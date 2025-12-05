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

            $headerKey = 7;
            if (!isset($rows[$headerKey])) {
                $headerKey = array_key_first($rows);
            }
            $headerRow = array_map(function($v){ return is_string($v) ? mb_strtolower(trim($v)) : ''; }, $rows[$headerKey]);

            $extCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                if (strpos($colName, 'ext') !== false || strpos($colName, 'extension') !== false || 
                    strpos($colName, 'extensión') !== false || strpos($colName, 'origen') !== false) {
                    $extCol = $colLetter; break;
                }
            }
            if ($extCol === null) $extCol = 'B';

            $durCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                if (strpos($colName, 'duraci') !== false || strpos($colName, 'duracion') !== false) {
                    $durCol = $colLetter; break;
                }
            }
            if ($durCol === null) {
                foreach ($headerRow as $colLetter => $colName) {
                    if (preg_match('/\bdur/i', $colName)) {
                        $durCol = $colLetter; break;
                    }
                }
            }
            if ($durCol === null) {
                foreach ($headerRow as $colLetter => $colName) {
                    if (strpos($colName, 'tiemp') !== false || strpos($colName, 'time') !== false || 
                        strpos($colName, 'horas') !== false || strpos($colName, 'hora') !== false) {
                        $durCol = $colLetter; break;
                    }
                }
            }
            if ($durCol === null) {
                $durCol = 'D';
            }

            $agentCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                if (strpos($colName, 'agente que atendio') !== false) {
                    $agentCol = $colLetter; break;
                }
            }
            if ($agentCol === null) {
                foreach ($headerRow as $colLetter => $colName) {
                    if (strpos($colName, 'agente') !== false) {
                        $agentCol = $colLetter; break;
                    }
                }
            }
            if ($agentCol === null) {
                $cols = array_keys($headerRow);
                $agentCol = end($cols);
            }

            $fechaCol = null;
            foreach ($headerRow as $colLetter => $colName) {
                if (strpos($colName, 'fecha') !== false || strpos($colName, 'hora') !== false) {
                    $fechaCol = $colLetter; break;
                }
            }
            if ($fechaCol === null) {
                $fechaCol = 'A';
            }

            $targetExt = ['331','362'];
            $totals = [ '331' => 0, '362' => 0 ];
            $agentNames = [ '331' => '', '362' => '' ]; 
            $hourlyCounts = [ '331' => [], '362' => [] ]; 
            $almuerzos = []; 
            $almuerzoMarkers = [ '331' => [], '362' => [] ];
            $firstLogueo = [ '331' => '', '362' => '' ]; 
            $firstLogueoTs = [ '331' => null, '362' => null ]; 
            
            $totalAll = 0;
            $allHours = []; 

            foreach ($rows as $rIndex => $row) {
                if ((int)$rIndex <= (int)$headerKey) continue;

                $extVal = isset($row[$extCol]) ? trim((string)$row[$extCol]) : '';
                $extValNum = preg_replace('/[^0-9]/', '', $extVal);

                if (!in_array($extValNum, $targetExt)) continue;

                $durValRaw = $row[$durCol] ?? '';
                $seconds = $this->parseDurationToSeconds($durValRaw);
                $totals[$extValNum] = ($totals[$extValNum] ?? 0) + $seconds;
                $totalAll += $seconds;
                
                $agentVal = isset($row[$agentCol]) ? trim((string)$row[$agentCol]) : '';
                if ($agentVal !== '' && ($agentNames[$extValNum] ?? '') === '') {
                    $agentNames[$extValNum] = $agentVal;
                }

                $fechaVal = isset($row[$fechaCol]) ? trim((string)$row[$fechaCol]) : '';
                $hourNum = $this->extractHourNumber($fechaVal);

                $nextHour = $hourNum + 1;
                $colHora = sprintf('%02d:00', $nextHour);

                if (!in_array($colHora, $allHours)) {
                    $allHours[] = $colHora;
                }

                if (!array_key_exists($extValNum, $almuerzos)) {
                    $almuerzos[$extValNum] = $this->getAlmuerzoByExtension($extValNum);
                }
                $alm = $almuerzos[$extValNum];

                $almHour = null;
                if ($alm !== null) {
                    $almInt = (int)$alm;
                    if ($almInt === 1) $almHour = '12:00';
                    elseif ($almInt === 2 || $almInt === 3) $almHour = '13:00';
                    elseif ($almInt === 4) $almHour = '14:00';
                }

                if ($almHour !== null && $colHora === $almHour) {
                    $almuerzoMarkers[$extValNum][$almHour] = true;

                    $nextHourNum = $nextHour + 1;
                    if ($nextHourNum > 23) $nextHourNum = 23;
                    $nextAlmHour = sprintf('%02d:00', $nextHourNum);

                    if (!isset($hourlyCounts[$extValNum][$nextAlmHour])) {
                        $hourlyCounts[$extValNum][$nextAlmHour] = 0;
                    }
                    $hourlyCounts[$extValNum][$nextAlmHour] += 1;

                    if (!in_array($nextAlmHour, $allHours)) $allHours[] = $nextAlmHour;
                } else {
                    if (!isset($hourlyCounts[$extValNum][$colHora])) {
                        $hourlyCounts[$extValNum][$colHora] = 0;
                    }
                    $hourlyCounts[$extValNum][$colHora] += 1;
                }

                if (!empty($fechaVal)) {
                    $ts = strtotime($fechaVal);
                    if ($ts !== false) {
                        if ($firstLogueoTs[$extValNum] === null || $ts < $firstLogueoTs[$extValNum]) {
                            $firstLogueoTs[$extValNum] = $ts;
                            $firstLogueo[$extValNum] = date('H:i', $ts);
                        }
                    } else {
                        if (empty($firstLogueo[$extValNum])) {
                            if (preg_match('/(\d{1,2}:\d{2})/', $fechaVal, $mm)) {
                                $firstLogueo[$extValNum] = $mm[1];
                            } else {
                                $firstLogueo[$extValNum] = $fechaVal;
                            }
                        }
                    }
                }
            }

            usort($allHours, function($a, $b) {
                $aNum = (int)explode(':', $a)[0];
                $bNum = (int)explode(':', $b)[0];
                return $aNum - $bNum;
            });
            
            $hours = [];
            foreach ($allHours as $h) {
                $hourNum = (int)explode(':', $h)[0];
                if ($hourNum >= 8) {
                    $hours[] = $h;
                }
            }
            if (empty($hours)) {
                $hours = $allHours;
            }

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
                        $almuerzoMarkers[$extKey][$almHour] = true;
                        if (!in_array($almHour, $hours)) $hours[] = $almHour;
                        $hourNum = (int)explode(':', $almHour)[0];
                        $nextHour = sprintf('%02d:00', min(23, $hourNum + 1));
                        if (!in_array($nextHour, $hours)) $hours[] = $nextHour;
                    }
                }
            }

            usort($hours, function($a, $b) {
                $aNum = (int)explode(':', $a)[0];
                $bNum = (int)explode(':', $b)[0];
                return $aNum - $bNum;
            });

            $headerRow = ['Usuario BestVoIper', 'Extensión', 'Logueo'];
            foreach ($hours as $h) {
                $headerRow[] = $h;
            }
            $headerRow[] = 'Total Gestion';
            $headerRow[] = 'Total';
            
            $exportRows = [$headerRow];

            foreach ($targetExt as $ext) {
                $bestVoIper = $this->getBestVoIperByExtension($ext);
                $row = [$bestVoIper, $ext, $firstLogueo[$ext] ?? ''];
                
                $totalGestiones = 0;
                foreach ($hours as $h) {
                    if (!empty($almuerzoMarkers[$ext][$h])) {
                        $row[] = 'ALMUERZO';
                        continue;
                    }

                    $count = $hourlyCounts[$ext][$h] ?? 0;
                    $row[] = $count;
                    $totalGestiones += $count;
                }

                $row[] = $totalGestiones;

                $totalSeconds = $totals[$ext] ?? 0;
                $row[] = $this->secondsToHms($totalSeconds);
                
                $exportRows[] = $row;
            }

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
        
        if (preg_match('/(\d{1,2}):/', $s, $m)) {
            return (int)$m[1];
        }
        
        return 0;
    }
    
    private function parseDurationToSeconds($val)
    {
        if ($val === null) return 0;
        $s = trim((string)$val);
        if ($s === '') return 0;

        if (is_numeric($s)) {
            $f = (float)$s;
            if ($f > 0 && $f < 1) {
                return (int)round($f * 86400); 
            }
            return (int)round($f);
        }

        $s = str_replace(',', ':', $s);
        if (strpos($s, ':') !== false) {
            $parts = array_map('intval', array_reverse(explode(':', $s)));
            $seconds = 0;
            if (isset($parts[0])) $seconds += $parts[0]; 
            if (isset($parts[1])) $seconds += $parts[1] * 60; 
            if (isset($parts[2])) $seconds += $parts[2] * 3600; 
            return $seconds;
        }

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
            $usuario = Usuario::whereHas('bestRelacion', function ($query) use ($extension) {
                $query->where('extension', $extension);
            })->first();

            if ($usuario && $usuario->bestRelacion) {
                return $usuario->bestRelacion->nombre_usuario;
            }
        } catch (\Exception $e) {
            return '';
        }
        return '';
    }


    private function getAlmuerzoByExtension($extension)
    {
        try {
            $usuario = Usuario::whereHas('bestRelacion', function ($query) use ($extension) {
                $query->where('extension', $extension);
            })->first();

            if ($usuario && isset($usuario->almuerzo)) {
                return $usuario->almuerzo;
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}