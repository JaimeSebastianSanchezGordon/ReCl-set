# Spec Kit — ReClóset

## Resumen ejecutivo

ReClóset es una aplicación web para gestionar y compartir prendas de vestir (garments), conversaciones entre usuarios y mensajería asociada. Permite crear, listar y buscar prendas, mantener conversaciones y mensajes entre usuarios, y gestionar perfiles. El backend está implementado en PHP con un framework estilo Laravel; el frontend usa Vite y assets en resources/js y resources/css.

## Tabla de contenidos

- Resumen ejecutivo
- Contexto y motivación
- Objetivos y no-objetivos
- Usuarios y personas
- Casos de uso clave
- Arquitectura del sistema
- Modelos de datos principales
- API / Endpoints principales
- Flujo y vistas principales (UI)
- Seguridad y privacidad
- Despliegue y operaciones
- Pruebas y calidad
- Métricas de éxito
- Roadmap y hitos
- Cómo contribuir

## Contexto y motivación

Las personas suelen necesitar una forma sencilla de catalogar prendas, organizar su armario digital y comunicarse sobre intercambios o ventas. ReClóset busca ofrecer una experiencia simple para: crear catálogos de prendas, permitir interacción entre usuarios (conversaciones y mensajes) y facilitar búsquedas y filtros por atributos de prenda.

## Objetivos

- Permitir crear, editar y eliminar prendas (garments).
- Soportar conversaciones entre usuarios y mensajería en tiempo razonable.
- Exponer una API REST limpia para integración con clientes SPA o móviles.
- Ofrecer un frontend responsivo con Vite.

No-objetivos (por ahora): motor avanzado de recomendaciones, pago/checkout, integración con terceros (salvo que se acuerde).

## Usuarios y personas

- Usuario general: crea prendas, busca por atributos, inicia conversaciones.
- Vendedor/intercambiador: usuario activo que publica muchas prendas y gestiona conversaciones.
- Administrador: gestiona usuarios y contenido inapropiado.

## Casos de uso clave

1. Registrar y editar una prenda con imágenes y etiquetas.
2. Listar prendas y filtrar por categoría, talla o color.
3. Iniciar una conversación con otro usuario sobre una prenda.
4. Enviar y recibir mensajes dentro de una conversación.
5. Borrar o reportar contenido inapropiado.

## Arquitectura del sistema

- Backend: PHP (estilo Laravel), rutas en `routes/`, controladores en `app/Http/Controllers/`.
- Frontend: Vite con assets en `resources/js` y `resources/css`.
- Persistencia: base de datos relacional (migrations en `database/migrations/`).
- Tests: PHPUnit (`phpunit.xml`).

Diagrama (alto nivel):

- Cliente (SPA/Vite) ↔ API REST (PHP) → Base de datos

## Modelos de datos principales

- `User`
  - id, name, email, password, metadata
- `Garment` (prenda)
  - id, user_id, title, description, category, size, color, images, created_at
  - Migración: `2026_05_02_000001_create_garments_table.php`
- `Conversation`
  - id, subject, participant_a_id, participant_b_id, last_message_at
  - Migración: `2026_05_02_000002_create_conversations_table.php`
- `Message`
  - id, conversation_id, sender_id, body, read_at, created_at
  - Migración: `2026_05_02_000003_create_messages_table.php`

## API / Endpoints principales (propuesta)

- Autenticación: `POST /api/login`, `POST /api/register`, `POST /api/logout`
- Prendas:
  - `GET /api/garments` — listar y filtrar
  - `GET /api/garments/{id}` — detalle
  - `POST /api/garments` — crear
  - `PUT /api/garments/{id}` — actualizar
  - `DELETE /api/garments/{id}` — eliminar
- Conversaciones y mensajes:
  - `GET /api/conversations` — lista de conversaciones del usuario
  - `POST /api/conversations` — iniciar conversación
  - `GET /api/conversations/{id}/messages` — listar mensajes
  - `POST /api/conversations/{id}/messages` — enviar mensaje

Nota: implementar control de acceso para que solo participantes accedan a conversaciones y mensajes.

## Flujo y vistas principales (UI)

- Home / Feed: lista de prendas recientes o destacadas (`resources/views/index.blade.php`).
- Página de prenda: ficha con imágenes y botón para iniciar conversación.
- Perfil de usuario: listado de prendas del usuario.
- Bandeja de mensajes: listados de conversaciones y vista de mensajes.

## Seguridad y privacidad

- Autenticación segura (hash de contraseñas). Evitar exponer datos sensibles en APIs.
- Control de acceso por recurso (policy/guards) para evitar lecturas/escrituras no autorizadas.
- Sanitización de entradas y límites de tamaño para uploads de imágenes.

## Despliegue y operaciones

- Build frontend: `npm run build` (configurado en `package.json` / `vite.config.js`).
- Migraciones: `php artisan migrate` o el comando equivalente del framework.
- Entorno: variables en `.env` para DB, mail y servicios.

## Pruebas y calidad

- Tests unitarios y de integración con PHPUnit (`tests/`).
- Validación de contratos API y casos de uso críticos (crear prenda, iniciar conversación, enviar mensaje).

## Métricas de éxito

- Tiempo medio de carga de feed < 500ms.
- Tasa de entrega de mensajes (sin errores) > 99%.
- Tasa de retención de usuarios activos semana a semana.

## Roadmap y próximos hitos

1. MVP: CRUD de prendas + conversaciones básicas + autenticación.
2. Mejoras UX: subida de imágenes progresiva y filtros avanzados.
3. Moderación: reports y panel administrativo.

## Cómo contribuir

- Clona el repo y crea una rama con la convención `feature/descripcion`.
- Ejecuta migraciones locales y pruebas: `composer install`, `npm install`, `php artisan migrate`, `./vendor/bin/phpunit`.
- Abre PR con descripción de cambios y screenshots si aplica.

## Contacto

- Equipo propietario del repo: revisar `composer.json` y `README.md` para info de maintainers.

---

_Este documento sigue el formato Spec Kit: provee contexto, objetivos, arquitectura, API y roadmap para guiar implementación y discusiones._
