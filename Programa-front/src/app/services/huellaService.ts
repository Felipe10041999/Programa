import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { HuellaModel } from '../modelos/huellaModel';

@Injectable({
  providedIn: 'root'
})
export class HuellaService {
  
  private baseUrl = 'http://192.168.112.18:8000';
  private apiUrl = `${this.baseUrl}/api/huella`;
  constructor(private http:HttpClient){}

  listaHuella():Observable<HuellaModel[]>{
    return this.http.get<HuellaModel[]>(this.apiUrl);
  }

  registrarHuella(huella: HuellaModel){
    return this.http.post<any>(this.apiUrl, huella)
  }

  editarHuella(id: number, huella: HuellaModel){
    return this.http.put<any>(`${this.apiUrl}/${id}`, huella)
  }

  obtenerUsuarioId(id: number): Observable<HuellaModel>{
    return this.http.get<{usuario: HuellaModel}>(`${this.apiUrl}/${id}`)
    .pipe(map(res=>res.usuario))
  }

  eliminarHuella(id:number): Observable<any>{
    const url = `${this.apiUrl}/${id}`;
    return this.http.delete(url);
  }

  verificarAsignacionHuella(id:number):Observable<{asignado: boolean}> {
  return this.http.get<{asignado: boolean}>(`${this.baseUrl}/api/huellas/${id}`);
  }
}
