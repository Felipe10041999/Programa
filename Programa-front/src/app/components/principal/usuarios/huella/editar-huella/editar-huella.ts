import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms'
import { ActivatedRoute, Router} from '@angular/router'
import { HuellaService } from '../../../../../services/huellaService';
import { CommonModule } from '@angular/common'


@Component({
  selector: 'app-editar-huella',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './editar-huella.html',
  styleUrl: './editar-huella.css'
})
export class EditarHuella implements OnInit{
  
  editarForms: FormGroup;
  id: number = 0;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private servicio: HuellaService,
    private router: Router
  ){
    this.editarForms = this.fb.group({
      usuario: ['', Validators.required],
      clave: ['', Validators.required],
      nombre_usuario:['', Validators.required]
    })
  }

  ngOnInit(): void {
    this.id = Number(this.route.snapshot.paramMap.get('id'));
    console.log('User ID recibido', this.id);
    this.cargarUsuario();
  }

  cargarUsuario(){
   this.servicio.obtenerUsuarioId(this.id).subscribe({
    next: huella =>{
      console.log('Usuario recibido:', huella)
      this.editarForms.patchValue(huella);
    },
    error: (err) => console.error('Error al cargar el usuario', err)
   });
  }

  onSubmit(){
    if(this.editarForms.valid){
      console.log('Datos enviados:', this.editarForms.value);
      this.servicio.editarHuella(this.id, this.editarForms.value).subscribe({
        next: res =>{
          alert ('Usuario actualizado correctamente');
          this.router.navigate(['/huella'],{replaceUrl:true})
        },
        error: err =>{
          console.error('Error completo', err);
          const errorBackend = err.error?.error || err.error?.mensaje;
          console.error('Mensaje desde el Backend:', errorBackend);
          alert('Error al actualizar:' + (errorBackend || err.mensaje));
        }
      });
    }
  }
  
  cancelarEdicion(){
    this.router.navigate(['/huella'])
  }



}
