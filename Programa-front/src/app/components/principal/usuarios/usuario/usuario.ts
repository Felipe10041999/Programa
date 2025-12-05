import { Component, OnInit } from '@angular/core';
import { Router, RouterModule } from '@angular/router'
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { UsuarioModel } from '../../../../modelos/usuariosmodel';
import { UsuarioService } from '../../../../services/usuarioService';
import * as XLSX from 'xlsx';
import * as FileSaver from 'file-saver';

@Component({
  selector: 'app-usuario',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './usuario.html',
  styleUrl: './usuario.css'
})
export class Usuario implements OnInit{

  usuarios: UsuarioModel[] = [];
  usuariosFiltrados: UsuarioModel[] = [];
  usuariosPaginados: UsuarioModel[] = [];
  paginaActual: number = 1;
  registrosPorPagina: number = 4;
  totalPaginas: number = 0;
  terminoBusqueda: string = '';
  mostrarSoloSinCorreo: boolean = false;
  

  constructor(
    private servicio : UsuarioService,
    private router: Router
  ){}

  ngOnInit(): void {
    this.servicio.listaUsuarios().subscribe({
      next: (data) =>{
        this.usuarios = data;
        this.filtrarUsuarios();
      },
      error:(error) => {
        console.error('Error al cargar los usuarios', error)
      }
    });
  }

   filtrarUsuarios(): void {
    const termino = this.terminoBusqueda.toLowerCase().trim();

    this.usuariosFiltrados = this.usuarios.filter(usuario =>
      (usuario.nombres || '').toLowerCase().includes(termino) ||
      (usuario.apellidos || '').toLowerCase().includes(termino)
    );

    if (this.mostrarSoloSinCorreo) {
      this.usuariosFiltrados = this.usuariosFiltrados.filter(u => !u.correo || u.correo.toString().trim() === '');
    }
 
    this.totalPaginas = Math.ceil(this.usuariosFiltrados.length / this.registrosPorPagina);
    this.paginaActual = 1;
    this.actualizarPaginacion();
  }

  actualizarPaginacion(): void {
    const inicio = (this.paginaActual - 1) * this.registrosPorPagina;
    const fin = inicio + this.registrosPorPagina;
    this.usuariosPaginados = this.usuariosFiltrados.slice(inicio, fin);
  }

  cambiarPagina(nuevaPagina: number): void {
    if (nuevaPagina >= 1 && nuevaPagina <= this.totalPaginas) {
      this.paginaActual = nuevaPagina;
      this.actualizarPaginacion();
    }
  }

  toggleMostrarSoloSinCorreo(): void {
    this.mostrarSoloSinCorreo = !this.mostrarSoloSinCorreo;
    this.filtrarUsuarios();
  }
  
  eliminarUsuario(id:number):void{
    const confirmar = window.confirm('¿Estas segura que deseas eliminar el usuario?');
    if (!confirmar)return;

    this.servicio.eliminarUsuario(id).subscribe({
      next:() =>{
        this.usuarios= this.usuarios.filter(usuarios => usuarios.id !== id);
        this.filtrarUsuarios();
      },
      error: (error) =>{
        console.error('Error al eliminar el usuario', error)
      } 
    });
  }

  editarUsuario(id:number, usuario: UsuarioModel){
    this.router.navigate(['/editar-usuario', id])
  }

  irRegistro(){
    this.router.navigate(['/registrar-usuario'])
  }
  
  volverPrincipal(){
    this.router.navigate(['/usuarios'])
  }
  exportarAExcel(): void {
    const dataParaExportar = this.usuariosFiltrados.map(usuario => ({
      ID: usuario.id ?? '',
      Nombres: usuario.nombres ?? '',
      Apellidos: usuario.apellidos ?? '',
      Cédula: usuario.cedula ?? '',
      Teléfono: usuario.telefono ?? '',
      Cartera: usuario.cartera ?? '',
      'Número de equipo': usuario.numero_equipo ?? '',
      'Usuario equipo': usuario.equipo_usuario?.usuario ?? '',
      'Clave equipo': usuario.equipo_usuario?.clave ?? '',
      'Usuario huella': usuario.huella?.usuario ?? '',
      'Nombre usuario huella': usuario.huella?.nombre_usuario ?? '',
      'Clave huella': usuario.huella?.clave ?? '',
      Correo: usuario.correo ?? '',
      'Nombre usuario BestVoIPer' : usuario.best?.nombre_usuario ?? '',
      Extension : usuario.best?.extension ?? '',
      'Usuario BestVoIper' : usuario.best?.usuario ?? '',
      'Clave BestVoIper' : usuario.best?.clave ?? '',
      'No_diadema': usuario.no_diadema ?? ''

    }));

      const worksheet: XLSX.WorkSheet = XLSX.utils.json_to_sheet(dataParaExportar);
      const workbook: XLSX.WorkBook = {
        Sheets: { 'Usuarios': worksheet },
        SheetNames: ['Usuarios']
    };

      const excelBuffer: any = XLSX.write(workbook, {
        bookType: 'xlsx',
        type: 'array'
    });

      const blob: Blob = new Blob([excelBuffer], { type: 'application/octet-stream' });
      FileSaver.saveAs(blob, 'Informacion de agentes.xlsx');
  }
}
