<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;
use App\Exports\LogueoExport;
use Maatwebsite\Excel\Facades\Excel;

class Archivologueo extends Controller
{
    public function subir(Request $request)
    {
        // Validar archivo
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls',
        ]);

        // Guardar archivo temporalmente
        $file = $request->file('archivo');
        $path = $file->store('temp');

        // Leer archivo Excel
        $spreadsheet = IOFactory::load(Storage::path($path));
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Obtener extensiones y asesores de la base de datos (excluyendo cartera lider)
        $extensiones = DB::table('usuarios')
            ->select('nombres', 'apellidos', 'extension', 'cartera')
            ->where('cartera', '!=', 'lider')
            ->get();

        // Procesar marcaciones
        $resultados = [];
        $usuariosPorCartera = [];

        // Agrupar usuarios por cartera
        foreach ($extensiones as $usuario) {
            $cartera = $usuario->cartera;
            if (!isset($usuariosPorCartera[$cartera])) {
                $usuariosPorCartera[$cartera] = [];
            }
            $usuariosPorCartera[$cartera][] = $usuario;
        }

        // Ordenar carteras alfabéticamente
        ksort($usuariosPorCartera);
        foreach ($usuariosPorCartera as $cartera => $usuarios) {
            foreach ($usuarios as $usuario) {
                // Buscar todas las marcaciones de la extensión en el archivo
                $marcaciones = array_filter($rows, function($row) use ($usuario) {
                    // Asume que la columna de extensión está en $row[1], ajusta según tu archivo
                    return isset($row[1]) && $row[1] == $usuario->extension;
                });

                // Encontrar la llamada más temprana del día
                $horaMasTemprana = null;
                $horaMasTempranaStr = null;
                foreach ($marcaciones as $marcacion) {
                    if (isset($marcacion[0])) {
                        $fechaHora = $marcacion[0];
                        // Convertir a timestamp para comparar
                        $timestamp = strtotime($fechaHora);
                        if ($timestamp !== false) {
                            // Extraer solo la hora (HH:MM:SS)
                            $hora = date('H:i:s', $timestamp);
                            // Si es la primera hora encontrada o es más temprana que la anterior
                            if ($horaMasTemprana === null || $timestamp < $horaMasTemprana) {
                                $horaMasTemprana = $timestamp;
                                $horaMasTempranaStr = $hora;
                            }
                        }
                    }
                }

                // Calcular los minutos que se pasaron después de las 7:30
                $minutosSobrantes = null;
                if ($horaMasTempranaStr !== null) {
                    $minutosSobrantes = $this->calcularMinutosSobrantes($horaMasTempranaStr);
                }
                // Modificar para mostrar "A tiempo" si minutosSobrantes es 0
                $minutosSobrantesDisplay = ($minutosSobrantes === null || $minutosSobrantes === 0) ? 'A tiempo' : $minutosSobrantes;

                $resultados[] = [
                    'Asesor' => $usuario->nombres . ' ' . $usuario->apellidos,
                    'Extensión' => $usuario->extension,
                    'Cartera' => $cartera,
                    'Logueo' => $horaMasTempranaStr,
                    'Minutos Sobrantes' => $minutosSobrantesDisplay,
                ];
            }
        }

        // Elimina archivo temporal
        Storage::delete($path);

        // Crear archivo Excel con diseño profesional usando Laravel Excel
        $filename = 'archivo_dos_resultado_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new LogueoExport($resultados), $filename);
    }

    // Método para calcular los minutos que se pasaron después de las 7:30
    private function calcularMinutosSobrantes($hora) {
        $horaActual = strtotime($hora);
        $horaLimite = strtotime('07:30:00');
        if ($horaActual <= $horaLimite) {
            // Si la hora es antes o igual a las 7:30, devuelve 0
            return 0;
        } else {
            $diferencia = $horaActual - $horaLimite;
            $minutosSobrantes = floor($diferencia / 60);
            return $minutosSobrantes;
        }
        foreach ($resultados as &$resultado) {
    if ($resultado['Minutos Sobrantes'] <= 0) {
        $resultado['Minutos Sobrantes'] .= ' (A tiempo)';
    }
}
    }

}