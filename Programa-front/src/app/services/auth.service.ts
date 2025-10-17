import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { Router } from '@angular/router';

export interface Sesion {
  id: number;
  nombre_usuario: string;
  token_sesion: string;
  ultimo_acceso: string;
  estado_sesion: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://192.168.112.18:8000/api';
  private isAuthenticatedSubject = new BehaviorSubject<boolean>(false);
  private sesionSubject = new BehaviorSubject<Sesion | null>(null);

  public isAuthenticated$ = this.isAuthenticatedSubject.asObservable();
  public sesion$ = this.sesionSubject.asObservable();

  constructor(
    public http: HttpClient,
    private router: Router
  ) {
    this.checkAuthStatus();
  }

  private checkAuthStatus(): void {
    const sesion = this.getSesion();
    if (sesion && sesion.token_sesion) {
      this.isAuthenticatedSubject.next(true);
      this.sesionSubject.next(sesion);
    } else {
      this.isAuthenticatedSubject.next(false);
      this.sesionSubject.next(null);
    }
  }

  /**
   * Obtener la sesión del localStorage
   */
  getSesion(): Sesion | null {
    const sesionStr = localStorage.getItem('sesion');
    return sesionStr ? JSON.parse(sesionStr) : null;
  }

  isAuthenticated(): boolean {
    return this.isAuthenticatedSubject.value;
  }

  /**
   * Obtener el token de sesión actual
   */
  getToken(): string | null {
    const sesion = this.getSesion();
    return sesion ? sesion.token_sesion : null;
  }

  /**
   * Obtener el nombre de usuario actual
   */
  getCurrentUser(): string | null {
    const sesion = this.getSesion();
    return sesion ? sesion.nombre_usuario : null;
  }

  /**
   * Verificar la autenticación con el servidor
   */
  verificarAutenticacion(): Observable<any> {
    const token = this.getToken();
    if (!token) {
      return new Observable(observer => {
        observer.error('No hay token de sesión');
      });
    }

    return this.http.get(`${this.apiUrl}/verificar`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
  }

  async logout(): Promise<void> {
    try {
      const token = this.getToken();
      if (token) {
        await this.http.post(`${this.apiUrl}/logout`, {
          token_sesion: token
        }).toPromise();
      }
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
    } finally {
      this.clearSession();
      this.router.navigate(['/login']);
    }
  }

  private clearSession(): void {
    localStorage.removeItem('sesion');
    localStorage.removeItem('usuario_autenticado');
    this.isAuthenticatedSubject.next(false);
    this.sesionSubject.next(null);
  }

  updateAuthStatus(isAuth: boolean, sesion?: Sesion): void {
    this.isAuthenticatedSubject.next(isAuth);
    if (sesion) {
      this.sesionSubject.next(sesion);
    }
  }

  isSessionExpired(): boolean {
    const sesion = this.getSesion();
    if (!sesion || !sesion.ultimo_acceso) {
      return true;
    }

    const ultimoAcceso = new Date(sesion.ultimo_acceso);
    const ahora = new Date();
    const diferenciaHoras = (ahora.getTime() - ultimoAcceso.getTime()) / (1000 * 60 * 60);

    // Considerar expirada después de 30 minutos de inactividad
    return diferenciaHoras > 0.5; // 30 minutos
  }


  renovarSesion(): void {
    const sesion = this.getSesion();
    if (sesion) {
      sesion.ultimo_acceso = new Date().toISOString();
      localStorage.setItem('sesion', JSON.stringify(sesion));
      this.sesionSubject.next(sesion);
    }
  }
} 