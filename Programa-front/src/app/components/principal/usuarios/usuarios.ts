import { Component } from '@angular/core';
import { Router } from '@angular/router'

@Component({
  selector: 'app-usuarios',
  standalone: true,
  imports: [],
  templateUrl: './usuarios.html',
  styleUrl: './usuarios.css'
})
export class Usuarios {

  constructor(
    private router: Router,
  ){}

  irEquipos(){
    this.router.navigate(['/equipo'])
  }

  irHuella(){
    this.router.navigate(['/huella'])
  }
  
  irUsuarios(){
    this.router.navigate(['/usuario'])
  }
  irBest(){
    this.router.navigate(['/best'])
  }
  irPrincipal(){
    this.router.navigate(['']);
  }

}
