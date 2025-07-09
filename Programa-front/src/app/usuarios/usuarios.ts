import { Component, OnInit } from '@angular/core';
import { UsuariosService } from '../services/usuarios-service';
import { Usuariosmodel } from '../modelos/usuariosmodel';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import * as XLSX from 'xlsx';
import * as FileSaver from 'file-saver';

@Component({
  selector: 'app-usuarios',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './usuarios.html',
  styleUrls: ['./usuarios.css'],
})
export class Usuarios implements OnInit {
  usuarios: Usuariosmodel[] = [];             
  usuariosFiltrados: Usuariosmodel[] = [];    
  usuariosPaginados: Usuariosmodel[] = [];    

  paginaActual: number = 1;
  registrosPorPagina: number = 5;
  totalPaginas: number = 0;
  terminoBusqueda: string = ''; 

  constructor(private usuariosService: UsuariosService, private router: Router) {}

  ngOnInit(): void {
    this.usuariosService.getUsuarios().subscribe({
      next: (data) => {
        this.usuarios = data;
        this.filtrarUsuarios(); 
      },
      error: (error) => {
        console.error('Error al cargar usuarios:', error);
      }
    });
  }

  filtrarUsuarios(): void {
    const termino = this.terminoBusqueda.toLowerCase().trim();

    this.usuariosFiltrados = this.usuarios.filter(usuario =>
      usuario.nombres.toLowerCase().includes(termino) ||
      usuario.apellidos.toLowerCase().includes(termino) ||
      usuario.cartera.toLowerCase().includes(termino)
    );

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

  eliminarUsuario(id: number): void {
    const confirmar = window.confirm('¿Estás seguro de que deseas eliminar este usuario?');
    if (!confirmar) return;

    this.usuariosService.eliminarUsuario(id).subscribe({
      next: () => {
        this.usuarios = this.usuarios.filter(usuario => usuario.id !== id);
        this.filtrarUsuarios(); 
      },
      error: (error) => {
        console.error('Error al eliminar usuario:', error);
      }
    });
  }

  editarUsuario(id: number) {
    this.router.navigate(['/editar', id]);
  }

  IRRegistro() {
    this.router.navigate(['/registro']);
  }

  exportarAExcel(): void {
  const dataParaExportar = this.usuariosFiltrados.map(usuario => ({
    ID: usuario.id,
    Nombres: usuario.nombres,
    Apellidos: usuario.apellidos,
    Cédula: usuario.cedula,
    Teléfono: usuario.telefono,
    Cartera: usuario.cartera,
    'Número de equipo': usuario.numero_equipo,
    'Usuario equipo': usuario.usuario_equipo,
    'Clave equipo': usuario.clave_equipo,
    'Usuario huella': usuario.usuario_huella,
    'Clave huella': usuario.clave_huella,
    Correo: usuario.correo
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
  FileSaver.saveAs(blob, 'Tabla usuarios filtrado.xlsx');
}

}
