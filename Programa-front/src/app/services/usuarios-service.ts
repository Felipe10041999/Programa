import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Usuariosmodel } from '../modelos/usuariosmodel';
import { map } from 'rxjs/operators';


@Injectable({
  providedIn: 'root'
})
export class UsuariosService {

  private apiUrl = 'http://192.168.112.18:8000/api/usuarios'; 
  constructor(private http: HttpClient) { }
  getUsuarios(): Observable<Usuariosmodel[]> {
    return this.http.get<{data: Usuariosmodel[]}>(this.apiUrl)
      .pipe(map(response => response.data));
  }
  eliminarUsuario(id: number): Observable<any> {
  const url = `${this.apiUrl}/${id}`;
  return this.http.delete(url);
  }
  registrarUsuario(usuario: any) {
  return this.http.post<any>(this.apiUrl, usuario);
  }
  actualizarUsuario(id: number, datos: any) {
    console.log('Datos enviados al backend:', datos);
  return this.http.put<any>(`${this.apiUrl}/${id}`, datos);
  }
  obtenerUsuarioPorId(id: number): Observable<Usuariosmodel> {
  return this.http.get<{ usuario: Usuariosmodel }>(`${this.apiUrl}/${id}`)
    .pipe(map(res => res.usuario));
  }
  obtenerUsuarioActual() {
  const userJson = localStorage.getItem('usuario');
  return userJson ? JSON.parse(userJson) : null;
  }
  getUsuario(cartera?: string) {
    let url = this.apiUrl + '/usuarios';
    if (cartera) {
      url += `?cartera=${encodeURIComponent(cartera)}`;
    }
    return this.http.get<any>(url);
  }
  obtenerUsuarioPorCedula(cedula: string): Observable<Usuariosmodel> {
    return this.http.get<{ usuario: Usuariosmodel }>(`${this.apiUrl}/cedula/${cedula}`)
      .pipe(map(res => res.usuario));
  }
  actualizarUsuarioPorCedula(cedula: string, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/cedula/${cedula}`, data);
  }
}
