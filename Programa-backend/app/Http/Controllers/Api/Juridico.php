<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Exports\JuridicoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Usuario;

class Juridico extends Controller
{
    
    public function subir(Request $request)
    {
        $file = $request->file('file') ?? $request->file('archivo');
        if (!$file) {
            return response()->json(['error' => "Se requiere un archivo (campo 'file' o 'archivo')."], 422);
        }
        $path = $file->store('temp');

        try {
            $spreadsheet = IOFactory::load(Storage::path($path));
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (count($rows) === 0) {
                return response()->json(['error' => 'Archivo vacío o sin hojas.'], 422);
            }

            // Detectar encabezados: la fila de encabezado comienza en la fila 7
            $headerKey = 7;
            if (!isset($rows[$headerKey])) {
                // fallback: si no existe la fila 7, usar la primera fila disponible
                $headerKey = array_key_first($rows);
            }
            $headerRow = array_map(function($v){ return is_string($v) ? mb_strtolower(trim($v)) : ''; }, $rows[$headerKey]);

            // Buscar índice de columna de extensión (también buscar 'origen')
            $extCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                if (strpos($colName, 'ext') !== false || strpos($colName, 'extension') !== false || 
                    strpos($colName, 'extensión') !== false || strpos($colName, 'origen') !== false) {
                    $extCol = $colLetter; break;
                }
            }
            // Si no se detecta, usar la segunda columna por compatibilidad con archivos comunes
            if ($extCol === null) $extCol = 'B';

            // Buscar índice de columna de duración (búsqueda estricta)
            $durCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                // Priorizar coincidencias exactas o muy cercanas
                if (strpos($colName, 'duraci') !== false || strpos($colName, 'duracion') !== false) {
                    $durCol = $colLetter; break;
                }
            }
            if ($durCol === null) {
                // Segunda intención: buscar 'dur' pero asegurar que no sea parte de otra palabra
                foreach ($headerRow as $colLetter => $colName) {
                    if (preg_match('/\bdur/i', $colName)) {
                        $durCol = $colLetter; break;
                    }
                }
            }
            if ($durCol === null) {
                // Tercera: buscar 'tiempo', 'time', 'horas'
                foreach ($headerRow as $colLetter => $colName) {
                    if (strpos($colName, 'tiemp') !== false || strpos($colName, 'time') !== false || 
                        strpos($colName, 'horas') !== false || strpos($colName, 'hora') !== false) {
                        $durCol = $colLetter; break;
                    }
                }
            }
            // Si aún no se detecta, usar la cuarta columna (D en archivos típicos)
            if ($durCol === null) {
                $durCol = 'D';
            }

            // Buscar índice de columna de agente (búsqueda exacta: "Agente que Atend")
            $agentCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                // Buscar exactamente "Agente que Atend" (sin importar mayúsculas/minúsculas)
                if (strpos($colName, 'agente que atendio') !== false) {
                    $agentCol = $colLetter; break;
                }
            }
            // Si no se encuentra, buscar por palabras clave alternativas
            if ($agentCol === null) {
                foreach ($headerRow as $colLetter => $colName) {
                    if (strpos($colName, 'agente') !== false) {
                        $agentCol = $colLetter; break;
                    }
                }
            }
            // Fallback: última columna si no se detecta
            if ($agentCol === null) {
                $cols = array_keys($headerRow);
                $agentCol = end($cols);
            }

            // Buscar índice de columna de fecha/hora
            $fechaCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                if (strpos($colName, 'fecha') !== false || strpos($colName, 'hora') !== false) {
                    $fechaCol = $colLetter; break;
                }
            }
            // Fallback: primera columna si no se detecta
            if ($fechaCol === null) {
                $fechaCol = 'A';
            }

            $targetExt = ['331','362'];
            $totals = [ '331' => 0, '362' => 0 ];
            $agentNames = [ '331' => '', '362' => '' ]; // guardar primer agente no vacío por extensión
            $hourlyCounts = [ '331' => [], '362' => [] ]; // agrupar por hora: [ext][hora] => conteo de gestiones
            $almuerzos = []; // cache de almuerzo por extension
            $almuerzoMarkers = [ '331' => [], '362' => [] ]; // marcar 'ALMUERZO' en celdas por extension
            $firstLogueo = [ '331' => '', '362' => '' ]; // hora de la primera gestión por extensión (HH:MM:SS)
            $firstLogueoTs = [ '331' => null, '362' => null ]; // timestamp usado para comparar
            $totalAll = 0;
            $allHours = []; // para detectar todas las horas presentes en el archivo

            // Recorrer filas de datos (omitir las filas hasta la cabecera incluida)
            foreach ($rows as $rIndex => $row) {
                // Omitir filas anteriores o iguales a la fila de encabezado
                if ((int)$rIndex <= (int)$headerKey) continue;

                $extVal = isset($row[$extCol]) ? trim((string)$row[$extCol]) : '';
                // Normalizar solo números (en caso de venir con decimales o texto)
                $extValNum = preg_replace('/[^0-9]/', '', $extVal);

                if (!in_array($extValNum, $targetExt)) continue;

                $durValRaw = $row[$durCol] ?? '';
                $seconds = $this->parseDurationToSeconds($durValRaw);
                $totals[$extValNum] = ($totals[$extValNum] ?? 0) + $seconds;
                $totalAll += $seconds;
                
                // Obtener nombre del agente (guardar el primer valor no vacío que encontremos)
                $agentVal = isset($row[$agentCol]) ? trim((string)$row[$agentCol]) : '';
                if ($agentVal !== '' && ($agentNames[$extValNum] ?? '') === '') {
                    $agentNames[$extValNum] = $agentVal;
                }

                // Extraer hora de Fecha/Hora (formato esperado: "26-11-2025 13:58:18" o similar)
                $fechaVal = isset($row[$fechaCol]) ? trim((string)$row[$fechaCol]) : '';
                $hourNum = $this->extractHourNumber($fechaVal);

                // Agrupar gestiones por rango: gestiones de 7:00-7:59 -> columna 8:00, 8:00-8:59 -> columna 9:00, etc.
                $nextHour = $hourNum + 1;
                $colHora = sprintf('%02d:00', $nextHour);

                // Registrar todas las horas de columna encontradas
                if (!in_array($colHora, $allHours)) {
                    $allHours[] = $colHora;
                }

                // Obtener almuerzo desde BD (cacheado)
                if (!array_key_exists($extValNum, $almuerzos)) {
                    $almuerzos[$extValNum] = $this->getAlmuerzoByExtension($extValNum);
                }
                $alm = $almuerzos[$extValNum];

                // Mapear valor de almuerzo a hora objetivo: 1 -> 12:00, 2|3 -> 13:00, 4 -> 14:00
                $almHour = null;
                if ($alm !== null) {
                    $almInt = (int)$alm;
                    if ($almInt === 1) $almHour = '12:00';
                    elseif ($almInt === 2 || $almInt === 3) $almHour = '13:00';
                    elseif ($almInt === 4) $almHour = '14:00';
                }

                // Contabilizar gestión (la hora actual se agrupa en la siguiente columna)
                if ($almHour !== null && $colHora === $almHour) {
                    // Si esta gestión cae en la hora de almuerzo, marcar y mover a siguiente hora
                    $almuerzoMarkers[$extValNum][$almHour] = true;

                    // Calcular siguiente hora
                    $nextHourNum = $nextHour + 1;
                    if ($nextHourNum > 23) $nextHourNum = 23;
                    $nextAlmHour = sprintf('%02d:00', $nextHourNum);

                    if (!isset($hourlyCounts[$extValNum][$nextAlmHour])) {
                        $hourlyCounts[$extValNum][$nextAlmHour] = 0;
                    }
                    $hourlyCounts[$extValNum][$nextAlmHour] += 1;

                    // Asegurar que la siguiente hora aparezca en el listado
                    if (!in_array($nextAlmHour, $allHours)) $allHours[] = $nextAlmHour;
                } else {
                    if (!isset($hourlyCounts[$extValNum][$colHora])) {
                        $hourlyCounts[$extValNum][$colHora] = 0;
                    }
                    $hourlyCounts[$extValNum][$colHora] += 1;
                }

                // Registrar primer logueo (hora de la primera gestión) usando timestamp si es posible
                if (!empty($fechaVal)) {
                    $ts = strtotime($fechaVal);
                    if ($ts !== false) {
                        if ($firstLogueoTs[$extValNum] === null || $ts < $firstLogueoTs[$extValNum]) {
                            $firstLogueoTs[$extValNum] = $ts;
                            // Guardar logueo sin segundos (HH:MM)
                            $firstLogueo[$extValNum] = date('H:i', $ts);
                        }
                    } else {
                        // Si no se pudo parsear, usar valor bruto si no hay ninguno guardado
                        if (empty($firstLogueo[$extValNum])) {
                            // Intentar extraer hora y minutos del valor bruto
                            if (preg_match('/(\d{1,2}:\d{2})/', $fechaVal, $mm)) {
                                $firstLogueo[$extValNum] = $mm[1];
                            } else {
                                $firstLogueo[$extValNum] = $fechaVal;
                            }
                        }
                    }
                }
            }

            // Ordenar las horas encontradas
            usort($allHours, function($a, $b) {
                $aNum = (int)explode(':', $a)[0];
                $bNum = (int)explode(':', $b)[0];
                return $aNum - $bNum;
            });
            
            // Asegurar que comenzamos desde la hora 8 (gestiones de 7-8 van en columna 8)
            $hours = [];
            foreach ($allHours as $h) {
                $hourNum = (int)explode(':', $h)[0];
                if ($hourNum >= 8) {
                    $hours[] = $h;
                }
            }
            // Si no hay horas >= 8, usar todas las encontradas
            if (empty($hours)) {
                $hours = $allHours;
            }

            // Asegurar marcar ALMUERZO por extensión incluso si no hubo gestiones en esa hora
            foreach ($targetExt as $extKey) {
                if (!array_key_exists($extKey, $almuerzos)) {
                    $almuerzos[$extKey] = $this->getAlmuerzoByExtension($extKey);
                }
                $alm = $almuerzos[$extKey];
                if ($alm !== null) {
                    $almInt = (int)$alm;
                    $almHour = null;
                    if ($almInt === 1) $almHour = '12:00';
                    elseif ($almInt === 2 || $almInt === 3) $almHour = '13:00';
                    elseif ($almInt === 4) $almHour = '14:00';

                    if ($almHour !== null) {
                        // marcar ALMUERZO para esta extensión
                        $almuerzoMarkers[$extKey][$almHour] = true;
                        // asegurarse de que la columna de almuerzo y la siguiente estén en $hours
                        if (!in_array($almHour, $hours)) $hours[] = $almHour;
                        $hourNum = (int)explode(':', $almHour)[0];
                        $nextHour = sprintf('%02d:00', min(23, $hourNum + 1));
                        if (!in_array($nextHour, $hours)) $hours[] = $nextHour;
                    }
                }
            }

            // Reordenar horas después de insertar posibles horas de almuerzo
            usort($hours, function($a, $b) {
                $aNum = (int)explode(':', $a)[0];
                $bNum = (int)explode(':', $b)[0];
                return $aNum - $bNum;
            });

            // Preparar filas para exportar a Excel
            // Columnas: Usuario BestVoIper | Extensión | Logueo | [horas dinámicas - conteo] | Total Gestion | Total
            $headerRow = ['Usuario BestVoIper', 'Extensión', 'Logueo'];
            foreach ($hours as $h) {
                $headerRow[] = $h;
            }
            $headerRow[] = 'Total Gestion';
            $headerRow[] = 'Total';
            
            $exportRows = [$headerRow];

            // Agregar una sola fila por extensión con desglose por hora
            foreach ($targetExt as $ext) {
                // Obtener usuario_bestvoiper de la BD usando la extensión
                $bestVoIper = $this->getBestVoIperByExtension($ext);
                $row = [$bestVoIper, $ext, $firstLogueo[$ext] ?? ''];
                
                // Agregar conteo de gestiones por hora (en orden de $hours)
                $totalGestiones = 0;
                foreach ($hours as $h) {
                    // Si esta hora está marcada como ALMUERZO para esta extensión, mostrar texto y no contarla
                    if (!empty($almuerzoMarkers[$ext][$h])) {
                        $row[] = 'ALMUERZO';
                        continue;
                    }

                    $count = $hourlyCounts[$ext][$h] ?? 0;
                    $row[] = $count;
                    $totalGestiones += $count;
                }

                // Agregar total de gestiones (conteo)
                $row[] = $totalGestiones;

                // Agregar total (duración en HH:MM:SS)
                $totalSeconds = $totals[$ext] ?? 0;
                $row[] = $this->secondsToHms($totalSeconds);
                
                $exportRows[] = $row;
            }

            // Eliminar archivo temporal
            Storage::delete($path);

            $filename = 'juridico_total_duracion_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new JuridicoExport($exportRows), $filename);

        } catch (\Exception $e) {
            Storage::delete($path);
            return response()->json(['error' => 'Error procesando el archivo: ' . $e->getMessage()], 500);
        }
    }

   
    private function extractHourNumber($val)
    {
        if (empty($val)) return 0;
        $s = trim((string)$val);
        
        // Busca patrones como "13:58:18" o "13"
        if (preg_match('/(\d{1,2}):/', $s, $m)) {
            return (int)$m[1];
        }
        
        // Si no encuentra, devolver 0
        return 0;
    }
    
    private function parseDurationToSeconds($val)
    {
        if ($val === null) return 0;
        $s = trim((string)$val);
        if ($s === '') return 0;

        // Si es un número (posible segundos o fracción de día de Excel)
        if (is_numeric($s)) {
            // tratar floats pequeños como fracción de día (horas:min:seg en Excel)
            $f = (float)$s;
            if ($f > 0 && $f < 1) {
                return (int)round($f * 86400); // convertir fracción de día a segundos
            }
            return (int)round($f);
        }

        // Reemplazar coma por dos puntos si viene como 00,30
        $s = str_replace(',', ':', $s);
        // Extraer partes separadas por ':'
        if (strpos($s, ':') !== false) {
            $parts = array_map('intval', array_reverse(explode(':', $s)));
            $seconds = 0;
            if (isset($parts[0])) $seconds += $parts[0]; // segundos
            if (isset($parts[1])) $seconds += $parts[1] * 60; // minutos
            if (isset($parts[2])) $seconds += $parts[2] * 3600; // horas
            return $seconds;
        }

        // Intentar extraer números dentro del texto (regex mejorado: capturar hasta 3 dígitos)
        if (preg_match_all('/(\d{1,3})/', $s, $m)) {
            $nums = $m[0];
            if (count($nums) == 3) {
                return intval($nums[0])*3600 + intval($nums[1])*60 + intval($nums[2]);
            } elseif (count($nums) == 2) {
                return intval($nums[0])*60 + intval($nums[1]);
            } elseif (count($nums) == 1) {
                return intval($nums[0]);
            }
        }

        return 0;
    }
    
    private function secondsToHms($s)
    {
        $s = max(0, (int)$s);
        $h = floor($s / 3600);
        $m = floor(($s % 3600) / 60);
        $sec = $s % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $sec);
    }

    private function getBestVoIperByExtension($extension)
    {
        try {
            $usuario = Usuario::where('extension', $extension)->first();
            if ($usuario && !empty($usuario->usuario_bestvoiper)) {
                return $usuario->usuario_bestvoiper;
            }
        } catch (\Exception $e) {
            // Si hay error en la consulta, devolver vacío
            return '';
        }
        return '';
    }

    private function getAlmuerzoByExtension($extension)
    {
        try {
            $usuario = Usuario::where('extension', $extension)->first();
            if ($usuario && isset($usuario->almuerzo)) {
                return $usuario->almuerzo;
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
