<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DatosExport;

class ExcelController extends Controller
{
    public function procesar(Request $request)
{
    $request->validate([
        'file'  => 'required|file|mimes:xlsx,xls',
        'file2' => 'required|file|mimes:xlsx,xls',
    ]);

    $horaLimite = $request->input('hora_limite', 18);
    $carteraSeleccionada = $request->input('cartera', '');
    $usuarios   = Usuario::all();

    // 1. Procesar archivo 1
    $resumen1 = $this->leerArchivoProductividad($request->file('file'), $usuarios, $horaLimite);

    // 2. Procesar archivo 2 comparando con nombres reales en BD
    $resumen2 = $this->leerArchivoGrabaciones($request->file('file2'), $usuarios, $horaLimite);

    // 3. Generar resumen final combinando ambos
    [$filas, $encabezados] = $this->generarResumenFinal($resumen1, $resumen2, $usuarios, $carteraSeleccionada);

    return $this->exportarExcel($filas, $encabezados);
}

private function leerArchivoProductividad($file, $usuarios, $horaLimite)
{
    $datos = Excel::toArray([], $file)[0];
    $headings = array_map([$this, 'normalizar'], $datos[0]);

    $idxNombre = array_search('nombre completo', $headings);
    $idxHora = array_search('hora', $headings);

    if ($idxNombre === false || $idxHora === false) {
        abort(422, "Encabezados requeridos no encontrados en archivo de productividad.");
    }

    $resumen = [];

    foreach (array_slice($datos, 1) as $fila) {
        $nombre = trim($fila[$idxNombre] ?? '');
        if (stripos($nombre, 'Outsourcing NGSO -') === 0) {
            $nombre = trim(substr($nombre, strlen('Outsourcing NGSO -')));
        }

        if ($nombre === '') continue;

        $horaExtraida = $this->extraerHora($fila[$idxHora] ?? '');
        if (!$horaExtraida || $horaExtraida < 7 || $horaExtraida > $horaLimite) continue;

        $nombreNormalizado = $this->normalizar($nombre);
        $usuario = $this->buscarUsuarioPorNombreCompleto($nombre, $usuarios);

        $clave = $usuario
            ? $this->normalizar($usuario->nombre_usuario_huella)
            : $nombreNormalizado;

        if (!isset($resumen[$clave][$horaExtraida])) {
            $resumen[$clave][$horaExtraida] = 0;
        }

        $resumen[$clave][$horaExtraida]++;
    }

    return $resumen;
}

private function leerArchivoGrabaciones($file2, $usuarios, $horaLimite)
{
    $datos = Excel::toArray([], $file2)[0];
    $headings = array_map([$this, 'normalizar'], $datos[6]);
    $idxAgente = $idxFechaHora = null;

    foreach ($headings as $i => $col) {
        if (in_array($col, ['agente que atendio', 'agente que atendió', 'agente'])) {
            $idxAgente = $i;
        }
        if (in_array($col, ['fechahora', 'fecha hora', 'fecha/hora'])) {
            $idxFechaHora = $i;
        }
    }

    if ($idxAgente === null || $idxFechaHora === null) {
        abort(422, "Columnas 'Agente' o 'Fecha/Hora' no encontradas en archivo de grabaciones.");
    }

    $resumen = [];

    foreach (array_slice($datos, 7) as $fila) {
        $agente = trim($fila[$idxAgente] ?? '');
        $fechaHora = trim($fila[$idxFechaHora] ?? '');

        $horaExtra = $this->extraerHora($fechaHora);
        if (!$horaExtra || $horaExtra < 7 || $horaExtra > $horaLimite) continue;

        if ($agente === '') {
            // Agente vacío → agrupar como "SIN AGENTE"
            $clave = 'SIN AGENTE';
        } else {
            $agNorm = $this->normalizar($agente);

            // Buscar mejor coincidencia con nombres de usuarios
            $mejorUsuario = null;
            $mejorPct = 0;

            foreach ($usuarios as $u) {
                $nombreBD = $this->normalizar(trim($u->nombres . ' ' . $u->apellidos));
                similar_text($agNorm, $nombreBD, $pct);

                if ($pct > $mejorPct) {
                    $mejorPct = $pct;
                    $mejorUsuario = $u;
                }
            }

            $clave = $mejorPct >= 70
                ? $this->normalizar($mejorUsuario->nombre_usuario_huella)
                : $agNorm;
        }

        if (!isset($resumen[$clave][$horaExtra])) {
            $resumen[$clave][$horaExtra] = 0;
        }

        $resumen[$clave][$horaExtra]++;
    }

    return $resumen;
}

private function generarResumenFinal($resumen1, $resumen2, $usuarios, $carteraSeleccionada = '')
{
    $horas = array_unique(array_merge(
        ...array_map('array_keys', array_merge(array_values($resumen1), array_values($resumen2)))
    ));
    sort($horas);

    $filasUnificadas = [];
    $todos = array_unique(array_merge(array_keys($resumen1), array_keys($resumen2)));

    // Agregar todos los agentes de la base de datos (por nombre_usuario_huella normalizado)
    foreach ($usuarios as $usuario) {
        $keyNorm = $this->normalizar($usuario->nombre_usuario_huella);
        $todos[] = $keyNorm;
    }
    $todos = array_unique($todos);

    foreach ($todos as $keyNorm) {
        // Para cada clave, buscar usuario por huella o por nombres y apellidos
        $usuario = $this->buscarUsuarioPorNombreUsuarioHuella($keyNorm, $usuarios)
                 ?? $this->buscarUsuarioPorNombreCompleto($keyNorm, $usuarios);
        $nombreReal = $usuario
            ? trim($usuario->nombres . ' ' . $usuario->apellidos)
            : '';
        $cartera = $usuario ? $usuario->cartera : '';

        $tp = $tg = 0;
        $valores = [];
        foreach ($horas as $h) {
            $p = $resumen1[$keyNorm][$h] ?? 0;
            $g = $resumen2[$keyNorm][$h] ?? 0;
            $valores[] = $p;
            $valores[] = $g;
            $tp += $p;
            $tg += $g;
        }
        $valores[] = $tp;
        $valores[] = $tg;

        // Determinar novedad
        $tieneRegistros = array_sum($valores) > 0;
        $novedad = $tieneRegistros ? 'SIN NOVEDAD' : 'NOVEDAD';
        $valores[] = $novedad;

        // Unificar por clave (agente normalizado)
        if (!isset($filasUnificadas[$keyNorm])) {
            $filasUnificadas[$keyNorm] = [
                'asesor' => $keyNorm,
                'asesor_real' => $nombreReal,
                'cartera' => $cartera,
                'valores' => $valores
            ];
        } else {
            // Sumar valores si ya existe
            foreach ($valores as $i => $v) {
                if ($i < count($valores) - 1) { // Solo sumar los valores numéricos
                    $filasUnificadas[$keyNorm]['valores'][$i] += $v;
                }
            }
            // Recalcular novedad
            $tieneRegistros = array_sum(array_slice($filasUnificadas[$keyNorm]['valores'], 0, -1)) > 0;
            $filasUnificadas[$keyNorm]['valores'][count($valores) - 1] = $tieneRegistros ? 'SIN NOVEDAD' : 'NOVEDAD';
        }
    }

    // Convertir a array de filas
    $filas = [];
    $ordenC = ['CASTIGO'=>1,'DESISTIDOS'=>2,'DESOCUPADOS'=>3,'DESOCUPADOS 2022-2023'=>4,'SUPERNUMERARIO'=>5];
    usort($filasUnificadas, function($a, $b) use ($ordenC) {
        $carteraA = $a['cartera'] ?? '';
        $carteraB = $b['cartera'] ?? '';
        $ordenA = $ordenC[$carteraA] ?? 99;
        $ordenB = $ordenC[$carteraB] ?? 99;
        if ($ordenA === $ordenB) {
            return strcmp($a['asesor'], $b['asesor']);
        }
        return $ordenA - $ordenB;
    });
    $contadorPorCartera = [];
    foreach ($filasUnificadas as $f) {
        $cartera = $f['cartera'] ?? '';
        if ($cartera === 'LIDER') {
            continue; // Omitir registros de la cartera LIDER
        }
        // Filtro de cartera
        if ($carteraSeleccionada && $cartera !== $carteraSeleccionada) {
            continue;
        }
        if (!isset($contadorPorCartera[$cartera])) {
            $contadorPorCartera[$cartera] = 1;
        } else {
            $contadorPorCartera[$cartera]++;
        }
        $valores = $f['valores'];
        // Si cartera está vacía, dejar los valores de horas y totales vacíos
        if ($cartera === null || $cartera === '') {
            foreach ($valores as $i => $v) {
                if (is_numeric($v) && $v == 0) {
                    $valores[$i] = '';
                }
            }
        }
        $fila = [$contadorPorCartera[$cartera], $f['asesor'], $f['asesor_real'], $cartera];
        $fila = array_merge($fila, $valores);
        $filas[] = $fila;
    }

    // Cambiar encabezados
    $enc = ['Asesor','Asesor Real','Cartera'];
    foreach ($horas as $h) {
        $enc[] = $h.':00 Productividad';
        $enc[] = $h.':00 Grabaciones';
    }
    $enc[] = 'Total Productividad';
    $enc[] = 'Total Grabaciones';
    $enc[] = 'Novedad';

    return [$filas, $enc];
}


    private function exportarExcel($filas, $encabezados)
    {
        $export = new DatosExport($filas, $encabezados);
        return Excel::download($export, 'reporte_resumen.xlsx');
    }

    private function normalizar($cadena)
    {
        $cadena = strtolower($cadena);
        $cadena = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena); // Quita tildes
        $cadena = preg_replace('/[^a-z0-9 ]/', '', $cadena);
        return trim(preg_replace('/\s+/', ' ', $cadena));
    }

    private function extraerHora($texto)
    {
        if (preg_match('/([01]?\d|2[0-3]):[0-5]\d/', $texto, $matches)) {
            return intval($matches[1]) + 1;
        }
        return null;
    }

    private function buscarUsuarioPorNombreUsuarioHuella($nombreNormalizado, $usuarios)
    {
        return $usuarios->first(function ($u) use ($nombreNormalizado) {
            return $this->normalizar($u->nombre_usuario_huella) === $nombreNormalizado;
        });
    }

    private function buscarUsuarioPorNombreCompleto($nombreTexto, $usuarios)
    {
        $nombreNormalizado = $this->normalizar($nombreTexto);
    
        $mejorCoincidencia = null;
        $mayorSimilitud = 0;
    
        foreach ($usuarios as $u) {
            $nombreBD = $this->normalizar(trim($u->nombres . ' ' . $u->apellidos));
            similar_text($nombreNormalizado, $nombreBD, $porcentaje);
    
            if ($porcentaje > $mayorSimilitud) {
                $mayorSimilitud = $porcentaje;
                $mejorCoincidencia = $u;
            }
        }
    
        // Usamos 70% como umbral mínimo de similitud
        return $mayorSimilitud >= 70 ? $mejorCoincidencia : null;
    }

}
