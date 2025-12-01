import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { JuridicoService } from '../../../services/juridico-service';

@Component({
  selector: 'app-juridico',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './juridico.html',
  styleUrl: './juridico.css'
})
export class Juridico {
  
  archivoSeleccionado: File | null = null;
  cargando = false;
  mensaje = '';
  progreso = 0;
  dragover = false;

  constructor(
    private juridicoService: JuridicoService,
    private router: Router
  ) {}

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      if (this.juridicoService.validarArchivoExcel(file)) {
        this.archivoSeleccionado = file;
        this.mensaje = `Archivo seleccionado: ${file.name} (${this.juridicoService.formatearTamañoArchivo(file.size)})`;
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

    this.juridicoService.subirArchivo(this.archivoSeleccionado).subscribe({
      next: (event: any) => {
        const progresoInfo = this.juridicoService.procesarProgreso(event);
        if (progresoInfo) {
          this.progreso = progresoInfo.percentage;
        }

        if (this.juridicoService.esRespuestaExitosa(event)) {
          const respuesta = this.juridicoService.obtenerRespuesta(event);
          if (respuesta) {
            this.juridicoService.descargarArchivo(respuesta, 'Archivo gestion juridico.xlsx');
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
      if (this.juridicoService.validarArchivoExcel(file)) {
        this.archivoSeleccionado = file;
        this.mensaje = `Archivo seleccionado: ${file.name} (${this.juridicoService.formatearTamañoArchivo(file.size)})`;
      } else {
        this.mensaje = 'Error: Solo se permiten archivos Excel (.xlsx, .xls)';
      }
    }
  }
}
