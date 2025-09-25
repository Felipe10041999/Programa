import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NuevosService } from '../../../services/nuevos-service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-nuevos',
  templateUrl: './nuevos.html',
  styleUrls: ['./nuevos.css'],
  standalone: true,
  imports: [CommonModule, FormsModule]
})
export class Nuevos implements OnInit {
  archivoNuevos: File | null = null;
  archivoGestiones: File | null = null;
  generandoInforme = false;
  mensaje: string = '';
  mensajeExito = false;
  mensajeError = false;

  constructor(private router: Router, private nuevosService: NuevosService) {}

  onFileSelectedNuevos(event: any) {
    this.archivoNuevos = event.target.files[0] || null;
  }
  ngOnInit(): void {
  }

  onFileSelectedGestiones(event: any) {
    this.archivoGestiones = event.target.files[0] || null;
  }

  generarInforme() {
    if (!this.archivoNuevos || !this.archivoGestiones) return;
    this.generandoInforme = true;
    this.mensaje = '';
    this.mensajeExito = false;
    this.mensajeError = false;
    this.nuevosService.subirArchivos(this.archivoNuevos, this.archivoGestiones)
      .subscribe({
        next: blob => {
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = 'reporte_solicitudes.xlsx';
          a.click();
          window.URL.revokeObjectURL(url);
          this.generandoInforme = false;
          this.mensaje = 'Informe generado correctamente.';
          this.mensajeExito = true;
        },
        error: () => {
          this.generandoInforme = false;
          this.mensaje = 'Error generando el informe';
          this.mensajeError = true;
        }
      });
  }

  volverAPrincipal() {
    this.router.navigate(['']);
  }
}
