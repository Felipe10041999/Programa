import { Routes } from '@angular/router';
import { Usuarios } from './usuarios/usuarios';
import { RegistrosUsuarios } from './usuarios/registros-usuarios/registros-usuarios';

export const routes: Routes = [
  { path: '', component: Usuarios },
  { path: 'registro',
    loadComponent:() => import('./usuarios/registros-usuarios/registros-usuarios')
    .then(m=> m.RegistrosUsuarios)},
    { path: 'editar/:id',
    loadComponent:() => import('./usuarios/editar-usuario/editar-usuario')
    .then(m=> m.EditarComponent)},
   
];
