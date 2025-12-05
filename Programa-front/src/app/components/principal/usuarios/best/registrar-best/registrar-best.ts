import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { FormsModule, FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { BestModelModel } from '../../../../../modelos/bestModel.model';
import { BestService } from '../../../../../services/best-service';

@Component({
  selector: 'app-registrar-best',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, CommonModule],
  templateUrl: './registrar-best.html',
  styleUrl: './registrar-best.css'
})
export class RegistrarBest {
  registroForm: FormGroup;
  bests:BestModelModel[]= [];

  constructor(
    private fb: FormBuilder,
    private servicio: BestService,
    private router: Router,
  ){
    this.registroForm = this.fb.group({
      nombre_usuario: ['', Validators.required],
      extension: ['', Validators.required],
      usuario: ['', Validators.required],
      clave: ['', Validators.required],
    });
  }
  
  onSubmit(){
    if(this.registroForm.invalid){
      this.registroForm.markAllAsTouched();
      return;
    }
    const best={... this.registroForm.value};

    this.servicio.registrarBest(best).subscribe({
      next:(res) =>{
        console.log('Usuario registrado con exito:', res);
        alert('Registro exitoso');
        this.router.navigate(['best'])
      }
    })
  }

  cancelarRegistro(){
    this.router.navigate(['best'])
  }
}
