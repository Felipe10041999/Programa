import { Injectable } from '@angular/core';
import { CanActivate, Router, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class NoAuthGuard implements CanActivate {
  
  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  canActivate(): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    // Si el usuario ya está autenticado, redirigir al panel principal
    if (this.authService.isAuthenticated() && !this.authService.isSessionExpired()) {
      return this.router.createUrlTree(['/principal']);
    }
    
    // Usuario no autenticado, permitir acceso al login
    return true;
  }
} 