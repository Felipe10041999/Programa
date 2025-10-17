import { Component, OnInit } from '@angular/core';
import { FormsModule, FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { UsuarioService } from '../../../../../services/usuarioService';
import { EquipoService } from '../../../../../services/equipoService';
import { HuellaService } from '../../../../../services/huellaService';
import { UsuarioModel } from '../../../../../modelos/usuariosmodel';
import { EquipoModel } from '../../../../../modelos/equipoModel.model';
import { HuellaModel } from '../../../../../modelos/huellaModel';
import { map, startWith } from 'rxjs/operators';

@Component({
  selector: 'app-registrar-usuario',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, CommonModule],
  templateUrl: './registrar-usuario.html',
  styleUrl: './registrar-usuario.css'
})
export class RegistrarUsuario implements OnInit {

  registroForm: FormGroup;
  usuarios: UsuarioModel[] = [];
  equipos: EquipoModel[] = [];
  equiposDisponibles: EquipoModel[] = [];
  huellas: HuellaModel[] = [];
  huellasDisponibles: HuellaModel[] = [];
  error: string = '';
  mensaje: string = '';

  // Nuevas propiedades para el autocompletado de correos
  listaCorreos: string[] = [];
  correosAsignados: string[] = [];
  correosFiltrados: string[] = [];

  constructor(
    private fb: FormBuilder,
    private usuarioService: UsuarioService,
    private equipoService: EquipoService,
    private huellaService: HuellaService,
    private router: Router
  ) {
    this.registroForm = this.fb.group({
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      cedula: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      telefono: ['', Validators.required],
      cartera: ['', Validators.required],
      numero_equipo: ['', Validators.required],
      equipo_usuario: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      huella: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      correo: ['', [Validators.required, Validators.email]],
      usuario_bestvoiper: ['', Validators.required],
      extension: ['', Validators.required],
    });
  }

  ngOnInit(): void {
    this.generarCorreosBase();
    this.cargarUsuarios();
    this.cargarEquipos();
    this.cargarHuellas();

    // Escucha dinámica para el campo correo
    this.registroForm.get('correo')?.valueChanges
      .pipe(
        startWith(''),
        map(value => this.filtrarCorreos(value || ''))
      )
      .subscribe(correos => {
        this.correosFiltrados = correos;
      });
  }

  generarCorreosBase(): void {
    this.listaCorreos = Array.from({ length: 70 }, (_, i) => `ellibertador${i + 1}@ngsoabogados.com`);
  }

  cargarUsuarios(): void {
    this.usuarioService.listaUsuarios().subscribe({
      next: (data) => {
        this.usuarios = data;

        // Extraer correos ya usados
        this.correosAsignados = this.usuarios
          .map(u => u.correo?.toLowerCase())
          .filter(correo => !!correo); // Elimina undefined/null

        // Inicializar correos filtrados excluyendo los usados
        this.correosFiltrados = this.filtrarCorreos('');
      },
      error: (error) => {
        console.error('Error al cargar usuarios existentes:', error);
      }
    });
  }

  filtrarCorreos(valor: string): string[] {
    const filtro = valor.toLowerCase();
    return this.listaCorreos
      .filter(correo =>
        correo.toLowerCase().includes(filtro) &&
        !this.correosAsignados.includes(correo.toLowerCase())
      );
  }

  cargarEquipos(): void {
    this.equipoService.obtenerEquipos().subscribe({
      next: (data) => {
        this.equipos = data;
        let pendientes = this.equipos.length;
        this.equiposDisponibles = [];
        this.equipos.forEach((equipo) => {
          this.equipoService.verificarAsignacionEquipo(equipo.id).subscribe({
            next: (res) => {
              if (!res.asignado) {
                this.equiposDisponibles.push(equipo);
              }
              pendientes--;
            },
            error: () => {
              pendientes--;
            }
          });
        });
      },
      error: (error) => {
        console.error('Error al cargar los Usuarios de los equipos')
      }
    });
  }

  cargarHuellas(): void {
    this.huellaService.listaHuella().subscribe({
      next: (data) => {
        this.huellas = data;
        let pendientes = this.huellas.length;
        this.huellasDisponibles = [];
        this.huellas.forEach((huella) => {
          this.huellaService.verificarAsignacionHuella(huella.id).subscribe({
            next: (res) => {
              if (!res.asignado) {
                this.huellasDisponibles.push(huella);
              }
              pendientes--;
            },
            error: () => {
              pendientes--;
            }
          });
        });
      },
      error: (error) => {
        console.error('Error al cargar los usuarios de huella')
      }
    });
  }

  registrarUsuario(): void {
    if (this.registroForm.valid) {
      console.log('Formulario de usuario:', this.registroForm.value)
      this.usuarioService.registrarUsuario(this.registroForm.value).subscribe({
        next: (respuesta) => {
          this.mensaje = 'Usuario registrado correctamente';
          this.error = '';
          this.registroForm.reset();
          this.router.navigate(['/usuario']);
          alert('Registro exitoso');
        },
        error: (err) => {
          console.error(err);
          this.error = 'Error al registrar el usuario';
          this.mensaje = '';
        }
      });

    } else {
      this.error = 'Por favor completa todos los campos correctamente';
      this.mensaje = '';
    }
  }

  cancelarRegistro() {
    this.router.navigate(['/usuario']);
  }

}
