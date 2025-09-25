import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class NuevosService {

 private apiUrl = 'http://192.168.112.18:8000/api/gestiones-nuevos'; 

  constructor(private http: HttpClient) {}

  subirArchivos(archivo1: File, archivo2: File): Observable<Blob> {
    const formData = new FormData();
    formData.append('archivo1', archivo1);
    formData.append('archivo2', archivo2);
    return this.http.post(this.apiUrl, formData, { responseType: 'blob' });
  }
}
