import { HttpClient, HttpEvent, HttpEventType, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

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

  /**
   * Sube un archivo Excel para procesar las marcaciones
   * @param archivo - El archivo Excel a subir
   * @returns Observable con el progreso de la carga y la respuesta final
   */

  subirArchivo(archivo: File): Observable<HttpEvent<any>> {
    const formData = new FormData();
    formData.append('archivo', archivo);

    return this.http.post(`${this.apiUrl}/subir`, formData, {
      reportProgress: true,
      observe: 'events',
      responseType: 'blob'
    });
  }



  /**
   * Procesa el progreso de la carga y retorna un objeto con información del progreso
   * @param event - Evento HTTP del progreso
   * @returns Objeto con información del progreso o null si no es un evento de progreso
   */
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

  /**
   * Verifica si el evento es una respuesta exitosa
   * @param event - Evento HTTP
   * @returns true si es una respuesta exitosa
   */

  
  esRespuestaExitosa(event: HttpEvent<any>): boolean {
    return event instanceof HttpResponse && event.status === 200;
  }

  /**
   * Obtiene la respuesta final del servidor
   * @param event - Evento HTTP
   * @returns La respuesta del servidor o null si no es una respuesta
   */
  obtenerRespuesta(event: HttpEvent<any>): any {
    if (event instanceof HttpResponse) {
      return event.body;
    }
    return null;
  }

  /**
   * Valida si el archivo es un archivo Excel válido
   * @param archivo - El archivo a validar
   * @returns true si es un archivo Excel válido
   */
  validarArchivoExcel(archivo: File): boolean {
    const tiposValidos = [
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
      'application/vnd.ms-excel' // .xls
    ];
    
    return tiposValidos.includes(archivo.type);
  }

  /**
   * Obtiene el tamaño del archivo en formato legible
   * @param bytes - Tamaño en bytes
   * @returns Tamaño formateado (KB, MB, etc.)
   */
  formatearTamañoArchivo(bytes: number): string {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  /**
   * Descarga el archivo procesado
   * @param blob - El blob del archivo
   * @param nombreArchivo - Nombre del archivo a descargar
   */
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


