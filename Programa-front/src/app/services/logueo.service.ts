import { HttpClient, HttpEvent, HttpEventType, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

export interface UploadProgress {
  loaded: number;
  total: number;
  percentage: number;
}

export interface UploadResponse {
  success: boolean;
  message: string;
  filename?: string;
}

@Injectable({ 
  providedIn: 'root'
})
export class LogueoService {

  private apiUrl = 'http://192.168.112.18:8000/api/archivologueo';

  constructor(private http: HttpClient) { }

  subirArchivo(archivo: File): Observable<HttpEvent<any>> {
    const formData = new FormData();
    formData.append('archivo', archivo);

    return this.http.post(`${this.apiUrl}/subir`, formData, {
      reportProgress: true,
      observe: 'events',
      responseType: 'blob'
    });
  }

  procesarProgreso(event: HttpEvent<any>): UploadProgress | null {
    if (event.type === HttpEventType.UploadProgress) {
      const loaded = event.loaded;
      const total = event.total || 0;
      const percentage = total > 0 ? Math.round(100 * loaded / total) : 0;
      
      return {
        loaded,
        total,
        percentage
      };
    }
    return null;
  }
  
  esRespuestaExitosa(event: HttpEvent<any>): boolean {
    return event instanceof HttpResponse && event.status === 200;
  }

  obtenerRespuesta(event: HttpEvent<any>): any {
    if (event instanceof HttpResponse) {
      return event.body;
    }
    return null;
  }

  validarArchivoExcel(archivo: File): boolean {
    const tiposValidos = [
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
      'application/vnd.ms-excel' // .xls
    ];
    
    return tiposValidos.includes(archivo.type);
  }

  formatearTamañoArchivo(bytes: number): string {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

    descargarArchivo(blob: Blob, nombreArchivo: string = 'archivo_dos_resultado.xlsx'): void {
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = nombreArchivo;
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
  }
}


