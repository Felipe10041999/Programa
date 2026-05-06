# Sistema de Gestión Interna NGSO

Sistema web centralizado para la gestión interna de NGSO que automatiza procesos manuales, administra usuarios y genera informes de productividad de forma automática.

## 📋 Tabla de Contenidos

- [Descripción](#-descripción)
- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Módulos](#-módulos)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Capturas de Pantalla](#-capturas-de-pantalla)
- [Solución de Problemas](#-solución-de-problemas)
- [Contribución](#-contribución)
- [Autor](#-autor)
- [Licencia](#-licencia)

## 📖 Descripción

El Sistema de Gestión Interna NGSO es una plataforma web desarrollada para reemplazar procesos manuales basados en archivos Excel. El sistema centraliza la gestión de usuarios, operaciones de agentes y la generación automática de informes, eliminando tareas manuales propensas a errores y optimizando los tiempos de respuesta.

### Problemas que resuelve:
- ✅ Dispersión de información en múltiples archivos Excel
- ✅ Riesgo de errores humanos en manipulación manual de datos
- ✅ Procesos lentos de elaboración de informes
- ✅ Falta de centralización y trazabilidad

## ✨ Características

- 🔐 **Autenticación segura** con JWT
- 👥 **Gestión completa de agentes** (CRUD)
- 💻 **Administración de credenciales** de equipos, Huella y BestVoIper
- 📊 **Generación automática de informes** por hora, logueo y gestiones
- 📈 **Reportes de productividad** en tiempo real
- 🔄 **Procesamiento automático** de archivos Excel/CSV
- 📱 **Interfaz responsive** optimizada para múltiples dispositivos
- 🌐 **Acceso multi-usuario** en red local

## 🛠 Tecnologías

### Backend
- **Laravel** 12.20.0
- **PHP** 8.2
- **Composer** 2.8.10
- **MySQL** 10.4.32

### Frontend
- **Angular** 20.0.5
- **Node.js** 22.17.0
- **TypeScript**
- **Tailwind CSS** (utilidades core)

### Herramientas de Desarrollo
- **XAMPP** 8.2 (Apache + PHP + MySQL)
- **Git** 2.45+
- **MySQL Workbench**
- **Visual Studio Code**

## 💻 Requisitos del Sistema

### Servidor (Mínimo / Recomendado)

| Componente | Mínimo | Recomendado |
|-----------|--------|-------------|
| **Procesador** | Dual-core 2.5 GHz | Quad-core 3.0 GHz |
| **RAM** | 8 GB | 16 GB |
| **Almacenamiento** | 120 GB HDD | 240 GB SSD |
| **Sistema Operativo** | Windows 10 | Windows 11 Pro |
| **Red** | Ethernet 100 Mbps | Gigabit Ethernet |

### Estaciones Cliente

| Componente | Mínimo | Recomendado |
|-----------|--------|-------------|
| **Procesador** | Dual-core 2 GHz | Quad-core 3 GHz |
| **RAM** | 4 GB | 8 GB |
| **Almacenamiento** | 10 GB | 20 GB SSD |
| **Navegador** | Chrome 90+ | Chrome 120+ |

## 📥 Instalación

### 1. Prerequisitos

Asegúrate de tener instalado:

- [XAMPP 8.2](https://www.apachefriends.org)
- [Node.js 22.17.0](https://nodejs.org)
- [Composer 2.8.10](https://getcomposer.org)
- [Git 2.45+](https://git-scm.com)
- [MySQL Workbench](https://dev.mysql.com/downloads/workbench/)

### 2. Clonar el Repositorio

```bash
git clone https://github.com/Felipe10041999/Programa.git
cd Programa
```

### 3. Configurar Backend (Laravel)

```bash
cd Programa-backend

# Instalar dependencias
composer install

# Copiar archivo de entorno
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

**Configuración del archivo `.env`:**

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:vsP4rCxkn4NdkBwmnBmADzksLz66ni+7wGezcuQhEVY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=registros
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Configurar Base de Datos

1. Abrir MySQL Workbench
2. Crear conexión al puerto 3306
3. Ejecutar:

```sql
CREATE DATABASE registros;
USE registros;
```

4. Importar el archivo SQL desde: `Programa/Base de datos/Base de datos.sql`
   - File → Open SQL Script
   - Seleccionar el archivo
   - Ejecutar script completo

### 5. Configurar Frontend (Angular)

```bash
cd ../Programa-front

# Instalar dependencias
npm install
```

### 6. Configurar CORS

Editar `backend/app/Http/config/cors.php`:

```php
'allowed_origins' => [
    'http://192.168.1.10:4200',
    'http://192.168.1.10'
],
```

*(Ajustar según la IP de tu servidor)*

Limpiar cache de configuración:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ⚙️ Configuración

### Iniciar Servicios Manualmente

**1. Iniciar MySQL:**
- Abrir panel de control de XAMPP
- Iniciar servicio MySQL

**2. Backend (Laravel):**
```bash
cd Programa-backend
php artisan serve --host=0.0.0.0 --port=8000
```

**3. Frontend (Angular):**
```bash
cd Programa-front
ng serve --host=0.0.0.0 --port=4200
```

### Configurar Inicio Automático (Windows)

Para que los servicios inicien automáticamente al encender el servidor:

1. Abrir **Programador de tareas** como administrador
2. Crear tarea para cada script:

**Para Angular:**
- Nombre: Angular
- Desencadenador: Al iniciar sistema
- Acción: Ejecutar `Programa/scripts/angular.bat`
- Ejecutar con privilegios más altos

**Para Laravel:**
- Nombre: Laravel
- Desencadenador: Al iniciar sistema
- Acción: Ejecutar `Programa/scripts/laravel.bat`
- Ejecutar con privilegios más altos

**Para MySQL:**
- Nombre: Iniciar MySQL
- Desencadenador: Al iniciar sistema
- Acción: Iniciar servicio MySQL desde XAMPP

## 🚀 Uso

### Acceso al Sistema

Una vez iniciados los servicios:

1. **Pantalla principal:** `http://192.168.1.10:4200` (ajustar IP según servidor)
2. **Login:** Usar credenciales configuradas en el sistema

### Flujo de Trabajo

1. **Pantalla de inicio:** 
   - Ingresar cédula para consulta rápida de agente
   - O hacer clic en "Ir a gestiones"

2. **Login:** 
   - Autenticarse con credenciales de administrador

3. **Panel principal:** 
   - Acceder a los diferentes módulos disponibles

## 📦 Módulos

### 1. Gestión de Usuarios

Administración centralizada de información de agentes y credenciales.

- **1.1 Agentes:** CRUD completo de agentes del sistema
- **1.2 Equipos:** Gestión de usuarios y contraseñas de equipos asignados
- **1.3 Huella:** Administración de usuarios del aplicativo Huella
- **1.4 BestVoIper:** Gestión de usuarios de marcaciones BestVoIper

### 2. Informes

Generación automática de informe de gestiones por hora:
- Cargar 3 archivos Excel base
- Procesamiento automático sin filtros manuales
- Descarga de reporte final consolidado

### 3. Hora de Logueo

Procesamiento de archivo de registros de ingreso:
- Hora real de entrada de cada agente
- Cálculo de diferencias con horario establecido
- Tiempo que debe reponerse en caso de llegada tarde

### 4. Gestión Nuevos

Informe de solicitudes nuevas gestionadas:
- Separación por jornada (mañana/tarde)
- Seguimiento de atención de nuevas gestiones

### 5. Gestión Jurídico

Reporte de marcaciones en área jurídica:
- Cantidad de marcaciones por hora
- Total de marcaciones realizadas
- Tiempo total acumulado

### 6. Configuración

Módulo reservado para futuras ampliaciones:
- Ajustes generales del sistema
- Variables de configuración
- Integraciones adicionales

## 📁 Estructura del Proyecto

```
Programa/
├── Programa-backend/          # Backend Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── config/
│   │   └── Models/
│   ├── config/
│   ├── database/
│   ├── routes/
│   ├── storage/
│   ├── .env.example
│   └── composer.json
│
├── Programa-front/            # Frontend Angular
│   ├── src/
│   │   ├── app/
│   │   │   ├── components/
│   │   │   ├── services/
│   │   │   └── modules/
│   │   ├── assets/
│   │   └── environments/
│   ├── angular.json
│   └── package.json
│
├── Base de datos/             # Scripts SQL
│   └── Base de datos.sql
│
└── scripts/                   # Scripts de automatización
    ├── angular.bat
    └── laravel.bat
```

## 📸 Capturas de Pantalla

### Pantalla de Inicio
Verificación rápida de cédula para consulta de agentes.

### Login
Autenticación segura con credenciales de administrador.

### Módulo Principal
Panel central con acceso a todos los módulos del sistema.

### Gestión de Usuarios
CRUD completo de agentes, equipos y credenciales.

### Generación de Informes
Carga de archivos y descarga automática de reportes consolidados.

## 🔍 Verificación de Instalación

### Backend (Laravel)

```bash
# Verificar versión de PHP
php -v

# Verificar versión de Composer
composer --version

# Verificar conexión a base de datos
php artisan migrate:status

# Limpiar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend (Angular)

```bash
# Verificar versión de Node.js
node -v

# Verificar versión de npm
npm -v

# Verificar Angular CLI
ng version

# Compilar proyecto
ng build
```

### Base de Datos (MySQL)

```sql
-- Verificar base de datos
SHOW DATABASES;

-- Usar base de datos
USE registros;

-- Verificar tablas
SHOW TABLES;

-- Verificar datos iniciales
SELECT * FROM usuarios LIMIT 5;
```

### Acceso desde Cliente

Desde un equipo en la misma red local:

```
http://[IP_SERVIDOR]:4200
```

Ejemplo:
```
http://192.168.1.10:4200
```

## 🐛 Solución de Problemas

### Error: Puerto ocupado

**Windows - Verificar qué proceso usa el puerto:**
```bash
# Puerto 4200 (Angular)
netstat -ano | findstr :4200

# Puerto 8000 (Laravel)
netstat -ano | findstr :8000

# Puerto 3306 (MySQL)
netstat -ano | findstr :3306
```

**Liberar puerto:**
```bash
# Matar proceso por PID
taskkill /PID [numero_PID] /F
```

### Error: Composer install falla

```bash
# Ignorar requisitos de plataforma
composer install --ignore-platform-reqs

# Actualizar Composer
composer self-update

# Limpiar cache
composer clear-cache
```

### Error: npm install falla

```bash
# Limpiar cache de npm
npm cache clean --force

# Eliminar node_modules y reinstalar
rmdir /s /q node_modules
del package-lock.json
npm install
```

### Error: CORS - No se puede acceder desde el cliente

1. Verificar configuración en `backend/app/Http/config/cors.php`
2. Agregar IP del servidor a `allowed_origins`
3. Limpiar cache:
```bash
php artisan config:cache
```

### Error: Base de datos no conecta

1. Verificar que MySQL esté corriendo en XAMPP
2. Revisar credenciales en `.env`:
   - DB_HOST=127.0.0.1
   - DB_PORT=3306
   - DB_DATABASE=registros
   - DB_USERNAME=root
   - DB_PASSWORD= (vacío por defecto)

### Error: Angular no compila

```bash
# Actualizar Angular CLI
npm install -g @angular/cli@20.0.5

# Reinstalar dependencias
rm -rf node_modules package-lock.json
npm install
```

## 📝 Notas Importantes

- ⚠️ El sistema funciona **solo en red local** (LAN)
- ⚠️ Requiere **IP estática** para el servidor
- ⚠️ Los archivos de entrada deben cumplir **formatos específicos**
- ⚠️ **No compatible** con Internet Explorer
- ⚠️ Firewalls pueden bloquear puertos necesarios (4200, 8000, 3306)
- ⚠️ Mantener actualizados navegadores en equipos cliente

## 🤝 Contribución

Este es un proyecto interno de NGSO. Para contribuciones o sugerencias:

1. Contactar al área de Tecnología/Desarrollo
2. Reportar problemas o solicitar funcionalidades
3. Seguir las políticas internas de la organización

## 👨‍💻 Autor

**Edgar Felipe Mosquera Rozo**
- Organismo: NGSO
- Proyecto: Programa de gestión interna
- Versión: 0100
- Fecha: 18/11/2025

## 📄 Licencia

Uso interno de NGSO. Todos los derechos reservados.

## 📚 Documentación Adicional

Para más información, consultar:

- **Manual de Instalación:** Documentación completa del proceso de instalación
- [Documentación Laravel](https://laravel.com/docs)
- [Documentación Angular](https://angular.io/docs)
- [Documentación MySQL](https://dev.mysql.com/doc/)
- [Documentación XAMPP](https://www.apachefriends.org/docs/)

## 📞 Soporte

Para soporte técnico o consultas:

- **Área de Tecnología / Desarrollo:** Instalación y configuración
- **Equipo de Soporte Técnico:** Mantenimiento y resolución de incidencias

## 🔄 Historial de Versiones

| Versión | Fecha | Cambios |
|---------|-------|---------|
| 0100 | 18/11/2025 | Versión inicial del sistema |

## ⚡ Quick Start

Para una instalación rápida:

```bash
# 1. Clonar repositorio
git clone https://github.com/Felipe10041999/Programa.git
cd Programa

# 2. Backend
cd Programa-backend
composer install
copy .env.example .env
php artisan key:generate

# 3. Base de datos (en MySQL Workbench)
# CREATE DATABASE registros;
# Importar: Base de datos/Base de datos.sql

# 4. Frontend
cd ../Programa-front
npm install

# 5. Iniciar servicios
# Terminal 1: cd Programa-backend && php artisan serve --host=0.0.0.0
# Terminal 2: cd Programa-front && ng serve --host=0.0.0.0

# 6. Acceder: http://localhost:4200
```

---

**Versión del documento:** 0100  
**Última actualización:** 18/11/2025  
**Estado:** Producción

---

<div align="center">
Desarrollado con ❤️ por Edgar Felipe Mosquera Rozo para NGSO
</div>
