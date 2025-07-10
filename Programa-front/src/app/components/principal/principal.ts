import { Component } from '@angular/core';
import { RouterModule, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { UsuariosService } from '../../services/usuarios-service';

@Component({
  selector: 'app-principal',
  imports: [RouterModule, CommonModule],
  templateUrl: './principal.html',
  styleUrl: './principal.css'
})
export class Principal {

  constructor(private router: Router) {}

  navegarAUsuarios() {
    this.router.navigate(['/usuarios']);
  }
  navegarAInformes() {
    this.router.navigate(['/informes']);
  }
}
