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
            $todosCero = true;
            foreach ($horas as $h) {
                $valor = $conteos[$h] ?? 0;
                $fila[] = $valor;
                $total += $valor;
                if ($valor != 0) {
                    $todosCero = false;
                }
            }
            $fila[] = $total;
            if ($total != 0) {
                $todosCero = false;
            }
            $fila[] = $todosCero ? 'NOVEDAD' : 'SIN NOVEDAD';
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
                $usuario->nombre_usuario_huella,
                trim($usuario->nombres . ' ' . $usuario->apellidos),
                $usuario->cartera
            ];
            foreach ($horas as $h) {
                $fila[] = 0;
            }
            $fila[] = 0; // Total
            $fila[] = 'NOVEDAD'; // Todos ceros
            $filas[] = $fila;
        }

        $encabezados = array_merge(['Asesor', 'Asesor real', 'Cartera'], $horas_formato, ['Total', 'NOVEDAD']);

        // Ordenar filas por cartera (columna 2)
        usort($filas, function($a, $b) {
            return strcmp($a[2], $b[2]);
        });

        $export = new \App\Exports\DatosExport($filas, $encabezados);
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'resumen_gestiones_horas.xlsx');
    }
}