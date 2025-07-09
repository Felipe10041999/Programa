import { Routes } from '@angular/router';
import { Principal } from './components/principal/principal';

export const routes: Routes = [
  { path: '', component: Principal },
  { path: 'usuarios',
    loadComponent:() => import('./components/principal/usuarios/usuarios')
    .then(m=> m.Usuarios)
  },
  { path: 'registro',
    loadComponent:() => import('./components/principal/usuarios/registrar-usuarios/registrar-usuarios')
    .then(m=> m.RegistrarUsuarios)},
  { path: 'editar/:id',
    loadComponent:() => import('./components/principal/usuarios/editar-usuarios/editar-usuarios')
    .then(m=> m.EditarUsuarios)},
];
