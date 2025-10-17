import { Component, OnInit } from '@angular/core';
import { EquipoService} from '../../../../services/equipoService';
import {Router, RouterModule} from '@angular/router'
import { EquipoModel } from '../../../../modelos/equipoModel.model';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import * as XLSX from 'xlsx';
import * as FileSaver from 'file-saver';


@Component({
  selector: 'app-equipo',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './equipo.html',
  styleUrl: './equipo.css'
})
export class Equipo implements OnInit {

  equipos: EquipoModel[] = [];
  equiposFiltrados: EquipoModel[] = [];
  equiposPaginados: EquipoModel[] = [];
  paginaActual: number = 1;
  registrosPorPagina:number = 8;
  totalPaginas: number = 0;
  terminoBusqueda: string = '';

    constructor(private equipoService: EquipoService,
    private router: Router){}

  ngOnInit(): void {
    this.equipoService.obtenerEquipos().subscribe({
      next: (data) =>{
        this.equipos = data;
        this.equipos.forEach((equipo, idx) =>{
          this.equipoService.verificarAsignacionEquipo(equipo.id).subscribe({
            next: (res) =>{
              this.equipos[idx].asignado =res.asignado;
              this.buscar();
            },
            error:()=>{
              this.equipos[idx].asignado =false;
              this.buscar();
            }
          });
        });      },
      error: (error) =>{
        console.error('Error al cargar usuarios: ',  error);
      }
    });
  }

  buscar(): void{
    const termino = this.terminoBusqueda.toLowerCase().trim();
    this.equiposFiltrados = this.equipos.filter(equipos =>
      equipos.usuario.toLowerCase().includes(termino)
    );
    this.totalPaginas = Math.ceil(this.equiposFiltrados.length / this.registrosPorPagina);
    this.paginaActual = 1;
    this.actualizarPaginacion();
  }

  actualizarPaginacion(): void{
    const inicio = (this.paginaActual - 1) * this.registrosPorPagina;
    const fin = inicio + this.registrosPorPagina;
    this.equiposPaginados = this.equiposFiltrados.slice(inicio,fin);
  }
  
  cambiarPagina(nuevaPagina: number):void{
    if(nuevaPagina >= 1 && nuevaPagina <= this.totalPaginas){
      this.paginaActual = nuevaPagina;
      this.actualizarPaginacion();
    }
  }

  eliminarEquipo(id: number): void{
    const confirmar = window.confirm('¿Estas seguro que deseas eliminar el usuario?');
    if(!confirmar)return;

    this.equipoService.eliminarUsuario(id).subscribe({
      next:() =>{
        this.equipos = this.equipos.filter(equipos => equipos.id !== id);
        this.buscar();
      },
      error: (error) => {
        console.error('Error al eliminar usuario:', error)
      }
    });
  }

  editarEquipo(id: number){
    this.router.navigate(['/editar-equipo', id])
  }
  
  irRegistro(){
    this.router.navigate(['/registrar-equipo']);
  }

  volverPrincipal(){
    this.router.navigate(['/usuarios'])
  }
  exportarAExcel(): void{
      const dataParaExportar = this.equiposFiltrados.map(equipo =>({
        Usuario: equipo.usuario,
        Clave: equipo.clave,
        Estado: equipo.asignado,
      }));
  
      const worksheet:XLSX.WorkSheet = XLSX.utils.json_to_sheet(dataParaExportar);
      const workbook: XLSX.WorkBook ={
        Sheets: {'Equipo': worksheet},
        SheetNames: ['Equipo']
      };
  
      const excelBuffer: any = XLSX.write(workbook,{
        bookType:'xlsx',
        type: 'array'
      });
  
      const blob: Blob = new Blob([excelBuffer],{type:'application/octet-stream'});
      FileSaver.saveAs(blob, 'Tabla de usuarios de los equipos.xlsx');
    }
}
