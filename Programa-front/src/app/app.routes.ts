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
    path: 'registro',
    loadComponent:() => import('./components/principal/usuarios/registrar-usuarios/registrar-usuarios')
    .then(m=> m.RegistrarUsuarios),
    canActivate: [AuthGuard]
  },
  { 
    path: 'editar/:id',
    loadComponent:() => import('./components/principal/usuarios/editar-usuarios/editar-usuarios')
    .then(m=> m.EditarUsuarios),
    canActivate: [AuthGuard]
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
