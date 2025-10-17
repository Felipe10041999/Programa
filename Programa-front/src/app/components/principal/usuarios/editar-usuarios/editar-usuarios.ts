import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { UsuariosService } from '../../../../services/usuarios-service';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-editar-usuarios',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './editar-usuarios.html',
  styleUrls: ['./editar-usuarios.css']
})
export class EditarUsuarios implements OnInit {
  editarForm: FormGroup;
  userId: number = 0;
  
  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private usuariosService: UsuariosService,
    private router: Router
  ) {
    this.editarForm = this.fb.group({
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
  ngOnInit() {
    this.userId = Number(this.route.snapshot.paramMap.get('id'));
    console.log('User ID recibido:', this.userId);
    this.cargarUsuario();
  }
  cargarUsuario(){
    this.usuariosService.obtenerUsuarioPorId(this.userId).subscribe({
      next: usuario => {
        console.log('Usuario recibido:', usuario);
        this.editarForm.patchValue(usuario);
      },
      error: (err) => console.error('Error al cargar el usuario', err)
    });
  }
  onSubmit() {
    if (this.editarForm.valid) {
      console.log('Datos enviados:', this.editarForm.value);
      this.usuariosService.actualizarUsuario(this.userId, this.editarForm.value).subscribe({
        next: res => {
          alert('Usuario actualizado con éxito');
          this.router.navigate(['/usuarios'], { replaceUrl: true });
        },
        error: err => {
          console.error('Error completo:', err);
          const backendError = err.error?.error || err.error?.mensaje;
          console.error('→ Mensaje desde backend:', backendError);
          alert('Error al actualizar: ' + (backendError || err.message));
        }
      });
    }
  }
  cancelarEdicion() {
    this.router.navigate(['/usuarios']);
  }
}