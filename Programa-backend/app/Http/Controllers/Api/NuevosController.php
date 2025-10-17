<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SolicitudesExport;
use App\Models\Usuario;

class NuevosController extends Controller
{
    public function gestionesNuevos(Request $request)
    {
        $request->validate([
            'archivo1' => 'required|file|mimes:xlsx,xls',
            'archivo2' => 'required|file|mimes:xlsx,xls',
        ]);

        $archivo1 = $request->file('archivo1');
        $archivo2 = $request->file('archivo2');
        $usuarios = \App\Models\Usuario::all();
        $datos1 = $this->leerArchivo1($archivo1);
        $resumen2 = $this->leerArchivo2($archivo2, $usuarios);
        return $this->generarArchivo($datos1, $resumen2, $usuarios);
    }

    public function leerArchivo1($archivo1)
{
    $datos1Array = \Maatwebsite\Excel\Facades\Excel::toArray([], $archivo1, null);
    $sheetIndex = 1;
    if (!isset($datos1Array[$sheetIndex])) {
        abort(422, 'No se encontró la segunda hoja en el archivo 1.');
    }
    $datos1 = $datos1Array[$sheetIndex];
    // Buscar encabezados
    $filaEncabezados = 0;
    foreach ($datos1 as $i => $fila) {
        if (is_array($fila) && count(array_filter($fila, fn($v) => trim((string)$v) !== '')) > 0) {
            $filaEncabezados = $i;
            break;
        }
    }
    $head1 = array_map(function($h) {
        return strtolower(trim((string)$h));
    }, $datos1[$filaEncabezados]);
    $idxSolicitud = array_search('solicitud', $head1);
    $idxAsignacion = array_search('asignacion', $head1);
    if ($idxSolicitud === false) {
        abort(422, 'Encabezado "solicitud" no encontrado en archivo 1.');
    }
    $datos1Filas = array_slice($datos1, $filaEncabezados + 1);
    // Retornar array asociativo solicitud => asignacion
    $solicitudes = [];
    foreach ($datos1Filas as $fila) {
        $solicitud = $fila[$idxSolicitud] ?? null;
        $asignacion = $idxAsignacion !== false ? ($fila[$idxAsignacion] ?? null) : null;
        if ($solicitud) {
            $solicitudes[$solicitud] = $asignacion;
        }
    }
    return $solicitudes;
}

    public function leerArchivo2($archivo2, $usuarios)
    {
        $datos = \Maatwebsite\Excel\Facades\Excel::toArray([], $archivo2)[0];
        $headings = array_map([$this, 'normalizar'], $datos[2]);
        $idxSolicitud = array_search('solicitud', $headings);
        $idxNombre = array_search('nombre completo', $headings);
        $fechaCreacionIndices = array_keys($headings, 'fecha de creacion');
        $idxFechaCreacion = isset($fechaCreacionIndices[1]) ? $fechaCreacionIndices[1] : false;
        $idxGestion = array_search('gestion de pago', $headings);
        if ($idxSolicitud === false || $idxNombre === false || $idxFechaCreacion === false || $idxGestion === false) {
            abort(422, 'Encabezados requeridos no encontrados en archivo 2.');
        }
        $registros = [];
        foreach (array_slice($datos, 3) as $fila) { // Saltar encabezados y subtotales
            $solicitud = trim($fila[$idxSolicitud] ?? '');
            $nombreCompleto = trim($fila[$idxNombre] ?? '');
            $fechaCreacion = trim($fila[$idxFechaCreacion] ?? '');
            $gestionDePago = trim($fila[$idxGestion] ?? '');
            if ($solicitud === '' || $nombreCompleto === '' || $fechaCreacion === '') continue;
            $registros[] = [
                'solicitud' => $solicitud,
                'nombre_completo' => $nombreCompleto,
                'fecha_creacion' => $fechaCreacion,
                'gestion_de_pago' => $gestionDePago
            ];
        }
        return ['registros' => $registros];
    }

   public function generarArchivo($solicitudes, $resumen2, $usuarios)
{
    $filas = [];
    $solicitudesUnicas = array_keys($solicitudes);
    $solicitudesProcesadas = [];
    foreach ($solicitudesUnicas as $solicitud) {
        // Buscar en archivo2 (resumen2) el registro con esa solicitud
        $registro = null;
        foreach ($resumen2['registros'] ?? [] as $r) {
            if (isset($r['solicitud']) && $r['solicitud'] == $solicitud) {
                $registro = $r;
                break;
            }
        }
        if (!$registro) {
            continue;
        }
        // Limpiar nombre completo (quitar Outsourcing NGSO -)
        $nombreCompleto = trim($registro['nombre_completo'] ?? '');
        if (stripos($nombreCompleto, 'Outsourcing NGSO -') === 0) {
            $nombreCompleto = trim(substr($nombreCompleto, strlen('Outsourcing NGSO -')));
        }
        // Buscar usuario por nombre completo en la base de datos
        $usuario = $this->buscarUsuarioPorNombreCompleto($nombreCompleto, $usuarios);
        $asesor = $usuario ? $usuario->nombre_usuario_huella : '';
        $asesorReal = $usuario ? trim($usuario->nombres . ' ' . $usuario->apellidos) : '';
        // Buscar gestiones de la solicitud antes y después de las 12
        $manana = $tarde = $gestionManana = $gestionTarde = '';
        foreach ($resumen2['registros'] as $r) {
            if (($r['solicitud'] ?? null) == $solicitud) {
                $hora = $this->extraerHora($r['fecha_creacion'] ?? '');
                $horaTexto = '';
                if (preg_match('/([01]?\d|2[0-3]):[0-5]\d/', $r['fecha_creacion'] ?? '', $m)) {
                    $horaTexto = $m[0];
                }
                if ($hora !== null && $hora < 13 && $manana === '') {
                    $manana = $horaTexto;
                    $gestionManana = $r['gestion_de_pago'] ?? '';
                }
                if ($hora !== null && $hora >= 13 && $tarde === '') {
                    $tarde = $horaTexto;
                    $gestionTarde = $r['gestion_de_pago'] ?? '';
                }
            }
        }
        if ($manana === '') {
            $manana = '00:00';
            $gestionManana = 'No aplica';
        }
        if ($tarde === '') {
            $tarde = '00:00';
            $gestionTarde = 'No aplica';
        }
        $filas[] = [
            $solicitud,
            $asesor,
            $asesorReal,
            $manana,
            $gestionManana,
            $tarde,
            $gestionTarde
        ];
        $solicitudesProcesadas[$solicitud] = true;
    }

    // Agregar solicitudes que no se encontraron en archivo 2 al final
    foreach ($solicitudesUnicas as $solicitud) {
        if (!isset($solicitudesProcesadas[$solicitud])) {
            // Buscar asignacion para esta solicitud
            $asignacion = $solicitudes[$solicitud] ?? '';
            // Buscar usuario por nombre_usuario_huella = asignacion
            $usuario = $usuarios->first(function($u) use ($asignacion) {
                return strtolower(trim($u->nombre_usuario_huella)) === strtolower(trim($asignacion));
            });
            $asesor = $usuario ? $usuario->nombre_usuario_huella : $asignacion;
            $asesorReal = $usuario ? trim($usuario->nombres . ' ' . $usuario->apellidos) : '';
            $filas[] = [
                $solicitud,
                $asesor,
                $asesorReal,
                '00:00', // Mañana
                'No aplica', // Gestión de pago mañana
                '00:00', // Tarde
                'No aplica', // Gestión de pago tarde
            ];
        }
    }

    $encabezados = ['Solicitud', 'Asesor', 'Asesor Real', 'Mañana', 'Gestion de pago (mañana)', 'Tarde', 'Gestion de pago (tarde)'];
    $export = new \App\Exports\SolicitudesExport($filas, $encabezados);
    return \Maatwebsite\Excel\Facades\Excel::download($export, 'reporte_solicitudes.xlsx');
}

    public function normalizar($cadena)
    {
        $cadena = strtolower($cadena);
        $cadena = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
        $cadena = preg_replace('/[^a-z0-9 ]/', '', $cadena);
        return trim(preg_replace('/\s+/', ' ', $cadena));
    }

    public function buscarUsuarioPorNombreCompleto($nombreTexto, $usuarios)
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

    public function extraerHora($texto)
    {
        if (preg_match('/([01]?\d|2[0-3]):[0-5]\d/', $texto, $matches)) {
            return intval($matches[1]) + 1;
        }
        return null;
    }
}