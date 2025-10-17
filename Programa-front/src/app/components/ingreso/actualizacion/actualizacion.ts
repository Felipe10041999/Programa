import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { RouterModule, ActivatedRoute, Router } from '@angular/router';
import { UsuarioService } from '../../../services/usuarioService';
import { EquipoService } from '../../../services/equipoService';
import { HuellaService } from '../../../services/huellaService';
import { of } from 'rxjs';


@Component({
  selector: 'app-actualizacion',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './actualizacion.html',
  styleUrls: ['./actualizacion.css']
})
export class Actualizacion implements OnInit {
  editarForm!: FormGroup;
  usuarioEncontrado: boolean = false;
  errorMessage: string = '';
  successMessage: string = '';
  // ids y objetos relacionados expuestos para la plantilla
  public equipoId: number | null = null;
  public huellaId: number | null = null;
  public equipo: any = null;
  public huella: any = null;

  constructor(
    private fb: FormBuilder,
    private usuariosService: UsuarioService,
    private equipoService: EquipoService,
    private huellaService: HuellaService,
    private router: Router,
    private route: ActivatedRoute 
  ) {}

  ngOnInit() {
    
    this.editarForm = this.fb.group({
      nombres: ['', Validators.required],
      apellidos: ['', Validators.required],
      cedula: [{ value: '', disabled: true }, [Validators.required, Validators.pattern(/^\d+$/)]],
      telefono: ['', Validators.required],
      cartera: ['', Validators.required],
      numero_equipo: ['', Validators.required],
      usuario_equipo: ['', Validators.required],
      clave_equipo: ['', Validators.required],
      usuario_huella: ['', Validators.required],
      nombre_usuario_huella: ['', Validators.required],
      clave_huella: ['', Validators.required],
      correo: ['', [Validators.required, Validators.email]],
      extension: ['', [Validators.required, Validators.minLength(2)]],
      usuario_bestvoiper: ['', Validators.required]
    });

    
    const idParam = this.route.snapshot.paramMap.get('id');
    console.log('ID recibido por la ruta:', idParam);

    if (idParam) {
      this.cargarUsuarioPorId(Number(idParam));
    } else {
      console.warn('No se encontró parámetro id en la ruta');
    }
  }

  cargarUsuarioPorId(id: number) {
    this.usuarioEncontrado = false;
    this.errorMessage = '';
    this.successMessage = '';

    this.usuariosService.obtenerUsuarioId(id).subscribe({
      next: usuario => {
        console.log('Usuario obtenido:', usuario);
        this.usuarioEncontrado = true;
        this.editarForm.patchValue(usuario);
        // Equipo: puede venir como objeto en `equipo_usuario` o `equipoUsuario` o solo id
        let equipoId: number | null = null;
        const uAny: any = usuario as any;
        const equipoObj = uAny.equipo_usuario ?? uAny.equipoUsuario ?? null;
        if (equipoObj && typeof equipoObj === 'object') {
          equipoId = equipoObj.id ?? null;
        } else if (uAny.equipo_usuario && typeof uAny.equipo_usuario === 'number') {
          equipoId = uAny.equipo_usuario;
        }

        // Huella: puede venir como objeto en `huella` o `Huella` o solo id
        let huellaId: number | null = null;
        const huellaObj = uAny.huella ?? uAny.Huella ?? null;
        if (huellaObj && typeof huellaObj === 'object') {
          huellaId = huellaObj.id ?? null;
        } else if (uAny.huella && typeof uAny.huella === 'number') {
          huellaId = uAny.huella;
        }

        // Patchear primero los campos del usuario
        this.editarForm.patchValue(usuario);

  // Guardar ids en el componente y cargar objetos relacionados si existen
  this.equipoId = equipoId;
  this.huellaId = huellaId;

        if (equipoId) {
          this.equipoService.obtenerUsuarioId(equipoId).subscribe({
            next: (eq) => {
              // completar campos de equipo en el formulario para permitir edición
              this.editarForm.patchValue({
                usuario_equipo: eq.usuario ?? '',
                clave_equipo: eq.clave ?? ''
              });
              this.equipo = eq;
            },
            error: (err) => console.error('Error al cargar equipo:', err)
          });
        }

        if (huellaId) {
          this.huellaService.obtenerUsuarioId(huellaId).subscribe({
            next: (h) => {
              this.editarForm.patchValue({
                usuario_huella: (h as any).usuario ?? '',
                nombre_usuario_huella: (h as any).nombre_usuario ?? '',
                clave_huella: (h as any).clave ?? ''
              });
              this.huella = h;
            },
            error: (err) => console.error('Error al cargar huella:', err)
          });
        }
      },
      error: err => {
        console.error('Error al obtener usuario:', err);
        this.errorMessage = err.error?.mensaje || 'Error al cargar datos del usuario';
      }
    });
  }

  onSubmit() {
    if (this.editarForm.invalid) {
      this.editarForm.markAllAsTouched();
      return;
    }

    const confirmacion = confirm('¿Estás seguro de que deseas guardar los cambios?');
    if (!confirmacion) return;

    const formData = this.editarForm.getRawValue(); 
    const cedula = formData.cedula;
    // Actualizar equipo y huella si existen ids asociados, luego actualizar usuario
  const equipoId: number | null = this.equipoId ?? null;
  const huellaId: number | null = this.huellaId ?? null;

    // Ejecutar secuencia: crear/actualizar equipo -> crear/actualizar huella -> actualizar usuario
    const runUpdateSequence = () => {
      // After equipo/huella are created/updated, perform usuario update
      const userPayload: any = {
        nombres: formData.nombres,
        apellidos: formData.apellidos,
        cedula: formData.cedula,
        telefono: formData.telefono,
        cartera: formData.cartera,
        numero_equipo: formData.numero_equipo,
        equipo_usuario: this.equipoId, // id esperado por backend
        huella: this.huellaId, // id esperado por backend
        correo: formData.correo,
        usuario_bestvoiper: formData.usuario_bestvoiper,
        extension: formData.extension
      };

      console.log('Enviando payload usuario:', userPayload);

      this.usuariosService.actualizarUsuarioPorCedula(cedula, userPayload).subscribe({
        next: () => {
          this.successMessage = 'Usuario actualizado con éxito';
          this.router.navigate(['/ingreso']);
        },
        error: (err: any) => {
          console.error('Error al actualizar usuario:', err);
          const backendError = err.error?.error || err.error?.mensaje;
          this.errorMessage = 'Error al actualizar usuario: ' + (backendError || err.message);
        }
      });
    };

    // 1) Equipo: crear si no existe id y hay datos, o actualizar si existe
    const equipoPayload = { id: equipoId ?? undefined, usuario: formData.usuario_equipo, clave: formData.clave_equipo };
    console.log('Payload equipo (antes de crear/editar):', equipoPayload);
    const equipoOperacion$ = equipoId ? this.equipoService.editarEquipos(equipoId, {
      id: equipoId,
      usuario: formData.usuario_equipo,
      clave: formData.clave_equipo
    }) : (formData.usuario_equipo ? this.equipoService.registrarEquipos({ usuario: formData.usuario_equipo, clave: formData.clave_equipo } as any) : of(null));

    equipoOperacion$.subscribe({
      next: (eqRes: any) => {
        // si se creó, setear el id retornado (puede venir en res.usuario.id o res.id)
        if (!equipoId && eqRes) {
          const newEquipoId = (eqRes.usuario && eqRes.usuario.id) ?? eqRes.id ?? (eqRes.usuario?.id ?? null);
          if (newEquipoId) this.equipoId = newEquipoId;
        }
        // 2) Huella: crear si no existe id y hay datos, o actualizar si existe
        const huellaOperacion$ = huellaId ? this.huellaService.editarHuella(huellaId, {
          id: huellaId,
          usuario: formData.usuario_huella,
          nombre_usuario: formData.nombre_usuario_huella,
          clave: formData.clave_huella
        }) : (formData.usuario_huella ? this.huellaService.registrarHuella({ id: 0 as any, usuario: formData.usuario_huella, nombre_usuario: formData.nombre_usuario_huella, clave: formData.clave_huella } as any) : of(null));

        huellaOperacion$.subscribe({
          next: (hRes: any) => {
            // extraer id que pudo venir en res.usuario.id o res.id
            if (!huellaId && hRes) {
              const newHuellaId = (hRes.usuario && hRes.usuario.id) ?? hRes.id ?? (hRes.usuario?.id ?? null);
              if (newHuellaId) this.huellaId = newHuellaId;
            }
            // Ejecutar actualización de usuario
            runUpdateSequence();
          },
          error: (err: any) => {
            console.error('Error al actualizar/crear huella:', err);
            console.error('Status:', err.status, 'Body:', err.error);
            this.errorMessage = 'Error al actualizar/crear huella: ' + (err.error?.mensaje || err.message || JSON.stringify(err.error));
          }
        });
      },
      error: (err: any) => {
        console.error('Error al actualizar/crear equipo:', err);
        console.error('Status:', err.status, 'Body:', err.error);
        this.errorMessage = 'Error al actualizar/crear equipo: ' + (err.error?.mensaje || err.message || JSON.stringify(err.error));
      }
    });
  }


  cancelar() {
    this.router.navigate(['/ingreso']);
  }
}
