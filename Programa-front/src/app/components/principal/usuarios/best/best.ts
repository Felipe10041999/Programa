import { Component, OnInit } from '@angular/core';
import { BestService } from '../../../../services/best-service';
import { Router, RouterModule } from '@angular/router'
import { BestModelModel } from '../../../../modelos/bestModel.model';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import * as XLSX from 'xlsx';
import * as FileSaver from 'file-saver';


@Component({
  selector: 'app-best',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './best.html',
  styleUrl: './best.css'
})
export class Best implements OnInit {
  bests: (BestModelModel & {asignado?: boolean})[] = [];
  bestsFiltrados: BestModelModel[] = [];
  bestsPaginados: BestModelModel[] = [];
  paginaActual: number = 1;
  registroPorPagina: number = 8;
  totalPaginas: number = 0;
  terminoBusqueda: string = '';

  constructor(
    private servicio : BestService,
    private router: Router
  ){}
  if(){}

  ngOnInit(): void {
    this.servicio.listaBest().subscribe({
      next: (data) =>{
        this.bests = data;
        this.bests.forEach((best, idx) =>{
          this.servicio.verificarAsignacionBest(best.id).subscribe({
            next: (res) =>{
              this.bests[idx].asignado =res.asignado;
              this.filtrarBests();
            },
            error: () =>{
              this.bests[idx].asignado = false;
              this.filtrarBests();
            }
          });
        });
      },
      error: (error) =>{
        console.error('Error al cargar usuarios de bestVoIper', error);
      }
    });
  }

  filtrarBests(): void{
    const termino = this.terminoBusqueda.toLocaleLowerCase().trim();

    this.bestsFiltrados = this.bests.filter(bests =>
      bests.nombre_usuario.toLocaleLowerCase().includes(termino)||
      bests.extension.toLocaleLowerCase().includes(termino)
    );
    this.totalPaginas = Math.ceil (this.bestsFiltrados.length / this.registroPorPagina)
    this.paginaActual = 1;
    this.actualizarPaginacion();
  }

  actualizarPaginacion(): void {
    const inicio = (this.paginaActual -1) * this.registroPorPagina;
    const fin = inicio + this.registroPorPagina;
    this.bestsPaginados = this.bestsFiltrados.slice(inicio, fin);
  }

  cambiarPagina(nuevaPagina: number): void{
    if(nuevaPagina >= 1 && nuevaPagina <= this.totalPaginas){
      this.paginaActual = nuevaPagina;
      this.actualizarPaginacion();
    }
  }

  eliminarBest(id: number): void{
    const confirmar = window.confirm('¿Estas seguro que deseas eliminar el usuario?');
    if (!confirmar) return;

    this.servicio.eliminarBest(id).subscribe({
      next:()=>{
        this.bests =this.bests.filter(bests => bests.id !== id);
        this.filtrarBests();
      },
      error:(error) =>{
        console.error('Error al eliminar usuario:' , error)
      }
    });
  }

  editarBest(id:number){
    this.router.navigate(['/best-editar', id])
  }

  irRegistro(){
    this.router.navigate(['/best-registrar'])
  }

  volverPrincipal(){
    this.router.navigate(['/usuarios'])
  }

  exportarAExcel(): void{
    const dataParaExportar = this.bestsFiltrados.map(best =>({
      'Nombre usuario': best.nombre_usuario,
      Extension: best.extension,
      Usuario: best.usuario,
      Clave: best.clave,
      Estado: best.asignado
    }));
    const worksheet:XLSX.WorkSheet = XLSX.utils.json_to_sheet(dataParaExportar);
    const workbook: XLSX.WorkBook ={
      Sheets: {'Best': worksheet},
      SheetNames: ['Best']
    };

    const excelBuffer: any = XLSX.write(workbook,{
      bookType: 'xlsx',
      type: 'array'
    });

    const blob: Blob = new Blob([excelBuffer],{type:'application/octet-stream'});
    FileSaver.saveAs(blob, 'Lista de usuarios de BestVoIper');
  }
}
