import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { FormsModule, FormBuilder, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import { EquipoModel } from '../../../../../modelos/equipoModel.model';
import { EquipoService } from '../../../../../services/equipoService';

@Component({
  selector: 'app-registrar-equipo',
  standalone: true,
  imports: [FormsModule,ReactiveFormsModule,CommonModule],
  templateUrl: './registrar-equipo.html',
  styleUrl: './registrar-equipo.css'
})
export class RegistrarEquipo  {
  
  registroForm: FormGroup;
  equipos: EquipoModel[] = [];

  constructor(
    private fb: FormBuilder,
    private equipoService: EquipoService,
    private router : Router,
    ){
      this.registroForm = this.fb.group({
        usuario: ['',Validators.required],
        clave: ['',Validators.required],
      });
    }
    
    onSubmit(){
      if(this.registroForm.invalid){
        this.registroForm.markAllAsTouched();
        return;
      }
      const equipo ={... this.registroForm.value};
      this.equipoService.registrarEquipos(equipo).subscribe({
        next: (res) =>{
          console.log('Usuario registrado con exito: ', res);
          alert('Registro exitoso');
          this.router.navigate(['/equipo'])
        }
      })
    }

    cancelarRegistro(){
      this.router.navigate(['/equipo'])
    }
}
