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
    if (this.authService.isAuthenticated()) {
      if (this.authService.isSessionExpired()) {
        this.authService.logout();
        return this.router.createUrlTree(['/login']);
      }
      
      this.authService.renovarSesion();
      return true;
    }
    
    return this.router.createUrlTree(['/login']);
  }
} 