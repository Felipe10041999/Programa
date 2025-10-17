<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DatosExport;
use Illuminate\Support\Facades\File;

class GenerarInformeAutomatizado extends Command
{
    protected $signature = 'informe:automatizado';
    protected $description = 'Genera el informe automáticamente tomando los archivos más recientes de cada carpeta';

    // Rutas de las carpetas
    private $carpeta1 = 'C:\\Users\\aprendiz.sena\\Downloads\\Documentos prueba\\Gestion Total';
    private $carpeta2 = 'C:\\Users\\aprendiz.sena\\Downloads\\Documentos prueba\\Gestion Castigada';
    private $carpeta3 = 'C:\\Users\\aprendiz.sena\\Downloads\\Documentos prueba\\Marcacion BestVoIper';
    // Usaremos el disco 'informes' configurado en config/filesystems.php

    public function handle()
    {
        $usuarios = Usuario::all();
        $horaLimite = 18; // Puedes parametrizar esto si lo necesitas

        // Obtener el archivo más reciente de cada carpeta
        $file1 = $this->getLatestFile($this->carpeta1);
        $file2 = $this->getLatestFile($this->carpeta2);
        $file3 = $this->getLatestFile($this->carpeta3);

        if (!$file1 || !$file2 || !$file3) {
            $this->error('No se encontraron archivos en una de las carpetas.');
            return 1;
        }
            
        $resumen1 = app('App\\Http\\Controllers\\Api\\ExcelController')->leerArchivoProductividad($file1, $usuarios, $horaLimite);
        $resumen2 = app('App\\Http\\Controllers\\Api\\ExcelController')->leerArchivoGrabaciones($file2, $usuarios, $horaLimite);
        $resumen3 = app('App\\Http\\Controllers\\Api\\ExcelController')->leerArchivoProductividad($file3, $usuarios, $horaLimite);

        [$filas, $encabezados] = app('App\\Http\\Controllers\\Api\\ExcelController')->generarResumenFinalTres($resumen1, $resumen2, $resumen3, $usuarios);

    // Generar nombre de archivo con fecha y hora
    $nombreArchivo = 'Informe_Gestiones_' . date('Ymd_His') . '.xlsx';

    // Guardar el informe en el disco 'informes' (ver config/filesystems.php)
    Excel::store(new DatosExport($filas, $encabezados), $nombreArchivo, 'informes');
    $this->info('Informe generado y guardado en disco "informes" con nombre: ' . $nombreArchivo);
    return 0;
    }

    private function getLatestFile($dir)
    {
        $files = File::files($dir);
        if (empty($files)) return null;
        usort($files, function($a, $b) {
            return $b->getMTime() <=> $a->getMTime();
        });
        return $files[0]->getPathname();
    }
}
