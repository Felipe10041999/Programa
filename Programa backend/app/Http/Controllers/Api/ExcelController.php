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
            // Si el nombre empieza con 'Outsourcing NGSO ', eliminar esa parte
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

        $filas = [];
        foreach ($resumen as $nombre => $conteos) {
            $total = 0;
            $fila = [$nombre];
            foreach ($horas as $h) {
                // Si no existe dato para la hora, poner cero
                $valor = array_key_exists($h, $conteos) ? $conteos[$h] : 0;
                $fila[] = $valor;
                $total += $valor;
            }
            $fila[] = $total;
            $filas[] = $fila;
        }
        $encabezados = array_merge(['Nombre completo'], $horas_formato, ['Total gestiones']);

        $export = new \App\Exports\DatosExport($filas, $encabezados);
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'resumen_gestiones.xlsx');
    }

   
}