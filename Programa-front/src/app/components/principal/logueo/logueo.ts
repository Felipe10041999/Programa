import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { LogueoService, UploadProgress } from '../../../services/logueo.service';

@Component({
  selector: 'app-logueo',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './logueo.html',
  styleUrl: './logueo.css'
})
export class Logueo {
  archivoSeleccionado: File | null = null;
  cargando = false;
  mensaje = '';
  progreso = 0;
  dragover = false;

  constructor(
    private logueoService: LogueoService,
    private router: Router
  ) {}

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      // Validar que sea un archivo Excel usando el servicio
      if (this.logueoService.validarArchivoExcel(file)) {
        this.archivoSeleccionado = file;
        this.mensaje = `Archivo seleccionado: ${file.name} (${this.logueoService.formatearTamañoArchivo(file.size)})`;
      } else {
        this.mensaje = 'Error: Solo se permiten archivos Excel (.xlsx, .xls)';
        this.archivoSeleccionado = null;
      }
    }
  }

  subirArchivo() {
    if (!this.archivoSeleccionado) {
      this.mensaje = 'Por favor selecciona un archivo';
      return;
    }

    this.cargando = true;
    this.progreso = 0;
    this.mensaje = 'Procesando archivo...';

    this.logueoService.subirArchivo(this.archivoSeleccionado).subscribe({
      next: (event: any) => {
        // Procesar progreso de carga
        const progresoInfo = this.logueoService.procesarProgreso(event);
        if (progresoInfo) {
          this.progreso = progresoInfo.percentage;
        }

        // Verificar si es respuesta exitosa
        if (this.logueoService.esRespuestaExitosa(event)) {
          const respuesta = this.logueoService.obtenerRespuesta(event);
          if (respuesta) {
            // Descargar el archivo procesado directamente
            this.logueoService.descargarArchivo(respuesta, 'archivo_dos_resultado.xlsx');
            this.mensaje = 'Archivo procesado exitosamente. Descarga iniciada.';
            this.cargando = false;
            this.progreso = 100;
          }
        }
      },
      error: (error: any) => {
        console.error('Error al subir archivo:', error);
        this.mensaje = 'Error al procesar el archivo. Intenta nuevamente.';
        this.cargando = false;
        this.progreso = 0;
      }
    });
  }

  volver() {
    this.router.navigate(['/principal']);
  }

  onDragOver(event: DragEvent) {
    event.preventDefault();
    event.stopPropagation();
    this.dragover = true;
  }

  onDragLeave(event: DragEvent) {
    event.preventDefault();
    event.stopPropagation();
    this.dragover = false;
  }

  onDrop(event: DragEvent) {
    event.preventDefault();
    event.stopPropagation();
    this.dragover = false;
    
    const files = event.dataTransfer?.files;
    if (files && files.length > 0) {
      const file = files[0];
      if (this.logueoService.validarArchivoExcel(file)) {
        this.archivoSeleccionado = file;
        this.mensaje = `Archivo seleccionado: ${file.name} (${this.logueoService.formatearTamañoArchivo(file.size)})`;
      } else {
        this.mensaje = 'Error: Solo se permiten archivos Excel (.xlsx, .xls)';
      }
    }
  }
}
