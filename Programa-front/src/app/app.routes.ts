import { Routes } from '@angular/router';
import { Login } from './components/login/login';
import { Principal } from './components/principal/principal';
import { Informes } from './components/principal/informes/informes';
import { AuthGuard } from './guards/auth.guard';
import { NoAuthGuard } from './guards/no-auth.guard';

export const routes: Routes = [
  { 
    path: '', 
    redirectTo: '/ingreso', 
    pathMatch: 'full' 
  },
  {
  path: 'actualizacion/:id',
  loadComponent: () => import('../app/components/ingreso/actualizacion/actualizacion')
  .then(m => m.Actualizacion),
  canActivate: [NoAuthGuard]
  },
  {
    path:'ingreso',
    loadComponent:() => import('./components/ingreso/ingreso')
    .then(m => m.Ingreso),
    canActivate: [NoAuthGuard]
  },
  { 
    path: 'login', 
    loadComponent: () => import('./components/login/login')
    .then(m => m.Login),
    canActivate: [NoAuthGuard]
  },
  { 
    path: 'principal', 
    loadComponent:() => import('./components/principal/principal')
    .then(m => m.Principal),
    canActivate: [AuthGuard]
  },
  { 
    path: 'usuarios',
    loadComponent:() => import('./components/principal/usuarios/usuarios')
    .then(m=> m.Usuarios),
    canActivate: [AuthGuard]
  },
  {
    path: 'nuevos',
    loadComponent:()=> import('./components/principal/nuevos/nuevos')
    .then(m=> m.Nuevos),
    canActivate: [AuthGuard]
  },

  {
        path:'huella',
        loadComponent:() => import('./components/principal/usuarios/huella/huella')
        .then(m => m.Huella)
    },
    {
        path:'registrar-huella',
        loadComponent:() => import ('./components/principal/usuarios/huella/registrar-huella/registrar-huella')
        .then(m =>m.RegistrarHuella)
    },
    {
        path:'editar-huella/:id',
        loadComponent:() => import ('./components/principal/usuarios/huella/editar-huella/editar-huella')
        .then(m => m.EditarHuella)
    },
    {
        path: 'equipo',
        loadComponent: () => import('./components/principal/usuarios/equipo/equipo')
        .then(m => m.Equipo)
    },
    {
        path: 'registrar-equipo',
        loadComponent: () => import('./components/principal/usuarios/equipo/registrar-equipo/registrar-equipo')
        .then(m => m.RegistrarEquipo)
    },
    {
        path: 'editar-equipo/:id',
        loadComponent: () => import('./components/principal/usuarios/equipo/editar-equipo/editar-equipo')
        .then(m => m.EditarEquipo)
    },
    {
        path: 'usuario',
        loadComponent:() => import('./components/principal/usuarios/usuario/usuario')
        .then(m => m.Usuario)
    },
    {
        path: 'registrar-usuario',
        loadComponent:() => import('./components/principal/usuarios/usuario/registrar-usuario/registrar-usuario')
        .then(m => m.RegistrarUsuario)
    },
    {
        path: 'editar-usuario/:id',
        loadComponent:() => import('./components/principal/usuarios/usuario/editar-usuario/editar-usuario')
        .then(m => m.EditarUsuario)
    },
  { 
    path: 'informes',
    loadComponent:() => import('./components/principal/informes/informes')
    .then(m=> m.Informes),
    canActivate: [AuthGuard]
  },
  { 
    path: 'logueo',
    loadComponent:() => import('./components/principal/logueo/logueo')
    .then(m=> m.Logueo),
    canActivate: [AuthGuard]
  },
  // Ruta para capturar cualquier ruta no definida
  { 
    path: '**', 
    redirectTo: '/ingreso' 
  }
];
