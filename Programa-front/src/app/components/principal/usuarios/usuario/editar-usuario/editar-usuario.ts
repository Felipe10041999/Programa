import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators ,ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { UsuarioService } from '../../../../../services/usuarioService';
import { HuellaService } from '../../../../../services/huellaService';
import { EquipoService } from '../../../../../services/equipoService';
import { UsuarioModel } from '../../../../../modelos/usuariosmodel';
import { HuellaModel } from '../../../../../modelos/huellaModel';
import { EquipoModel } from '../../../../../modelos/equipoModel.model';
import { map, startWith } from 'rxjs/operators';

@Component({
  selector: 'app-editar-usuario',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './editar-usuario.html',
  styleUrl: './editar-usuario.css'
})
export class EditarUsuario implements OnInit {

  editarForm: FormGroup;
  usuarios: UsuarioModel[] = [];
  equipos: EquipoModel[] = [];
  equiposDisponibles: EquipoModel[] = [];
  huellas: HuellaModel[] = [];
  huellasDisponibles: HuellaModel[] = [];
  error: string = '';
  mensaje: string = '';
  id: number = 0;
  listaCorreos: string[] = [];
  correosAsignados: string[] = [];
  correosFiltrados: string[] = [];

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private servicio: UsuarioService,
    private equipoService: EquipoService,
    private huellaService: HuellaService,
    private router: Router
  ) {
    this.editarForm = this.fb.group({
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      cedula: ['', [Validators.required, Validators.pattern(/^\d+$/)]],
      telefono: ['', Validators.required],
      cartera: ['', Validators.required],
      numero_equipo: ['', Validators.required],
      equipo_usuario: ['', Validators.required],
      huella: ['', Validators.required],
      correo: ['', [Validators.required, Validators.email]],
      usuario_bestvoiper: ['', Validators.required],
      extension: ['', [Validators.required, Validators.minLength(2)]],
      no_diadema: ['', Validators.required]

    });
  }

  ngOnInit() {
    this.id = Number(this.route.snapshot.paramMap.get('id'));
    this.generarCorreosBase(); 
    this.cargarUsuarios(); 
    this.cargarEquipos();
    this.cargarHuellas();

    this.editarForm.get('correo')?.valueChanges
      .pipe(
        startWith(''),
        map(value => this.filtrarCorreos(value || ''))
      )
      .subscribe(filtrados => {
        this.correosFiltrados = filtrados;
      });
  }

  generarCorreosBase(): void {
    this.listaCorreos = Array.from({ length: 70 }, (_, i) => `ellibertador${i + 1}@ngsoabogados.com`);
  }

  cargarUsuarios() {
    // 1. Obtener todos los usuarios para filtrar correos asignados
    this.servicio.listaUsuarios().subscribe({
      next: (usuarios) => {
        this.usuarios = usuarios;
        // Filtrar correos ya usados (excepto del usuario actual)
        this.correosAsignados = usuarios
          .filter(u => u.id !== this.id)
          .map(u => u.correo?.toLowerCase())
          .filter(c => !!c);

        // 2. Obtener datos del usuario actual
        this.servicio.obtenerUsuarioId(this.id).subscribe({
          next: usuario => {
            this.editarForm.patchValue(usuario);
            // Asegurarse que el correo actual siga visible
            this.correosFiltrados = this.filtrarCorreos('');
          },
          error: err => console.error('Error al cargar el usuario', err)
        });
      },
      error: err => console.error('Error al cargar usuarios para filtrar correos', err)
    });
  }

  filtrarCorreos(valor: string): string[] {
    const filtro = valor.toLowerCase();
    const correoActual = this.editarForm.get('correo')?.value?.toLowerCase();
    return this.listaCorreos.filter(correo =>
      correo.toLowerCase().includes(filtro) &&
      (!this.correosAsignados.includes(correo.toLowerCase()) || correo.toLowerCase() === correoActual)
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
              const equipoActualId = this.editarForm.get('equipo_usuario')?.value;
              if (!res.asignado || equipo.id === equipoActualId) {
                if (!this.equiposDisponibles.some(e => e.id === equipo.id)) {
                  this.equiposDisponibles.push(equipo);
                }
              }
              pendientes--;
            },
            error: () => {
              pendientes--;
            }
          });
        });
      },
      error: () => {
        console.error('Error al cargar los Usuarios de los equipos');
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
              const huellaActualId = this.editarForm.get('huella')?.value;
              if (!res.asignado || huella.id === huellaActualId) {
                if (!this.huellasDisponibles.some(h => h.id === huella.id)) {
                  this.huellasDisponibles.push(huella);
                }
              }
              pendientes--;
            },
            error: () => {
              pendientes--;
            }
          });
        });
      },
      error: () => {
        console.error('Error al cargar los usuarios de huella');
      }
    });
  }

  onSubmit() {
    if (this.editarForm.valid) {
      this.servicio.editarUsuario(this.id, this.editarForm.value).subscribe({
        next: res => {
          alert('Usuario actualizado con éxito');
          this.router.navigate(['/usuario']);
        },
        error: err => {
          console.error('Error completo: ', err);
          const backendError = err.error?.error || err.error?.mensaje;
          alert('Error al actualizar: ' + (backendError || err.mensaje));
        }
      });
    }
  }

  cancelarEditar() {
    this.router.navigate(['/usuario']);
  }
}
