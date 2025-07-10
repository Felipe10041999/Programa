import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class InformesService {
  private apiUrl = 'http://localhost:8000/api'; // URL base

  constructor(private http: HttpClient) { }

  generarInformeProductividad(formData: FormData): Observable<Blob> {
    return this.http.post(`${this.apiUrl}/procesar-excel`, formData, { responseType: 'blob' }); // Enviar formData
  }

  generarInformeProductividadConParametros(parametros: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/procesar-excel`, parametros);
  }
}