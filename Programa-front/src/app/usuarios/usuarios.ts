import { Component, OnInit } from '@angular/core';
import { UsuariosService } from '../services/usuarios-service';
import { Usuariosmodel } from '../modelos/usuariosmodel';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';

@Component({
  selector: 'app-usuarios',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './usuarios.html',
  styleUrls: ['./usuarios.css'],  // corregido aquí
})

export class Usuarios implements OnInit {
  usuarios: Usuariosmodel[] = [];
  usuariosPaginados: Usuariosmodel[] = [];
  
  paginaActual: number = 1;
  registrosPorPagina: number = 7;
  totalPaginas: number = 0;

  constructor(private usuariosService: UsuariosService, private router :Router) {}

  ngOnInit(): void {
    this.usuariosService.getUsuarios().subscribe({
      next: (data) => {
        this.usuarios = data;
        this.totalPaginas = Math.ceil(this.usuarios.length / this.registrosPorPagina);
        this.actualizarPaginacion();
      },
      error: (error) => {
        console.error('Error al cargar usuarios:', error);
      }
    });
  }
  

  actualizarPaginacion(): void {
    const inicio = (this.paginaActual - 1) * this.registrosPorPagina;
    const fin = inicio + this.registrosPorPagina;
    this.usuariosPaginados = this.usuarios.slice(inicio, fin);
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
        this.totalPaginas = Math.ceil(this.usuarios.length / this.registrosPorPagina);
        if (this.paginaActual > this.totalPaginas) this.paginaActual = this.totalPaginas;
        this.actualizarPaginacion();
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
} 

