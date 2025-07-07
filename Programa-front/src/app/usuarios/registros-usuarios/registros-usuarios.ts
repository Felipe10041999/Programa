import { Component } from '@angular/core';
import { FormsModule, FormBuilder,FormGroup, ReactiveFormsModule,Validators  } from '@angular/forms'; 
import { Router } from '@angular/router';
import { UsuariosService } from '../../services/usuarios-service';
import { CommonModule } from '@angular/common';
import { Usuariosmodel } from '../../modelos/usuariosmodel';

@Component({
  selector: 'app-registros-usuarios',
  standalone:true,
  imports: [FormsModule,ReactiveFormsModule,CommonModule],
  templateUrl: './registros-usuarios.html',
  styleUrl: './registros-usuarios.css'
})
export class RegistrosUsuarios {
  registroForm: FormGroup;

  constructor(
    private fb: FormBuilder,
    private usuariosService: UsuariosService,
    private router: Router
  ) {
    this.registroForm = this.fb.group({
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      cedula: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      telefono: ['', Validators.required],
      cartera: ['', Validators.required],
      numero_equipo: ['', Validators.required],
      usuario_equipo: ['', Validators.required],
      clave_equipo: ['', Validators.required],
      usuario_huella: ['', Validators.required],
      clave_huella: ['', Validators.required],
      correo: ['', [Validators.required, Validators.email]],
    });
  }

  onSubmit() {
    if (this.registroForm.invalid) {
      this.registroForm.markAllAsTouched();
      return;
    }

    const nuevoUsuario = this.registroForm.value;

    this.usuariosService.registrarUsuario(nuevoUsuario).subscribe({
      next: (res) => {
        console.log('Usuario registrado con éxito:', res);
        alert('Registro exitoso');
        this.router.navigate(['']);
      },
      error: (err) => {
        console.error('Error al registrar usuario:', err);
        alert('Error en el registro');
      }
    });
  }
}
