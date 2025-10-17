import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { EquipoModel } from '../modelos/equipoModel.model';

@Injectable({
  providedIn: 'root'
})
export class EquipoService {
  
  private baseUrl = 'http://192.168.112.18:8000';
  private apiUrl = `${this.baseUrl}/api/equipos`;
  constructor(private http:HttpClient){}

  obtenerEquipos():Observable<EquipoModel[]>{
    return this.http.get<EquipoModel[]>(this.apiUrl);
  }

  registrarEquipos(equipo: EquipoModel){
    return this.http.post<any>(this.apiUrl, equipo);
  }

  editarEquipos(id: number, equipo: EquipoModel){
    return this.http.put<any>(`${this.apiUrl}/${id}`, equipo)
  }

  obtenerUsuarioId(id: number):Observable<EquipoModel>{
    return this.http.get<{usuario :EquipoModel}>(`${this.apiUrl}/${id}`)
    .pipe(map(res=>res.usuario))
  }

  eliminarUsuario(id:number): Observable<any>{
    const url = `${this.apiUrl}/${id}`;
    return this.http.delete(url);
  }
  
  verificarAsignacionEquipo(id:number):Observable<{asignado: boolean}> {
  return this.http.get<{asignado: boolean}>(`${this.baseUrl}/api/equipo/${id}`);
  }

}
