import { Component, OnInit } from '@angular/core';
import { FormsModule, FormBuilder, FormGroup, ReactiveFormsModule, Validators, AbstractControl, ValidationErrors } from '@angular/forms'; 
import { Router } from '@angular/router';
import { UsuariosService } from '../../../../services/usuarios-service';
import { CommonModule } from '@angular/common';
import { Usuariosmodel } from '../../../../modelos/usuariosmodel';

@Component({
  selector: 'app-registrar-usuarios',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, CommonModule],
  templateUrl: './registrar-usuarios.html',
  styleUrl: './registrar-usuarios.css'
})
export class RegistrarUsuarios implements OnInit {
  registroForm: FormGroup;
  usuariosExistentes: Usuariosmodel[] = [];

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
      nombre_usuario_huella: ['', Validators.required],
      clave_huella: ['', Validators.required],
      correo: ['', [Validators.required, Validators.email]],
      extension: ['', [Validators.required, Validators.minLength(2)]],
      usuario_bestvoiper: ['', Validators.required],
    });
  }
  ngOnInit(): void {
    // Cargar usuarios existentes para validación
    this.cargarUsuariosExistentes();
    
    // Agregar validador personalizado para cédula
    this.registroForm.get('cedula')?.setValidators([
      Validators.required, 
      Validators.pattern(/^\d+$/),
      this.validarCedulaDuplicada.bind(this)
    ]);
  }
  cargarUsuariosExistentes(): void {
    this.usuariosService.getUsuarios().subscribe({
      next: (data) => {
        this.usuariosExistentes = data;
      },
      error: (error) => {
        console.error('Error al cargar usuarios existentes:', error);
      }
    });
  }
  validarCedulaDuplicada(control: AbstractControl): ValidationErrors | null {
    if (!control.value) {
      return null;
    }

    const cedulaIngresada = control.value.toString();
    const cedulaExiste = this.usuariosExistentes.some(
      usuario => usuario.cedula.toString() === cedulaIngresada
    );

    return cedulaExiste ? { cedulaDuplicada: true } : null;
  }
  onSubmit() {
    if (this.registroForm.invalid) {
      this.registroForm.markAllAsTouched();
      return;
    }

    // Copia el objeto y transforma el campo usuarioBestVoIper a usuario_bestvoiper
    const nuevoUsuario = { ...this.registroForm.value };
    nuevoUsuario.usuario_bestvoiper = nuevoUsuario.usuarioBestVoIper;
    delete nuevoUsuario.usuarioBestVoIper;

    this.usuariosService.registrarUsuario(nuevoUsuario).subscribe({
      next: (res) => {
        console.log('Usuario registrado con éxito:', res);
        alert('Registro exitoso');
        this.router.navigate(['/usuarios']);
      },
      error: (err) => {
        console.error('Error al registrar usuario:', err);
        alert('Error en el registro');
      }
    });
  }
  getCedulaError(): string {
    const cedulaControl = this.registroForm.get('cedula');
    if (cedulaControl?.errors) {
      if (cedulaControl.errors['required']) {
        return 'La cédula es requerida';
      }
      if (cedulaControl.errors['pattern']) {
        return 'La cédula debe contener solo números';
      }
      if (cedulaControl.errors['cedulaDuplicada']) {
        return 'Esta cédula ya está registrada';
      }
    }
    return '';
  }
  cancelarRegistro() {
    this.router.navigate(['/usuarios']);
  }
}
