import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-ingreso',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './ingreso.html',
  styleUrl: './ingreso.css'
})
export class Ingreso {
  cedula: string = '';
  isLoading: boolean = false;
  mensaje: string = '';
  mensajeTipo: 'success' | 'error' | '' = '';

  constructor(
    private router: Router,
    private http: HttpClient
  ) {}

  async verificarCedula() {
    this.mensaje = '';
    this.mensajeTipo = '';

    if (!this.cedula.trim()) {
      this.mensaje = 'Por favor ingrese su número de cédula';
      this.mensajeTipo = 'error';
      return;
    }

    this.isLoading = true;
    try {
      const cedulaTrimmed = this.cedula.trim();
      const response: any = await this.http
        .get(`http://192.168.112.18:8000/api/usuarios/cedula/${cedulaTrimmed}`)
        .toPromise();

      if (response && response.usuario) {
        this.router.navigate(['/actualizacion', response.usuario.id]);
      } else {
        this.mensaje = 'Cédula no encontrada en el sistema.';
        this.mensajeTipo = 'error';
      }
    } catch (error: any) {
      this.mensaje = error.error?.mensaje || 'Error al verificar la cédula.';
      this.mensajeTipo = 'error';
    } finally {
      this.isLoading = false;
    }
  }

  irALogin() {
    this.router.navigate(['/login']);
  }
}
