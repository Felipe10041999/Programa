import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { BestModelModel } from '../modelos/bestModel.model';

@Injectable({
  providedIn: 'root'
})
export class BestService {
  
  private baseUrl = 'http://192.168.112.18:8000';
  private apiUrl = `${this.baseUrl}/api/best`;
  constructor(private http:HttpClient){}

  listaBest():Observable<BestModelModel[]>{
    return this.http.get<BestModelModel[]>(this.apiUrl);
  }

  registrarBest(best: BestModelModel){
    return this.http.post<any>(this.apiUrl, best)
  }

  editarBest(id: number, best: BestModelModel){
    return this.http.put<any>(`${this.apiUrl}/${id}`, best)
  }

  obtenerUsuarioId(id:number):Observable<BestModelModel>{
    return this.http.get<{usuario: BestModelModel}>(`${this.apiUrl}/${id}`)
    .pipe(map(res=>res.usuario))
  }

  eliminarBest(id:number): Observable<any>{
    const url = `${this.apiUrl}/${id}`;
    return this.http.delete(url);
  }
  verificarAsignacionBest(id:number):Observable<{asignado: boolean}>{
    return this.http.get<{asignado: boolean}>(`${this.baseUrl}/api/bests/${id}`)
  }
}
