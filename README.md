# Organizador de Metodos de Cobro

Aplicacion web en PHP + MySQL para organizar y compartir de forma simple las cuentas, billeteras y enlaces donde una persona puede recibir dinero.

La app no procesa pagos. Su objetivo es centralizar informacion de cobro en un perfil publico facil de consultar desde movil o escritorio.

## Funcionalidades

- Registro e inicio de sesion.
- Panel privado para administrar perfil, cuentas bancarias, billeteras cripto y metodos online.
- Perfil publico compartible por numero de telefono.
- Copia rapida de numeros de cuenta y direcciones.
- Enlaces externos para servicios y plataformas de cobro.
- Subida de imagen de perfil y de imagenes para servicios.

## Stack

- PHP 8.2
- MySQL 8
- Apache
- HTML, CSS y JavaScript vanilla
- Docker y Docker Compose para entorno local

## Estructura del proyecto

```text
banco/
|-- includes/
|   |-- bootstrap.php
|   |-- app_data.php
|   |-- layout.php
|   |-- auth_components.php
|   |-- profile_components.php
|   `-- dashboard_components.php
|-- scripts/
|   |-- auth.js
|   |-- dashboard.js
|   |-- profile-ui.js
|   `-- site-nav.js
|-- styles/
|   |-- app-shell.css
|   |-- auth.css
|   |-- dashboard.css
|   `-- style.css
|-- imagen/
|-- uploads/
|-- index.php
|-- login.php
|-- perfildecuentas.php
|-- auth.php
|-- procesar.php
|-- buscar_telefono.php
|-- db.php
|-- helpers.php
|-- perfil_app.sql
|-- Dockerfile
`-- docker-compose.yml
```

## Flujo principal

### Acceso

- `login.php`: pantalla de acceso y registro.
- `auth.php`: procesa login y registro por AJAX.
- Si el usuario ya tiene sesion activa, `login.php` redirige a `index.php`.

### Panel privado

- `index.php`: dashboard del usuario autenticado.
- Permite editar perfil, agregar servicios, cuentas, wallets y pagos online.
- Usa `procesar.php` para guardar y eliminar registros.

### Perfil publico

- `perfildecuentas.php?numero=8091234567`
- Muestra la informacion compartible del usuario encontrada por numero.

## Base de datos

El archivo principal de base de datos es:

- `perfil_app.sql`

Ese script crea la base `app_db` con las tablas:

- `usuarios`
- `servicios`
- `cuentas_bancarias`
- `cripto_wallets`
- `pagos_online`

Tambien incluye datos de ejemplo.

## Variables de entorno

La conexion a MySQL se toma desde estas variables:

```env
DB_HOST=db
DB_DATABASE=app_db
DB_USERNAME=app_user
DB_PASSWORD=secret
```

Si no se definen manualmente, la app usa esos mismos valores por defecto.

## Ejecutar con Docker

### 1. Levantar contenedores

```bash
docker-compose up --build
```

### 2. Importar la base de datos

Cuando el contenedor de MySQL este listo, importa `perfil_app.sql` dentro de `app_db`.

Ejemplo:

```bash
docker exec -i mysql_db mysql -uapp_user -psecret app_db < perfil_app.sql
```

### 3. Abrir la aplicacion

```text
http://localhost:8080/login.php
```

## Ejecutar en entorno local sin Docker

Requisitos:

- PHP 8+
- MySQL 8+
- Apache o servidor local tipo WAMP/XAMPP

Pasos generales:

1. Crea una base de datos `app_db`.
2. Importa `perfil_app.sql`.
3. Ajusta `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` si tu entorno lo requiere.
4. Sirve el proyecto desde tu servidor local.
5. Abre `login.php`.

## Archivos importantes

- `db.php`: conexion a la base de datos.
- `helpers.php`: utilidades comunes.
- `includes/layout.php`: layout global, navbar, footer y favicon.
- `styles/app-shell.css`: estilos globales compartidos.
- `styles/auth.css`: estilos de acceso.
- `styles/style.css`: estilos del perfil y panel principal.
- `styles/dashboard.css`: estilos del dashboard y modales auxiliares.

## Assets

- `uploads/`: imagenes subidas por usuarios y placeholders.
- `imagen/`: iconos e imagenes base del sistema.
- `favicon.svg`: favicon principal del sitio.

## Notas de desarrollo

- La app esta modularizada por componentes PHP para facilitar mantenimiento.
- El frontend esta hecho sin framework JS.
- El diseno responsive se resuelve con CSS propio, sin Bootstrap ni Tailwind.
- `login.html` se mantiene como compatibilidad, pero la entrada real es `login.php`.

## Posibles mejoras futuras

- Persistir configuraciones visuales del usuario en la base de datos.
- Agregar validaciones mas robustas del lado cliente y servidor.
- Incorporar pruebas automatizadas.
- Separar aun mas la logica de negocio del render PHP.
- Agregar pagina 404 y manejo de errores mas amigable.

## Licencia

Este proyecto incluye un archivo `LICENSE` en la raiz del repositorio.
