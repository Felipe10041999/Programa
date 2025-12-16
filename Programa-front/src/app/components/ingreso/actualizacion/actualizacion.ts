import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { RouterModule, ActivatedRoute, Router } from '@angular/router';
import { UsuarioService } from '../../../services/usuarioService';
import { EquipoService } from '../../../services/equipoService';
import { HuellaService } from '../../../services/huellaService';
import { of } from 'rxjs';
import { BestService } from '../../../services/best-service';


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
  public equipoId: number | null = null;
  public huellaId: number | null = null;
  public bestId: number | null = null;
  public equipo: any = null;
  public huella: any = null;
  public best: any = null;

  constructor(
    private fb: FormBuilder,
    private usuariosService: UsuarioService,
    private equipoService: EquipoService,
    private huellaService: HuellaService,
    private bestService: BestService,
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
      usuario_equipo: [''],
      clave_equipo: [''],
      usuario_huella: [''],
      nombre_usuario_huella: [''],
      clave_huella: [''],
      nombre_usuario: [''],
      extension: [''],
      clave_best:[''],
      usuario:[''],
      correo: ['', [Validators.required, Validators.email]],
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

        let equipoId: number | null = null;
        const uAny: any = usuario as any;
        const equipoObj = uAny.equipo_usuario ?? uAny.equipoUsuario ?? null;
        if (equipoObj && typeof equipoObj === 'object') {
          equipoId = equipoObj.id ?? null;
        } else if (uAny.equipo_usuario && typeof uAny.equipo_usuario === 'number') {
          equipoId = uAny.equipo_usuario;
        }

        let huellaId: number | null = null;
        const huellaObj = uAny.huella ?? uAny.Huella ?? null;
        if (huellaObj && typeof huellaObj === 'object') {
          huellaId = huellaObj.id ?? null;
        } else if (uAny.huella && typeof uAny.huella === 'number') {
          huellaId = uAny.huella;
        }

        let bestId: number | null = null;
        const bestObj = uAny.best ?? uAny.Best ?? null;
        if (bestObj && typeof bestObj === 'object'){
          bestId = bestObj.id ?? null;
        } else if(uAny.best && typeof uAny.best === 'number'){
          bestId = uAny.best;
        }

        
        this.editarForm.patchValue(usuario);
        this.equipoId = equipoId;
        this.huellaId = huellaId;
        this.bestId = bestId;

        if (equipoId) {
          this.equipoService.obtenerUsuarioId(equipoId).subscribe({
            next: (eq) => {
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

        if(bestId){
          this.bestService.obtenerUsuarioId(bestId).subscribe({
            next: (b) =>{
              this.editarForm.patchValue({
                nombre_usuario: (b as any).usuario ?? '',
                extension: (b as any).extension ?? '' ,
                clave_best: (b as any ).clave ?? '',
                usuario: (b as any ).nombre_usuario
              });
              this.best = b;
            },
            error: (err) => console.error('Error al cargar bestVoIper', err)
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
    const equipoId: number | null = this.equipoId ?? null;
    const huellaId: number | null = this.huellaId ?? null;
    const bestId: number | null = this.bestId ?? null;

    const runUpdateSequence = () => {
      const userPayload: any = {
        nombres: formData.nombres,
        apellidos: formData.apellidos,
        cedula: formData.cedula,
        telefono: formData.telefono,
        cartera: formData.cartera,
        numero_equipo: formData.numero_equipo,
        equipo_usuario: this.equipoId,
        huella: this.huellaId, 
        correo: formData.correo,
        best: this.bestId,
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

    const equipoPayload = { id: equipoId ?? undefined, usuario: formData.usuario_equipo, clave: formData.clave_equipo };
    console.log('Payload equipo (antes de crear/editar):', equipoPayload);
    const equipoOperacion$ = equipoId ? this.equipoService.editarEquipos(equipoId, {
      id: equipoId,
      usuario: formData.usuario_equipo,
      clave: formData.clave_equipo
    }) : (formData.usuario_equipo ? this.equipoService.registrarEquipos({ usuario: formData.usuario_equipo, clave: formData.clave_equipo } as any) : of(null));

    equipoOperacion$.subscribe({
      next: (eqRes: any) => {
        if (!equipoId && eqRes) {
          const newEquipoId = (eqRes.usuario && eqRes.usuario.id) ?? eqRes.id ?? (eqRes.usuario?.id ?? null);
          if (newEquipoId) this.equipoId = newEquipoId;
        }

        const huellaOperacion$ = huellaId ? this.huellaService.editarHuella(huellaId, {
          id: huellaId,
          usuario: formData.usuario_huella,
          nombre_usuario: formData.nombre_usuario_huella,
          clave: formData.clave_huella
        }) : (formData.usuario_huella ? this.huellaService.registrarHuella({ id: 0 as any, usuario: formData.usuario_huella, nombre_usuario: formData.nombre_usuario_huella, clave: formData.clave_huella } as any) : of(null));

        huellaOperacion$.subscribe({
          next: (hRes: any) => {
            if (!huellaId && hRes) {
              const newHuellaId = (hRes.usuario && hRes.usuario.id) ?? hRes.id ?? (hRes.usuario?.id ?? null);
              if (newHuellaId) this.huellaId = newHuellaId;
            }
            runUpdateSequence();
          },

          error: (err: any) => {
            console.error('Error al actualizar/crear huella:', err);
            console.error('Status:', err.status, 'Body:', err.error);
            this.errorMessage = 'Error al actualizar/crear huella: ' + (err.error?.mensaje || err.message || JSON.stringify(err.error));
          }
        });

        const bestOperacion$ = bestId ? this.bestService.editarBest(bestId, {
          id: bestId,
          nombre_usuario: formData.nombre_usuario,
          usuario: formData.usuario,
          clave: formData.clave_best,
          extension: formData.extension
        }) : (formData.nombre_usuario ? this.bestService.registrarBest({ id: 0 as any,nombre_usuario: formData.nombre_usuario, extension: formData.extension, usuario: formData.usuario, clave: formData.clave_best}as any): of(null))

        bestOperacion$.subscribe({
          next: (bRes: any) => {
            if(!bestId && bRes){
              const newBestId = (bRes.usuario && bRes.usuario.id) ?? bRes.id ?? (bRes.usuario?.id ?? null);
              if(newBestId) this.bestId = newBestId;
            }
            runUpdateSequence();
          }
        })
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
