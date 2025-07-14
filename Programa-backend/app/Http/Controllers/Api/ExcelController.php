<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BaseGestionImport;
use App\Exports\DatosExport;

class ExcelController extends Controller
{
   
    public function procesar(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $horaLimite = $request->input('hora_limite', 18); // 18 por defecto
        $tipoInforme = $request->input('tipo_informe', 'horas'); // 'horas' por defecto
        $carteraFiltro = $request->input('cartera', ''); // Cartera específica para filtrar

        $datos = \Maatwebsite\Excel\Facades\Excel::toArray([], $request->file('file'))[0];

        $normalizar = function($cadena) {
            $cadena = strtolower($cadena);
            $cadena = preg_replace('/[áàäâ]/u', 'a', $cadena);
            $cadena = preg_replace('/[éèëê]/u', 'e', $cadena);
            $cadena = preg_replace('/[íìïî]/u', 'i', $cadena);
            $cadena = preg_replace('/[óòöô]/u', 'o', $cadena);
            $cadena = preg_replace('/[úùüû]/u', 'u', $cadena);
            $cadena = preg_replace('/[^a-z0-9 ]/', '', $cadena);
            $cadena = trim($cadena);
            return $cadena;
        };

        $headings = array_map($normalizar, $datos[0]);
        $indices = [
            'nombre_completo' => array_search('nombre completo', $headings),
            'hora' => array_search('hora', $headings) !== false ? array_search('hora', $headings) : array_search('hora', $headings),
        ];

        foreach ($indices as $nombre => $indice) {
            if ($indice === false) {
                return response()->json(['error' => "Columna '$nombre' no encontrada"], 400);
            }
        }

        // Función para extraer la hora válida de la cadena
        function extraerHora($cadena) {
            // Busca la primera coincidencia de HH:MM en la cadena
            if (preg_match('/\\b([01]?\\d|2[0-3]):[0-5]\\d\\b/', $cadena, $matches)) {
                return intval($matches[1]);
            }
            return null;
        }

        // Recolectar todas las horas presentes en los datos
        $horas_encontradas = [];
        $resumen = [];
        foreach (array_slice($datos, 1) as $fila) {
            $nombre = $fila[$indices['nombre_completo']] ?? '';
            // Si el nombre empieza con 'Outsourcing NGSO -', eliminar esa parte
            if (stripos($nombre, 'Outsourcing NGSO -') === 0) {
                $nombre = trim(substr($nombre, strlen('Outsourcing NGSO -')));
            }
            if ($nombre === '') {
                continue; // Ignora filas sin nombre real
            }
            $hora_original = trim($fila[$indices['hora']] ?? '');
            $hora = null;
            $partes = explode(' ', $hora_original);
            $hora_str = end($partes);
            if (preg_match('/^([01]?\d|2[0-3]):[0-5]\d$/', $hora_str, $matches)) {
                $hora = intval($matches[1]);
                $hora++; // Sumar 1 a la hora extraída
            } else {
                continue;
            }
            if ($hora < 7 || $hora > $horaLimite) {
                continue; // Ignora horas fuera del rango 7-horaLimite
            }
            $horas_encontradas[$hora] = true;
            if (!isset($resumen[$nombre])) {
                $resumen[$nombre] = [];
            }
            if (!isset($resumen[$nombre][$hora])) {
                $resumen[$nombre][$hora] = 0;
            }
            $resumen[$nombre][$hora]++;
        }
        // Ordenar las horas presentes
        $horas = array_keys($horas_encontradas);
        sort($horas);
        $horas_formato = array_map(function($h) { return $h . ':00'; }, $horas);

        $usuarios = \App\Models\Usuario::all()->keyBy('nombre_usuario_huella');

        $filas = [];
        $usuariosIncluidos = [];
        foreach ($resumen as $nombre_excel => $conteos) {
            $nombre_normalizado = $normalizar($nombre_excel);
            $usuario = $usuarios->first(function($u) use ($normalizar, $nombre_normalizado) {
                return $normalizar($u->nombre_usuario_huella) === $nombre_normalizado;
            });
            $nombre_completo_bd = $usuario ? trim($usuario->nombres . ' ' . $usuario->apellidos) : '';
            $cartera = $usuario ? $usuario->cartera : '';
            // Filtrar por cartera si se especifica
            if ($carteraFiltro && $cartera !== $carteraFiltro) {
                continue; // Saltar esta fila si no coincide con la cartera filtrada
            }
            $usuariosIncluidos[$usuario ? $usuario->nombre_usuario_huella : $nombre_excel] = true;
            \Log::info('Fila final', [
                'nombre_excel' => $nombre_excel,
                'nombre_completo_bd' => $nombre_completo_bd,
                'cartera' => $cartera,
                'usuario' => $usuario,
                'cartera_filtro' => $carteraFiltro
            ]);
            $total = 0;
            $fila = [$nombre_excel, $nombre_completo_bd, $cartera];
            foreach ($horas as $h) {
                $valor = $conteos[$h] ?? 0;
                $fila[] = $valor;
                $total += $valor;
            }
            $fila[] = $total;
            $filas[] = $fila;
        }
        // Agregar usuarios de la base de datos que no hicieron ninguna gestión
        foreach ($usuarios as $usuario) {
            if (isset($usuariosIncluidos[$usuario->nombre_usuario_huella])) {
                continue; // Ya está incluido
            }
            // Filtrar por cartera si se especifica
            if ($carteraFiltro && $usuario->cartera !== $carteraFiltro) {
                continue;
            }
            $fila = [
                $usuario->nombre_usuario_huella, // Mostrar nombre_usuario_huella en la columna 'Nombre completo Excel'
                trim($usuario->nombres . ' ' . $usuario->apellidos),
                $usuario->cartera
            ];
            foreach ($horas as $h) {
                $fila[] = 0;
            }
            $fila[] = 0; // Total
            $filas[] = $fila;
        }
        if ($tipoInforme === 'cartera') {
            // Generar informe por cartera
            $resumen_cartera = [];
            foreach ($resumen as $nombre_excel => $conteos) {
                $nombre_normalizado = $normalizar($nombre_excel);
                $usuario = $usuarios->first(function($u) use ($normalizar, $nombre_normalizado) {
                    return $normalizar($u->nombre_usuario_huella) === $nombre_normalizado;
                });
                
                $cartera = $usuario ? $usuario->cartera : 'Sin cartera';
                
                // Filtrar por cartera si se especifica
                if ($carteraFiltro && $cartera !== $carteraFiltro) {
                    continue; // Saltar esta fila si no coincide con la cartera filtrada
                }
                
                $total_persona = array_sum($conteos);
                
                if (!isset($resumen_cartera[$cartera])) {
                    $resumen_cartera[$cartera] = [
                        'total_gestiones' => 0,
                        'total_personas' => 0,
                        'personas' => []
                    ];
                }
                
                $resumen_cartera[$cartera]['total_gestiones'] += $total_persona;
                $resumen_cartera[$cartera]['total_personas']++;
                $resumen_cartera[$cartera]['personas'][] = [
                    'nombre' => $nombre_excel,
                    'nombre_bd' => $usuario ? trim($usuario->nombres . ' ' . $usuario->apellidos) : '',
                    'total' => $total_persona
                ];
            }
            
            // Crear filas para el informe por cartera
            $filas_cartera = [];
            foreach ($resumen_cartera as $cartera => $datos) {
                $filas_cartera[] = [
                    $cartera,
                    $datos['total_personas'],
                    $datos['total_gestiones'],
                    round($datos['total_gestiones'] / $datos['total_personas'], 2)
                ];
            }
            
            $encabezados_cartera = ['Cartera', 'Total Personas', 'Total Gestiones', 'Promedio por Persona'];
            $export = new \App\Exports\DatosExport($filas_cartera, $encabezados_cartera);
            return \Maatwebsite\Excel\Facades\Excel::download($export, 'resumen_gestiones_cartera.xlsx');
        } else {
            // Informe por horas (lógica existente)
            $encabezados = array_merge(['Asesor', 'Asesor real', 'Cartera'], $horas_formato, ['Total']);
            $export = new \App\Exports\DatosExport($filas, $encabezados);
            return \Maatwebsite\Excel\Facades\Excel::download($export, 'resumen_gestiones_horas.xlsx');
        }
    }

   
}