import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class InformesService {
  private apiUrl = 'http://192.168.112.18:8000/api'; 
  
  constructor(private http: HttpClient) { }
  generarInformeProductividad(formData: FormData): Observable<Blob> {
    return this.http.post(`${this.apiUrl}/procesar-excel`, formData, { responseType: 'blob' });
  }
  generarInformeProductividadConParametros(parametros: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/procesar-excel`, parametros);
  }
}