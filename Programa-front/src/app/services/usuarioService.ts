import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { UsuarioModel } from '../modelos/usuariosmodel';

@Injectable({
  providedIn: 'root'
})
export class UsuarioService {

  private baseUrl = 'http://192.168.112.18:8000';
  private apiUrl =`${this.baseUrl}/api/usuarios`;
  constructor(private http:HttpClient){}

  listaUsuarios():Observable<UsuarioModel[]>{
    return this.http.get<UsuarioModel[]>(this.apiUrl)
  }

  registrarUsuario(usuario: UsuarioModel){
    return this.http.post<any>(this.apiUrl, usuario)
  }

  editarUsuario(id:number, usuario :UsuarioModel){
    return this.http.put<any>(`${this.apiUrl}/${id}`, usuario)
  }

  obtenerUsuarioId(id: number): Observable<UsuarioModel>{
    return this.http.get<{usuario: UsuarioModel}>(`${this.apiUrl}/${id}`)
    .pipe(map(res=>res.usuario))
  }
  
  eliminarUsuario(id:number): Observable<any>{
    const url = `${this.apiUrl}/${id}`;
    return this.http.delete(url)
  }
  actualizarUsuarioPorCedula(cedula: string, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/cedula/${cedula}`, data);
  }
}
