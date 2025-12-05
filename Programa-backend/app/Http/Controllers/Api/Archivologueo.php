<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use App\Exports\LogueoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Usuario; 

class Archivologueo extends Controller
{
    public function subir(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('archivo');
        $path = $file->store('temp');

        $spreadsheet = IOFactory::load(Storage::path($path));
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $usuarios = Usuario::with('bestRelacion') 
            ->where('cartera', '!=', 'lider')
            ->get();

        $resultados = [];
        $usuariosPorCartera = [];

        foreach ($usuarios as $usuario) {
            $cartera = $usuario->cartera;
            if (!isset($usuariosPorCartera[$cartera])) {
                $usuariosPorCartera[$cartera] = [];
            }
            $usuariosPorCartera[$cartera][] = $usuario;
        }

        ksort($usuariosPorCartera);

        foreach ($usuariosPorCartera as $cartera => $usuarios) {
            foreach ($usuarios as $usuario) {

                $extension = optional($usuario->bestRelacion)->extension;

                $marcaciones = array_filter($rows, function($row) use ($extension) {
                    return isset($row[1]) && $row[1] == $extension;
                });

                $horaMasTemprana = null;
                $horaMasTempranaStr = null;
                foreach ($marcaciones as $marcacion) {
                    if (isset($marcacion[0])) {
                        $fechaHora = $marcacion[0];
                        $timestamp = strtotime($fechaHora);
                        if ($timestamp !== false) {
                            $hora = date('H:i:s', $timestamp);
                            if ($horaMasTemprana === null || $timestamp < $horaMasTemprana) {
                                $horaMasTemprana = $timestamp;
                                $horaMasTempranaStr = $hora;
                            }
                        }
                    }
                }

                $minutosSobrantes = null;
                if ($horaMasTempranaStr !== null) {
                    $minutosSobrantes = $this->calcularMinutosSobrantes($horaMasTempranaStr);
                }
                $minutosSobrantesDisplay = ($minutosSobrantes === null || $minutosSobrantes === 0) ? 'A tiempo' : $minutosSobrantes;

                $resultados[] = [
                    'Asesor' => $usuario->nombres . ' ' . $usuario->apellidos,
                    'Extensión' => $extension,
                    'Cartera' => $cartera,
                    'Logueo' => $horaMasTempranaStr,
                    'Minutos Sobrantes' => $minutosSobrantesDisplay,
                ];
            }
        }

        Storage::delete($path);

        $filename = 'archivo_hora_ingreso_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new LogueoExport($resultados), $filename);
    }

    private function calcularMinutosSobrantes($hora) {
        $horaActual = strtotime($hora);
        $horaLimite = strtotime('07:30:00');
        if ($horaActual <= $horaLimite) {
            return 0;
        } else {
            $diferencia = $horaActual - $horaLimite;
            return floor($diferencia / 60);
        }
    }
}
