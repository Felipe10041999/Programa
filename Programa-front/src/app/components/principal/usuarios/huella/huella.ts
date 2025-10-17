import { Component, OnInit } from '@angular/core';
import { HuellaService } from '../../../../services/huellaService';
import { Router, RouterModule} from '@angular/router'
import { HuellaModel } from '../../../../modelos/huellaModel';
import { CommonModule} from '@angular/common'
import { FormsModule } from '@angular/forms';
import { Observable } from 'rxjs';
import * as XLSX from 'xlsx';
import * as FileSaver from 'file-saver';

@Component({
  selector: 'app-huella',
  standalone: true,
  imports: [CommonModule, RouterModule,FormsModule],
  templateUrl: './huella.html',
  styleUrl: './huella.css'
})
export class Huella implements OnInit{
  
  huellas: (HuellaModel & { asignado?: boolean })[] = [];
  huellasFiltrados: HuellaModel[] = [];
  huellasPaginados: HuellaModel[] = [];
  paginaActual: number = 1;
  registrosPorPagina: number = 8;
  totalPaginas: number =0;
  terminoBusqueda: string = '';

  constructor(
    private servicio : HuellaService,
    private router: Router
  ){}
  if (){}

  ngOnInit(): void {
    this.servicio.listaHuella().subscribe({
      next: (data) =>{
        this.huellas = data;
        // Consultar estado asignado para cada huella
        this.huellas.forEach((huella, idx) => {
          this.servicio.verificarAsignacionHuella(huella.id).subscribe({
            next: (res) => {
              this.huellas[idx].asignado = res.asignado;
              this.filtrarHuellas();
            },
            error: () => {
              this.huellas[idx].asignado = false;
              this.filtrarHuellas();
            }
          });
        });
      },
      error: (error) =>{
        console.error('Error al cargar usuarios de huella', error);
      }
    });
  }
  
  filtrarHuellas(): void{
    const termino = this.terminoBusqueda.toLocaleLowerCase().trim();

    this.huellasFiltrados = this.huellas.filter(huellas =>
      huellas.nombre_usuario.toLowerCase().includes(termino)
    );
    this.totalPaginas = Math.ceil(this.huellasFiltrados.length / this.registrosPorPagina)
    this.paginaActual = 1;
    this.actualizarPaginacion();

  }
  
  actualizarPaginacion(): void{
    const inicio = (this.paginaActual -1) * this.registrosPorPagina;
    const fin = inicio + this.registrosPorPagina;
    this.huellasPaginados = this.huellasFiltrados.slice(inicio, fin);
  }

  cambiarPagina(nuevaPagina: number):void{
    if (nuevaPagina >= 1 && nuevaPagina <=this.totalPaginas){
      this.paginaActual =nuevaPagina;
      this.actualizarPaginacion();
    }
  }

  eliminarHuella(id: number): void{
    const confirmar = window.confirm('¿Estas seguro que deseas eliminar el usuario?');
    if(!confirmar)return;

    this.servicio.eliminarHuella(id).subscribe({
      next:() =>{
        this.huellas = this.huellas.filter(huellas => huellas.id !== id);
        this.filtrarHuellas();
      },
      error: (error) => {
        console.error('Error al eliminar usuario:', error)
      }
    });
  }

  editarHuella(id: number){
    this.router.navigate(['/editar-huella', id])
  }

  irRegistro(){
    this.router.navigate(['/registrar-huella'])
  }

  volverPrincipal(){
    this.router.navigate(['/usuarios'])
  }
  
  exportarAExcel(): void{
    const dataParaExportar = this.huellasFiltrados.map(huella =>({
      Usuario: huella.usuario,
      Clave: huella.nombre_usuario,
      'Nombre del Usuario': huella.nombre_usuario,
      Estado: huella.asignado,
    }));

    const worksheet:XLSX.WorkSheet = XLSX.utils.json_to_sheet(dataParaExportar);
    const workbook: XLSX.WorkBook ={
      Sheets: {'Huella': worksheet},
      SheetNames: ['Huella']
    };

    const excelBuffer: any = XLSX.write(workbook,{
      bookType:'xlsx',
      type: 'array'
    });

    const blob: Blob = new Blob([excelBuffer],{type:'application/octet-stream'});
    FileSaver.saveAs(blob, 'Lista de usuarios de huella.xlsx');
  }
}
