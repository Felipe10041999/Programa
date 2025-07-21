import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { InformesService } from '../../../services/excel-productividad';

@Component({
  selector: 'app-informes',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './informes.html',
  styleUrls: ['./informes.css']
})
export class Informes implements OnInit {
  generandoInforme = false;
  archivoSeleccionado: File | null = null;
  archivoGrabacionesSeleccionado: File | null = null;
  horaLimite: number = 18; // Valor por defecto
  horasDisponibles: number[] = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
  carteraSeleccionada: string = ''; // Valor por defecto (todas las carteras)
  
  constructor(
    private router: Router,
    private informesService: InformesService
  ) {}
  ngOnInit(): void {
    // Inicialización del componente
  }
  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      // Validar que sea un archivo Excel
      if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || 
          file.type === 'application/vnd.ms-excel') {
        this.archivoSeleccionado = file;
        console.log('Archivo seleccionado:', file.name);
      } else {
        alert('Por favor seleccione un archivo Excel (.xlsx o .xls)');
        event.target.value = '';
      }
    }
  }
  onFileSelected2(event: any) {
    const file = event.target.files[0];
    if (file) {
      if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || 
          file.type === 'application/vnd.ms-excel') {
        this.archivoGrabacionesSeleccionado = file;
        console.log('Archivo de grabaciones seleccionado:', file.name);
      } else {
        alert('Por favor seleccione un archivo Excel (.xlsx o .xls)');
        event.target.value = '';
      }
    }
  }
  generarInformeProductividad() {
    if (!this.archivoSeleccionado || !this.archivoGrabacionesSeleccionado) {
      alert('Por favor seleccione ambos archivos Excel primero');
      return;
    }

    this.generandoInforme = true;
    
    // Crear FormData para enviar ambos archivos
    const formData = new FormData();
    formData.append('file', this.archivoSeleccionado);
    formData.append('file2', this.archivoGrabacionesSeleccionado);
    formData.append('hora_limite', this.horaLimite.toString());
    if (this.carteraSeleccionada) {
      formData.append('cartera', this.carteraSeleccionada);
    }
    
    this.informesService.generarInformeProductividad(formData).subscribe({
      next: (blob) => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'resumen_gestiones.xlsx';
        a.click();
        window.URL.revokeObjectURL(url);
        this.generandoInforme = false;
      },
      error: (error) => {
        console.error('Error al generar informe:', error);
        alert('Error al generar el informe');
        this.generandoInforme = false;
      }
    });
  }
  descargarArchivo(archivo: any) {
    // Lógica para descargar el archivo si es necesario
    const link = document.createElement('a');
    link.href = archivo.url;
    link.download = archivo.nombre;
    link.click();
  }
  volverAPrincipal() {
    this.router.navigate(['']);
  }
}