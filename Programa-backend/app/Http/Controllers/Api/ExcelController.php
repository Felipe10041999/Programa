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
            'file3' => 'required|file|mimes:xlsx,xls',
        ]);

        $horaLimite = $request->input('hora_limite', 18);
        $carteraSeleccionada = $request->input('cartera', '');
        $usuarios = Usuario::with('huellaRelacion')->get();

        $resumen1 = $this->leerArchivoProductividad($request->file('file'), $usuarios, $horaLimite);
        $resumen2 = $this->leerArchivoGrabaciones($request->file('file2'), $usuarios, $horaLimite);
        $resumen3 = $this->leerArchivoProductividad($request->file('file3'), $usuarios, $horaLimite);

        [$filas, $encabezados] = $this->generarResumenFinalTres($resumen1, $resumen2, $resumen3, $usuarios, $carteraSeleccionada);

        return $this->exportarExcel($filas, $encabezados);
    }

    private function generarResumenFinalTres($resumen1, $resumen2, $resumen3, $usuarios, $carteraSeleccionada = '')
    {
        if (!is_array($resumen2) || !isset($resumen2['resumen']) || !isset($resumen2['primerMarcacion'])) {
            $resumen2 = [
                'resumen' => is_array($resumen2) ? $resumen2 : [],
                'primerMarcacion' => []
            ];
        }

        $horas = array_unique(array_merge(
            ...array_map('array_keys', array_merge(array_values($resumen1), array_values($resumen2['resumen']), array_values($resumen3)))
        ));
        $horas = array_filter($horas, fn($h) => intval($h) !== 7);
        sort($horas);

        $filas = [];
        $todos = array_unique(array_merge(array_keys($resumen1), array_keys($resumen2['resumen']), array_keys($resumen3)));

        foreach ($usuarios as $usuario) {
            $nombreUsuario = $this->normalizar($usuario->huellaRelacion->nombre_usuario ?? '');
            $todos[] = $nombreUsuario;
        }
        $todos = array_unique($todos);

        $porCartera = [];
        foreach ($todos as $keyNorm) {
            if (empty(trim($keyNorm))) continue;
            $usuario = $this->buscarUsuarioPorNombreUsuarioHuella($keyNorm, $usuarios)
                ?? $this->buscarUsuarioPorNombreCompleto($keyNorm, $usuarios);

            $cartera = $usuario ? $usuario->cartera : '';
            if (strtoupper(trim($cartera)) === 'LIDER') continue;

            $porCartera[$cartera][] = [
                'keyNorm' => $keyNorm,
                'usuario' => $usuario
            ];
        }

        foreach ($porCartera as $cartera => $asesores) {
            $contador = 1;
            foreach ($asesores as $info) {
                $keyNorm = $info['keyNorm'];
                $usuario = $info['usuario'];
                $nombreReal = $usuario ? trim($usuario->nombres . ' ' . $usuario->apellidos) : '';
                $tp = $tg = 0;
                $valores = [];
                $primerMarcacion = $resumen2['primerMarcacion'][$keyNorm] ?? '';

                foreach ($horas as $h) {
                    $p1 = $resumen1[$keyNorm][$h] ?? 0;
                    $p3 = $resumen3[$keyNorm][$h] ?? 0;
                    $productividad = $p1 + $p3;
                    $g = $resumen2['resumen'][$keyNorm][$h] ?? 0;

                    $valores[] = $productividad;
                    $valores[] = $g;
                    $tp += $productividad;
                    $tg += $g;
                }

                $valores[] = $tp;
                $valores[] = $tg;
                $novedad = array_sum($valores) > 0 ? 'SIN NOVEDAD' : 'NOVEDAD';

                $fila = [$contador, $keyNorm, $nombreReal, $cartera, $primerMarcacion];
                $fila = array_merge($fila, $valores);
                $fila[] = $novedad;
                $filas[] = $fila;
                $contador++;
            }
        }

        $enc = ['N°', 'Asesor', 'Asesor Real', 'Cartera', 'Primer Marcacion'];
        foreach ($horas as $h) {
            $enc[] = $h . ':00 Productividad';
            $enc[] = $h . ':00 Grabaciones';
        }
        $enc[] = 'Total Productividad';
        $enc[] = 'Total Grabaciones';
        $enc[] = 'Novedad';

        return [$filas, $enc];
    }

    private function leerArchivoProductividad($file, $usuarios, $horaLimite)
    {
        $datos = Excel::toArray([], $file)[0];
        $headings = array_map([$this, 'normalizar'], $datos[2]);

        $idxNombre = array_search('nombre completo', $headings);
        $fechaCreacionIndices = array_keys($headings, 'fecha de creacion');
        $idxHora = $fechaCreacionIndices[1] ?? false;
        $idxGestion = array_search('gestion de pago', $headings);

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

            if ($idxGestion !== false) {
                $gestion = $this->normalizar($fila[$idxGestion] ?? '');
                if ($gestion === 'sin gestion') continue;
            }

            $horaExtraida = $this->extraerHora($fila[$idxHora] ?? '');
            if (!$horaExtraida || $horaExtraida < 7 || $horaExtraida > $horaLimite) continue;

            $nombreNormalizado = $this->normalizar($nombre);
            $usuario = $this->buscarUsuarioPorNombreCompleto($nombre, $usuarios);
            $clave = $usuario ? $this->normalizar($usuario->huellaRelacion->nombre_usuario ?? '') : $nombreNormalizado;

            $resumen[$clave][$horaExtraida] = ($resumen[$clave][$horaExtraida] ?? 0) + 1;
        }

        return $resumen;
    }

    private function leerArchivoGrabaciones($file2, $usuarios, $horaLimite)
    {
        $datos = Excel::toArray([], $file2)[0];
        $headings = array_map([$this, 'normalizar'], $datos[6]);

        $idxAgente = $idxFechaHora = $idxOrigen = null;

        foreach ($headings as $i => $col) {
            if (in_array($col, ['agente que atendio', 'agente que atendió', 'agente'])) {
                $idxAgente = $i;
            }
            if (in_array($col, ['fechahora', 'fecha hora', 'fecha/hora'])) {
                $idxFechaHora = $i;
            }
            if ($col === 'origen') {
                $idxOrigen = $i;
            }
        }

        if ($idxFechaHora === null) {
            abort(422, "Columna 'Fecha/Hora' no encontrada.");
        }

        $resumen = [];
        $primerMarcacion = [];

        foreach (array_slice($datos, 7) as $fila) {
            $agente = $idxAgente !== null ? trim($fila[$idxAgente] ?? '') : '';
            $fechaHora = trim($fila[$idxFechaHora] ?? '');
            $origen = $idxOrigen !== null ? trim($fila[$idxOrigen] ?? '') : '';

            $horaExtra = $this->extraerHora($fechaHora);
            if (!$horaExtra || $horaExtra < 7 || $horaExtra > $horaLimite) continue;

            $clave = null;
            if ($agente !== '') {
                $agNorm = $this->normalizar($agente);
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
                if ($mejorPct >= 70 && $mejorUsuario) {
                    $clave = $this->normalizar($mejorUsuario->huellaRelacion->nombre_usuario ?? '');
                }
            }

            if ($clave === null && $origen !== '') {
                $usuarioPorExt = $usuarios->first(fn($u) => isset($u->extension) && $u->extension == $origen);
                if ($usuarioPorExt) {
                    $clave = $this->normalizar($usuarioPorExt->huellaRelacion->nombre_usuario ?? '');
                }
            }

            if ($clave === null && $agente !== '') {
                $usuario = $this->buscarUsuarioPorNombreCompleto($agente, $usuarios);
                if ($usuario) {
                    $clave = $this->normalizar($usuario->huellaRelacion->nombre_usuario ?? '');
                }
            }

            if ($clave === null) continue;

            $resumen[$clave][$horaExtra] = ($resumen[$clave][$horaExtra] ?? 0) + 1;

            if (!isset($primerMarcacion[$clave]) || strtotime($fechaHora) < strtotime($primerMarcacion[$clave])) {
                if (preg_match('/([01]?\d|2[0-3]):[0-5]\d/', $fechaHora, $m)) {
                    $primerMarcacion[$clave] = $m[0];
                }
            }
        }

        return ['resumen' => $resumen, 'primerMarcacion' => $primerMarcacion];
    }

    private function exportarExcel($filas, $encabezados)
    {
        $export = new DatosExport($filas, $encabezados);
        return Excel::download($export, 'reporte_resumen.xlsx');
    }

    private function normalizar($cadena)
    {
        $cadena = strtolower($cadena);
        $cadena = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
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
            return $this->normalizar($u->huellaRelacion->nombre_usuario ?? '') === $nombreNormalizado;
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

        return $mayorSimilitud >= 70 ? $mejorCoincidencia : null;
    }
}
