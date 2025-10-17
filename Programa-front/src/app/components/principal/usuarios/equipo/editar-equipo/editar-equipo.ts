import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router} from '@angular/router'
import { EquipoService } from '../../../../../services/equipoService';
import { CommonModule } from '@angular/common';



@Component({
  selector: 'app-editar-equipo',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './editar-equipo.html',
  styleUrl: './editar-equipo.css'
})
export class EditarEquipo implements OnInit{
  editarForms: FormGroup;
  id: number = 0;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private servicio: EquipoService,
    private router : Router
  ){
    this.editarForms = this.fb.group({
      usuario: ['',Validators.required],
      clave: ['',Validators.required]
    })
  }

  ngOnInit(){
    this.id = Number(this.route.snapshot.paramMap.get('id'));
    console.log('User ID recibido:', this.id);
    this.cargarUsuario();
  }

  cargarUsuario(){
    this.servicio.obtenerUsuarioId(this.id).subscribe({
      next: equipo =>{
        console.log('usuario recibido:', equipo);
        this.editarForms.patchValue(equipo);
      },
      error: (err) => console.error('Error al cargar el usuario', err)
    });
  }

  onSubmit(){
    if (this.editarForms.valid){
      console.log('Datos enviados:',this.editarForms.value);
      this.servicio.editarEquipos(this.id, this.editarForms.value).subscribe({
        next: res =>{
          alert('Usuario actualizado correctamente');
          this.router.navigate(['/equipo'],{replaceUrl: true})
        },
        error: err =>{
          console.error('Error completo', err);
          const errorBackend = err.error?.error || err.error?.mensaje;
          console.error('Mensaje desde backend:', errorBackend);
          alert('Error al actualizar: ' +(errorBackend || err.message));
        }
      });
    }
  }
  
  cancelarEdicion(){
    this.router.navigate(['/equipo'])
  }
}
