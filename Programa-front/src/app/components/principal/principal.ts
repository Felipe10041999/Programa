import { Component, OnInit, OnDestroy } from '@angular/core';
import { RouterModule, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService, Sesion } from '../../services/auth.service';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-principal',
  standalone: true,
  imports: [RouterModule, CommonModule],
  templateUrl: './principal.html',
  styleUrl: './principal.css'
})
export class Principal implements OnInit, OnDestroy {
  usuarioActual: string | null = null;
  private authSubscription: Subscription = new Subscription();

  constructor(
    private router: Router,
    private authService: AuthService
  ) {}

  ngOnInit() {
    this.authSubscription.add(
      this.authService.sesion$.subscribe(sesion => {
        if (sesion) {
          this.usuarioActual = sesion.nombre_usuario;
        } else {
          this.usuarioActual = null;
        }
      })
    );

    this.usuarioActual = this.authService.getCurrentUser();
  }

  ngOnDestroy() {
    this.authSubscription.unsubscribe();
  }

  navegarAUsuarios() {
    this.router.navigate(['/usuarios']);
  }

  navegarAInformes() {
    this.router.navigate(['/informes']);
  }

  navegarALogueo() {
    this.router.navigate(['/logueo']);
  }

  async cerrarSesion() {
    await this.authService.logout();
  }
}
