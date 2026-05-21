# Sistema de Asignación de Módulos — TFJ Kevin / Edu / Vic

Aplicación web PHP para gestionar la asignación de módulos docentes a los profesores de un departamento de Formación Profesional. Permite al administrador importar datos desde Excel, visualizar la carga horaria por profesor y realizar asignaciones de forma interactiva. Los profesores pueden consultar sus módulos asignados y gestionar su contraseña de acceso.

---

## Índice

1. [Tecnologías usadas](#tecnologías-usadas)
2. [Estructura del proyecto](#estructura-del-proyecto)
3. [Base de datos](#base-de-datos)
4. [Instalación y configuración](#instalación-y-configuración)
5. [Flujo de la aplicación](#flujo-de-la-aplicación)
6. [Roles y permisos](#roles-y-permisos)
7. [Importación de datos desde Excel](#importación-de-datos-desde-excel)
8. [Endpoints AJAX](#endpoints-ajax)
9. [Seguridad](#seguridad)
10. [Archivos relevantes](#archivos-relevantes)

---

## Tecnologías usadas

| Capa | Tecnología |
|------|-----------|
| Servidor | PHP 8.2+ |
| Base de datos | MySQL / MariaDB |
| Acceso a BD | PDO (Prepared Statements) |
| Lectura de Excel | PhpSpreadsheet (Composer) |
| Frontend | HTML5, CSS3, JavaScript vanilla |
| Servidor web | Apache con mod_rewrite |

---

## Estructura del proyecto

```
/
├── index.php                          # Front controller: enruta todas las peticiones
├── hash.php                           # Utilidad de desarrollo para generar hashes bcrypt
├── .htaccess                          # Redirige todo al index.php (mod_rewrite)
│
├── core/
│   ├── BaseDatos.php                  # Singleton de conexión PDO a MySQL
│   └── cerrar_sesion.php              # Script alternativo de logout
│
├── controladores/
│   ├── Controlador_login.php          # Autenticación de admin y profesor
│   ├── Controlador_modulo.php         # Subida de Excel (profesores y módulos)
│   ├── Controlador_asignarMod.php     # AJAX: asignar/desasignar módulos a un profesor
│   ├── Controlador_obtenerModulosProfesor.php  # AJAX: obtener módulos con estado de asignación
│   ├── Controlador_cambiarPassword.php         # Cambio de contraseña del admin
│   ├── Controlador_setPasswordProfesor.php     # Establecer/cambiar contraseña del profesor
│   ├── Controlador_checkPasswordProfesor.php   # AJAX: saber si un profesor tiene contraseña
│   └── Controlador_eliminar_datos.php          # Borrado masivo de profesores o módulos
│
├── modelos/
│   ├── BaseDatos.php → (en core/)
│   ├── Modelo_usuarios.php            # CRUD tabla 'usuarios' (credenciales)
│   ├── Modelo_profesores.php          # CRUD tabla 'profesores'
│   ├── Modelo_modulos.php             # CRUD tabla 'modulos' + lógica de asignación
│   └── Modelo_excel.php               # Lectura de archivos .xlsx con PhpSpreadsheet
│
├── vistas/
│   ├── login.php                      # Formulario de login (admin y profesor)
│   ├── adminPanel.php                 # Panel principal del administrador
│   ├── asignarModulos.php             # Vista interactiva de asignación de módulos
│   ├── mostrarModulos.php             # Vista de listado de módulos
│   ├── profesorPanel.php              # Panel del profesor
│   └── estilos/
│       ├── login.css
│       ├── admin.css
│       ├── asignarModulos.css
│       ├── profesor.css
│       ├── darkmode.css
│       └── darkmode.js                # Toggle modo oscuro
│
└── Recursos/
    ├── tfg_instituto.sql              # Script SQL para crear e importar la BD
    ├── asignaciones_corregido.sql     # Script SQL alternativo/corregido
    ├── DetalleModulos.xlsx            # Ejemplo de Excel de módulos
    ├── Profesores.xlsx                # Ejemplo de Excel de profesores
    ├── plantilla_oficial.pdf          # Plantilla oficial del proyecto
    ├── composer.json                  # Dependencias PHP (PhpSpreadsheet)
    └── vendor/                        # Librerías instaladas por Composer
```

---

## Base de datos

### Diagrama de tablas

```
profesores                   modulos                      usuarios
──────────────               ──────────────               ──────────────
orden  (PK, AI)  ◄──┐        id            (PK, AI)       id      (PK, AI)
nombre           │  └── FK   profesor_id   (FK, nullable) usuario (UNIQUE)
categoria        │           nombre_modulo                password (bcrypt)
departamento     │           grado                        rol      (enum)
                 │           curso                        profesor_id (FK, nullable)
                 │           horas                                  │
                 │           categoria                              │
                 │                                                  │
                 └──────────────────────────────────────────────────┘
```

### Descripción de las tablas

**`profesores`**: Almacena los datos de cada docente. La clave primaria `orden` es el ID que se usa como referencia en el resto de tablas.

**`modulos`**: Cada fila es un módulo/asignatura de un ciclo formativo. La columna `profesor_id` es una FK nullable que indica qué profesor lo imparte (`NULL` = sin asignar).

**`usuarios`**: Almacena las credenciales de acceso. El administrador tiene `profesor_id = NULL`. Cada profesor tiene un registro en esta tabla con su `profesor_id`. Las contraseñas se almacenan siempre como hashes bcrypt.

### Importar la base de datos

```bash
mysql -u root -p < Recursos/tfg_instituto.sql
```

El script crea las tres tablas con sus índices y claves foráneas, e inserta datos de ejemplo (módulos del departamento de Informática y la cuenta del administrador).

**Credenciales del admin por defecto:**
- Usuario: `admin`
- Contraseña: `1234`

---

## Instalación y configuración

### Requisitos

- PHP 8.1 o superior con extensiones: `pdo_mysql`, `mbstring`, `zip`
- Apache con `mod_rewrite` habilitado
- MySQL / MariaDB 10.4+
- Composer (para gestionar dependencias PHP)

### Pasos

1. **Clonar o copiar el proyecto** en el directorio web de Apache (p. ej. `/var/www/html/asignaciones/`).

2. **Instalar dependencias PHP** (si no se incluye la carpeta `vendor`):
   ```bash
   cd Recursos/
   composer install
   ```

3. **Importar la base de datos**:
   ```bash
   mysql -u root -p < Recursos/tfg_instituto.sql
   ```

4. **Configurar la conexión a la BD** editando `core/BaseDatos.php`:
   ```php
   private const HOST = 'tu_servidor';    // IP o hostname del servidor MySQL
   private const USER = 'tu_usuario';     // Usuario de la BD
   private const PASS = 'tu_password';    // Contraseña
   private const DB   = 'asignaciones';   // Nombre de la BD
   ```

5. **Configurar Apache**: Asegúrate de que el virtualhost tiene `AllowOverride All` para que el `.htaccess` funcione correctamente.

6. **Verificar permisos**: El servidor web necesita permisos de escritura en la carpeta temporal de PHP para la subida de archivos.

7. **Acceder a la aplicación**: `http://tu-servidor/asignaciones/`

---

## Flujo de la aplicación

### Diagrama general

```
Usuario accede a /asignaciones/
        │
        ▼
  ¿Tiene sesión? ──No──► Login (login.php)
        │                     │
        │Yes              Admin / Profesor
        │                     │
        ▼                     ▼
  index.php           Controlador_login.php
  (switch $vista)           │
   ┌────────────┐           │ Sesión iniciada
   │ admin      │◄──────────┘
   │ asignacion │
   │ profesor   │
   │ logout     │
   └────────────┘
```

### Login de administrador

1. El admin introduce usuario y contraseña en el formulario.
2. `Controlador_login.php` verifica las credenciales contra la tabla `usuarios` usando `password_verify()`.
3. Si son correctas, guarda `rol = 'admin'` en sesión y redirige al panel admin.

### Login de profesor

1. El profesor selecciona su nombre en el desplegable.
2. Por AJAX se consulta `Controlador_checkPasswordProfesor.php` para saber si tiene contraseña configurada.
3. Si tiene contraseña, se muestra el campo de contraseña; si no, puede acceder directamente.
4. `Controlador_login.php` verifica (o crea el usuario si es la primera vez) y guarda la sesión.

### Panel de administrador

- Visualiza todos los módulos con su profesor asignado.
- Muestra la carga horaria total de cada profesor (con aviso visual si supera 20h).
- Permite subir Excel para importar profesores o módulos masivamente.
- Permite eliminar todos los profesores o todos los módulos.
- Permite cambiar su propia contraseña.
- Accede a la vista de asignación interactiva.

### Vista de asignación de módulos

1. El admin selecciona un profesor en el desplegable.
2. Por AJAX se cargan todos los módulos con su estado: "asignado a este profesor", "asignado a otro" (bloqueado) o "libre".
3. El admin marca/desmarca módulos y pulsa Guardar.
4. `Controlador_asignarMod.php` procesa los cambios: asigna los nuevos y desasigna los que se quitaron.
5. Si el total de horas supera 20, se muestra una advertencia (pero se guarda igualmente).

### Panel del profesor

- El profesor ve únicamente sus módulos asignados con el grado, curso y horas.
- Puede establecer o cambiar su contraseña de acceso.

---

## Roles y permisos

| Acción | Admin | Profesor |
|--------|-------|---------|
| Ver panel admin | ✅ | ❌ |
| Importar Excel profesores | ✅ | ❌ |
| Importar Excel módulos | ✅ | ❌ |
| Asignar módulos a profesores | ✅ | ❌ |
| Eliminar profesores/módulos | ✅ | ❌ |
| Cambiar contraseña propia | ✅ | ✅ |
| Ver sus módulos asignados | ❌ | ✅ |

La verificación de rol se realiza en dos niveles:
1. En `index.php` mediante la función `_requiereRol()` para las vistas.
2. En cada controlador AJAX/POST de forma independiente.

---

## Importación de datos desde Excel

### Excel de profesores (`Profesores.xlsx`)

| Columna A | Columna B | Columna C |
|-----------|-----------|-----------|
| (cualquier cosa) | Nombre completo | Categoría (PS/PT) |
| — | Ana García López | PS |

La primera fila se ignora (se considera cabecera). Se importan solo profesores cuyo nombre no exista ya en la BD.

### Excel de módulos (`DetalleModulos.xlsx`)

| Columna A | Columna B | Columna C | Columna D | Columna E |
|-----------|-----------|-----------|-----------|-----------|
| Grado | Curso | Nombre módulo | Horas | Categoría |
| DAW | 1 | Programación | 8 | INF |

La primera fila se ignora. Las filas sin nombre de módulo también se ignoran.

---

## Endpoints AJAX

Todos los endpoints AJAX devuelven JSON con al menos el campo `ok` (boolean).

| Endpoint | Método | Acceso | Descripción |
|----------|--------|--------|-------------|
| `controladores/Controlador_checkPasswordProfesor.php` | GET | Público | Comprueba si un profesor tiene contraseña configurada |
| `controladores/Controlador_obtenerModulosProfesor.php` | GET | Admin | Devuelve módulos con estado de asignación para un profesor |
| `controladores/Controlador_asignarMod.php` | POST (JSON) | Admin | Guarda la asignación de módulos a un profesor |

---

## Seguridad

- **Contraseñas**: Siempre almacenadas como hashes bcrypt (`PASSWORD_DEFAULT`). Nunca en texto plano.
- **Sesiones**: Se verifican en cada controlador y vista. Sin sesión válida → redirección al login.
- **Verificación de rol**: Doble comprobación en `index.php` y en cada controlador.
- **Cabeceras anti-caché**: Evitan que el navegador muestre páginas protegidas tras el logout.
- **Prepared statements**: Todas las consultas SQL usan PDO con `prepare()` / `execute()` para prevenir inyección SQL.
- **Listado de directorios**: Desactivado en `.htaccess` con `Options -Indexes`.
- **hash.php**: Solo debe estar accesible durante el desarrollo. Debe eliminarse o protegerse en producción.

---

## Archivos relevantes

| Archivo | Descripción |
|---------|-------------|
| `core/BaseDatos.php` | Único punto de conexión a la BD. Editar aquí las credenciales. |
| `Recursos/tfg_instituto.sql` | Script para crear la BD desde cero con datos de ejemplo. |
| `Recursos/composer.json` | Define la dependencia de PhpSpreadsheet. |
| `Recursos/DetalleModulos.xlsx` | Plantilla Excel de módulos para importar. |
| `Recursos/Profesores.xlsx` | Plantilla Excel de profesores para importar. |
| `hash.php` | Genera un hash bcrypt de '1234'. Útil para restablecer la contraseña del admin. |
