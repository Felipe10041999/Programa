import { Injectable } from '@angular/core';
import { CanActivate, Router, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {
  
  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  canActivate(): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    // Verificar si el usuario está autenticado
    if (this.authService.isAuthenticated()) {
      // Verificar si la sesión ha expirado
      if (this.authService.isSessionExpired()) {
        // Sesión expirada, cerrar sesión y redirigir al login
        this.authService.logout();
        return this.router.createUrlTree(['/login']);
      }
      
      // Renovar la sesión si es válida
      this.authService.renovarSesion();
      return true;
    }
    
    // Usuario no autenticado, redirigir al login
    return this.router.createUrlTree(['/login']);
  }
} 