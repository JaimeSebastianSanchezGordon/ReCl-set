# Proyecto de Intercambio de Ropa

Una plataforma integral diseñada para facilitar el intercambio de prendas entre usuarios, integrando comunicación en tiempo real y una arquitectura moderna basada en Laravel.

## Stack Tecnológico

- Backend: Laravel 13 y PHP 8.3+
- Frontend: Blade y Vite
- Base de datos: Supabase (PostgreSQL)
- Tiempo real: Laravel Reverb (WebSockets)

## Estructura del Proyecto

El proyecto sigue una arquitectura de monolito organizada de la siguiente manera:

- Vistas: localizadas en `resources/views/index.blade.php`
- Rutas: definidas en `routes/index.php`
- Infraestructura: configuración lista para despliegue y persistencia en la nube con Supabase

## Modelo de Datos

| Categoría | Tablas |
| --- | --- |
| Usuarios | `users`, `password_reset_tokens`, `sessions` |
| Inventario | `garments` |
| Mensajería | `conversations`, `messages` |

## Módulos Principales

### Sistema de Autenticación

Flujo completo de gestión de usuarios:

- Registro e inicio de sesión
- Recuperación y restablecimiento de contraseñas

### Gestión de Prendas (CRUD)

Panel central para el intercambio de ropa que permite:

- Publicar: crear nuevas ofertas de prendas
- Explorar: listar y consultar detalles de artículos disponibles
- Gestionar: editar o eliminar publicaciones propias

### Chat en Tiempo Real

Sistema de mensajería instantánea para coordinar intercambios, potenciado por Laravel Reverb para actualizaciones sin recargar la página.

## Configuración del Entorno

### Requisitos Previos

- PHP 8.3 o superior, con la extensión `pdo_pgsql` activa
- Composer
- Node.js y npm
- Proyecto activo en Supabase

### Instalación y Setup

Clona el repositorio y configura las variables de entorno. Asegúrate de que tu archivo `.env` contenga las credenciales de tu instancia de Supabase:

```env
DB_CONNECTION=pgsql
DB_HOST=tu-host-de-supabase
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu-password
DB_SSLMODE=require
```

Prepara el entorno de Laravel:

```bash
composer install
php artisan config:clear
php artisan migrate
```

### Ejecutar la Aplicación

Para que el sistema funcione completamente, necesitas ejecutar los siguientes servicios en terminales separadas:

- Servidor web: `php artisan serve`
- Compilador frontend: `npm run dev`
- Servidor WebSockets: `php artisan reverb:start`

Acceso: visita `http://127.0.0.1:8000`

## Comandos de Utilidad

| Comando | Descripción |
| --- | --- |
| `php artisan migrate:fresh` | Recrea la base de datos desde cero y borra los datos previos |
| `php artisan tinker` | Entorno interactivo para probar modelos y consultas |
| `php artisan reverb:start` | Inicia el servidor para el chat en tiempo real |
