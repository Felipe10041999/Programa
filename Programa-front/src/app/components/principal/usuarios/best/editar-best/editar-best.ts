import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms'
import { ActivatedRoute, Router} from '@angular/router'
import { BestService } from '../../../../../services/best-service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-editar-best',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './editar-best.html',
  styleUrl: './editar-best.css'
})
export class EditarBest  implements OnInit{
  
  editarForms: FormGroup;
  id: number = 0;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private servicio: BestService,
    private router: Router
  ){
    this.editarForms = this.fb.group({
      nombre_usuario:['', Validators.required],
      extension:['', Validators.required],
      usuario:['', Validators.required],
      clave:['', Validators.required]
    })
  }

  ngOnInit(): void {
    this.id = Number(this.route.snapshot.paramMap.get('id'));
    console.log('User ID recibido', this.id);
    this.cargarUsuario();
  }

  cargarUsuario(){
    this.servicio.obtenerUsuarioId(this.id).subscribe({
      next: best =>{
        console.log('Usuario recibido', best)
        this.editarForms.patchValue(best);
      },
      error: (err) =>console.error('Error al cargar el usuario', err)
    });
  }

  onSubmit(){
    if(this.editarForms.valid){
      console.log('Datos enviados:', this.editarForms.value);
      this.servicio.editarBest(this.id, this.editarForms.value).subscribe({
        next: res =>{
          alert ('Usuario actualizado correctamente');
          this.router.navigate(['/best'],{replaceUrl:true})
        },
        error: err =>{
          console.error('Error completo', err);
          const errorBackend = err.error?.error || err.error?.mensaje;
          console.error('Mensaje desde el Backend:', errorBackend);
          alert ('Error al actualizar:' + (errorBackend || err.mensaje));
        }
      });
    }
  }

  cancelarEdicion(){
    this.router.navigate(['/best'])
  }

}
