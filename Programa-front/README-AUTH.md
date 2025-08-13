# Sistema de Autenticación - NGSO

## Descripción
Este documento describe el sistema de autenticación implementado en la aplicación Angular para el Sistema de Gestión NGSO.

## Componentes Implementados

### 1. Componente de Login (`/src/app/components/login/`)
- **login.ts**: Lógica del componente de inicio de sesión
- **login.html**: Template del formulario de login
- **login.css**: Estilos del componente de login

### 2. Servicio de Autenticación (`/src/app/services/auth.service.ts`)
- Maneja el estado de autenticación
- Gestiona tokens de sesión
- Verifica expiración de sesiones
- Proporciona métodos para login/logout

### 3. Guards de Autenticación
- **AuthGuard** (`/src/app/guards/auth.guard.ts`): Protege rutas que requieren autenticación
- **NoAuthGuard** (`/src/app/guards/no-auth.guard.ts`): Previene acceso al login si ya está autenticado

## Flujo de Autenticación

### 1. Inicio de Sesión
1. Usuario accede a `/login`
2. Ingresa nombre de usuario
3. Sistema valida con el backend
4. Se crea/actualiza sesión en la base de datos
5. Se almacena token en localStorage
6. Redirección a `/principal`

### 2. Protección de Rutas
- Todas las rutas excepto `/login` están protegidas
- El `AuthGuard` verifica autenticación antes de permitir acceso
- Si no hay sesión válida, redirección automática a `/login`

### 3. Cierre de Sesión
- Botón de "Cerrar Sesión" en el header principal
- Limpia datos locales y redirecciona a `/login`
- Notifica al backend para invalidar la sesión

## Estructura de Datos

### Sesión de Usuario
```typescript
interface Sesion {
  id: number;
  nombre_usuario: string;
  token_sesion: string;
  ultimo_acceso: string;
  estado_sesion: string;
}
```

### Almacenamiento Local
- `sesion`: Datos completos de la sesión
- `usuario_autenticado`: Flag de estado de autenticación

## Configuración de Rutas

### Rutas Públicas
- `/login`: Formulario de inicio de sesión

### Rutas Protegidas
- `/principal`: Panel principal (requiere autenticación)
- `/usuarios`: Gestión de usuarios
- `/registro`: Registro de usuarios
- `/editar/:id`: Edición de usuarios
- `/informes`: Generación de informes

## Características de Seguridad

### 1. Verificación de Sesión
- Validación automática al cargar la aplicación
- Verificación de expiración (8 horas de inactividad)
- Renovación automática de sesión activa

### 2. Protección de Rutas
- Guards implementados en todas las rutas protegidas
- Redirección automática en caso de sesión inválida
- Prevención de acceso directo a URLs protegidas

### 3. Manejo de Errores
- Validación de formularios
- Manejo de errores de conexión
- Mensajes de error informativos para el usuario

## Uso del Sistema

### Para Desarrolladores

#### 1. Agregar Protección a Nuevas Rutas
```typescript
import { AuthGuard } from './guards/auth.guard';

export const routes: Routes = [
  {
    path: 'nueva-ruta',
    component: NuevoComponente,
    canActivate: [AuthGuard]
  }
];
```

#### 2. Verificar Autenticación en Componentes
```typescript
import { AuthService } from './services/auth.service';

constructor(private authService: AuthService) {}

ngOnInit() {
  if (this.authService.isAuthenticated()) {
    // Usuario autenticado
  }
}
```

#### 3. Obtener Información del Usuario
```typescript
const usuario = this.authService.getCurrentUser();
const token = this.authService.getToken();
```

### Para Usuarios

#### 1. Iniciar Sesión
1. Acceder a la aplicación
2. Ingresar nombre de usuario
3. Hacer clic en "Iniciar Sesión"
4. Esperar validación y redirección automática

#### 2. Navegar por la Aplicación
- Todas las funcionalidades disponibles después del login
- Header muestra usuario actual y botón de cerrar sesión
- Navegación protegida automáticamente

#### 3. Cerrar Sesión
- Hacer clic en "Cerrar Sesión" en el header
- Redirección automática a la pantalla de login
- Limpieza automática de datos de sesión

## Configuración del Backend

### Endpoints Requeridos
- `POST /api/login`: Inicio de sesión
- `POST /api/logout`: Cierre de sesión
- `GET /api/verificar`: Verificación de autenticación

### Base de Datos
- Tabla `iniciars` para almacenar sesiones
- Campos: id, nombre_usuario, token_sesion, ultimo_acceso, estado_sesion

## Consideraciones Técnicas

### 1. Angular 20
- Componentes standalone
- Inyección de dependencias moderna
- Guards funcionales

### 2. Seguridad
- Tokens de sesión únicos
- Verificación de expiración
- Limpieza automática de datos

### 3. UX/UI
- Diseño responsivo
- Mensajes de estado claros
- Transiciones suaves

## Solución de Problemas

### 1. Error de Autenticación
- Verificar conexión al backend
- Revisar logs del servidor
- Limpiar localStorage y reintentar

### 2. Sesión Expirada
- Login automático requerido
- Mensaje informativo para el usuario
- Redirección automática

### 3. Problemas de Navegación
- Verificar configuración de rutas
- Comprobar implementación de guards
- Revisar consola del navegador

## Mantenimiento

### 1. Actualizaciones
- Mantener dependencias actualizadas
- Revisar logs de seguridad
- Monitorear uso de tokens

### 2. Monitoreo
- Verificar sesiones activas
- Revisar logs de autenticación
- Monitorear rendimiento del sistema

---

**Nota**: Este sistema está diseñado para ser seguro y fácil de usar. Para cualquier modificación o consulta, contactar al equipo de desarrollo. 