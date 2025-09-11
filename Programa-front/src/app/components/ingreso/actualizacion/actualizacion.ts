import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { RouterModule, ActivatedRoute, Router } from '@angular/router';
import { UsuariosService } from '../../../services/usuarios-service';

@Component({
  selector: 'app-actualizacion',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './actualizacion.html',
  styleUrls: ['./actualizacion.css']
})
export class Actualizacion implements OnInit {
  editarForm!: FormGroup;
  usuarioEncontrado: boolean = false;
  errorMessage: string = '';
  successMessage: string = '';

  constructor(
    private fb: FormBuilder,
    private usuariosService: UsuariosService,
    private router: Router,
    private route: ActivatedRoute  // inyecta ActivatedRoute
  ) {}

  ngOnInit() {
    // inicializa el formulario
    this.editarForm = this.fb.group({
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      cedula: [{ value: '', disabled: true }, [Validators.required, Validators.pattern(/^\d+$/)]],
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
      usuario_bestvoiper: ['', Validators.required]
    });

    // leer parámetro de ruta
    const idParam = this.route.snapshot.paramMap.get('id');
    console.log('ID recibido por la ruta:', idParam);

    if (idParam) {
      this.cargarUsuarioPorId(Number(idParam));
    } else {
      console.warn('No se encontró parámetro id en la ruta');
    }
  }

  cargarUsuarioPorId(id: number) {
    this.usuarioEncontrado = false;
    this.errorMessage = '';
    this.successMessage = '';

    this.usuariosService.obtenerUsuarioPorId(id).subscribe({
      next: usuario => {
        console.log('Usuario obtenido:', usuario);
        this.usuarioEncontrado = true;
        this.editarForm.patchValue(usuario);
      },
      error: err => {
        console.error('Error al obtener usuario:', err);
        this.errorMessage = err.error?.mensaje || 'Error al cargar datos del usuario';
      }
    });
  }

  onSubmit() {
    if (this.editarForm.invalid) {
      this.editarForm.markAllAsTouched();
      return;
    }

    const confirmacion = confirm('¿Estás seguro de que deseas guardar los cambios?');
    if (!confirmacion) return;

    const formData = this.editarForm.getRawValue(); // incluyendo cedula deshabilitada
    const cedula = formData.cedula;

    this.usuariosService.actualizarUsuarioPorCedula(cedula, formData).subscribe({
      next: () => {
        this.successMessage = 'Usuario actualizado con éxito';
        this.router.navigate(['/ingreso']);
      },
      error: err => {
        console.error('Error en actualización:', err);
        const backendError = err.error?.error || err.error?.mensaje;
        this.errorMessage = 'Error al actualizar: ' + (backendError || err.message);
      }
    });
  }

  cancelar() {
    this.router.navigate(['/ingreso']);
  }
}
