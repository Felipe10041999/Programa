import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { FormsModule, FormBuilder, FormGroup, ReactiveFormsModule,Validators } from '@angular/forms';
import { HuellaModel } from '../../../../../modelos/huellaModel';
import { HuellaService } from '../../../../../services/huellaService';

@Component({
  selector: 'app-registrar-huella',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, CommonModule],
  templateUrl: './registrar-huella.html',
  styleUrl: './registrar-huella.css'
})
export class RegistrarHuella {
  registroForm: FormGroup;
  huellas: HuellaModel[]= [];

  constructor(
    private fb: FormBuilder,
    private servicio: HuellaService,
    private router: Router,
  ){
    this.registroForm =  this.fb.group({
      usuario: ['', Validators.required],
      clave: ['', Validators.required],
      nombre_usuario: ['', Validators.required]
    });
  }
  
  onSubmit(){
    if(this.registroForm.invalid){
      this.registroForm.markAllAsTouched();
      return;
    }
    const huella={... this.registroForm.value};

    this.servicio.registrarHuella(huella).subscribe({
      next:(res) =>{
        console.log('Usuario registrado con exito:', res);
        alert('Registro exitoso');
        this.router.navigate(['/huella'])
      }
    })
  }
  
  cancelarRegistro(){
    this.router.navigate(['/huella'])
  }
}
