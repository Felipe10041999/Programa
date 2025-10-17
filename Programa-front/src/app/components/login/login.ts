import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService, Sesion } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.html',
  styleUrl: './login.css'
})
export class Login {
  nombreUsuario: string = '';
  contrasena: string = '';
  isLoading: boolean = false;
  errorMessage: string = '';
  successMessage: string = '';

  constructor(
    private router: Router,
    private authService: AuthService
  ) {}

  async iniciarSesion() {
  this.errorMessage = '';
  this.successMessage = '';

    if (!this.nombreUsuario.trim() || !this.contrasena.trim()) {
      this.errorMessage = 'Por favor ingrese su nombre de usuario y contraseña';
      this.isLoading = false;
      return;
    }

    this.isLoading = true;
    try {
      const response: any = await this.authService.http.post('http://localhost:8000/api/login', {
        nombre_usuario: this.nombreUsuario.trim(),
        contrasena: this.contrasena
      }).toPromise();

      if (response.status === 200) {
        localStorage.setItem('sesion', JSON.stringify(response.sesion));
        this.authService.updateAuthStatus(true, response.sesion);
        this.successMessage = 'Inicio de sesión exitoso';
        setTimeout(() => {
          this.isLoading = false;
          this.router.navigate(['/principal']);
        }, 1000);
        return;
      } else {
        this.errorMessage = response.mensaje || 'Error en el inicio de sesión';
        this.isLoading = false;
      }
    } catch (error: any) {
      if (error.status === 401) {
        this.errorMessage = error.error?.mensaje || 'Usuario o contraseña incorrectos';
      } else if (error.status === 403) {
        this.errorMessage = error.error?.mensaje || 'Cuenta deshabilitada';
      } else if (error.status === 422) {
        try {
          const errores = error.error?.errores;
          if (errores) {
            const mensajes = Object.values(errores).flat();
            this.errorMessage = mensajes.length ? String(mensajes[0]) : 'Datos de validación incorrectos';
          } else {
            this.errorMessage = error.error?.mensaje || 'Datos de validación incorrectos';
          }
        } catch (e) {
          this.errorMessage = 'Datos de validación incorrectos';
        }
      } else if (error.status === 500) {
        this.errorMessage = 'Error en el servidor. Intente nuevamente.';
      } else {
        this.errorMessage = error.message || 'Error de conexión. Verifique su conexión a internet.';
      }
      this.isLoading = false;
    }
  }

  limpiarMensajes() { 
    this.errorMessage = '';
    this.successMessage = '';
  }
}                    