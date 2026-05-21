<?php
/**
 * =============================================================================
 * PUNTO DE ENTRADA PRINCIPAL - index.php
 * =============================================================================
 * Este es el front controller de la aplicación. Toda petición HTTP pasa por
 * aquí gracias a la configuración del .htaccess.
 *
 * Responsabilidades:
 *  1. Iniciar la sesión y aplicar cabeceras anti-caché.
 *  2. Decidir qué vista mostrar según el parámetro GET 'vista' y el rol del usuario.
 *  3. Redirigir al login si no hay sesión activa.
 *  4. Delegar las peticiones POST al Controlador_modulos.
 *  5. Verificar que cada vista solo sea accesible para el rol correcto.
 */

session_start();

// Evitamos que el navegador cachee páginas protegidas.
// Sin esto, al hacer logout el botón "atrás" podría mostrar la página anterior.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Constante global con la URL base del proyecto.
// Se usa en vistas y controladores para construir enlaces y redirecciones.
define('BASE_URL', '/asignaciones');

// Cargamos los modelos necesarios para la mayoría de vistas
require_once __DIR__ . '/modelos/Modelo_profesores.php';
require_once __DIR__ . '/modelos/Modelo_modulos.php';
require_once __DIR__ . '/modelos/Modelo_excel.php';

// ── Protección de sesión ──────────────────────────────────────────────────────
// Si el usuario no ha iniciado sesión, mostramos el formulario de login y paramos.
if (!isset($_SESSION['usuario'])) {
    _mostrarLogin();
    exit();
}

// Leemos la vista solicitada y el rol del usuario autenticado
$vista = $_GET['vista'] ?? '';
$rol   = $_SESSION['rol'];

// ── Manejo de peticiones POST ─────────────────────────────────────────────────
// Todas las acciones POST del panel de administración (subir Excel de profesores
// o módulos) se delegan al controlador correspondiente.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/controladores/Controlador_modulo.php';
    (new Controlador_modulos())->manejarPost();
    exit();
}

// ── Router principal (GET) ────────────────────────────────────────────────────
switch ($vista) {

    case 'admin':
        // Panel de administración: requiere rol 'admin'
        _requiereRol('admin');
        $modeloMod  = new Modelo_modulos();
        $modeloProf = new Modelo_profesores();
        // Obtenemos todos los módulos junto con el nombre del profesor asignado (JOIN)
        $modulos            = $modeloMod->obtenerModulosConProfesor();
        $profesores         = $modeloProf->obtenerProfesores();
        // Resumen de horas totales por profesor (para el panel de carga horaria)
        $profesoresConHoras = $modeloMod->obtenerTodosProfesoresConHoras();
        require __DIR__ . '/vistas/adminPanel.php';
        break;

    case 'asignacion':
        // Vista de asignación de módulos a profesores: solo admin
        _requiereRol('admin');
        $modeloMod  = new Modelo_modulos();
        $modeloProf = new Modelo_profesores();
        $modulos            = $modeloMod->obtenerModulosConProfesor();
        $profesores         = $modeloProf->obtenerProfesores();
        $profesoresConHoras = $modeloMod->obtenerTodosProfesoresConHoras();
        require __DIR__ . '/vistas/asignarModulos.php';
        break;

    case 'profesor':
        // Panel del profesor: solo accesible para el rol 'profesor'
        _requiereRol('profesor');
        $modeloMod = new Modelo_modulos();
        // Cada profesor solo ve sus propios módulos asignados
        $modulos   = $modeloMod->obtenerModulosPorProfesor((int)$_SESSION['profesor_id']);
        require __DIR__ . '/vistas/profesorPanel.php';
        break;

    case 'logout':
        // Cierre de sesión: destruimos todos los datos de sesión y redirigimos al login
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        break;

    default:
        // Si no se especifica vista, redirigir automáticamente al panel correcto según rol
        header('Location: ' . BASE_URL . '/?vista=' . ($rol === 'admin' ? 'admin' : 'profesor'));
        break;
}

// ── Funciones helper ──────────────────────────────────────────────────────────

/**
 * Carga la vista del login pasándole la lista de profesores.
 * Los profesores se necesitan para poblar el desplegable de selección.
 */
function _mostrarLogin(): void {
    $modeloProf = new Modelo_profesores();
    $profesores = $modeloProf->obtenerProfesores();
    require __DIR__ . '/vistas/login.php';
}

/**
 * Verifica que el usuario tenga el rol necesario para acceder a una vista.
 * Si no lo tiene, redirige al panel que le corresponde por su rol actual.
 *
 * @param string $rolNecesario  El rol requerido ('admin' o 'profesor')
 */
function _requiereRol(string $rolNecesario): void {
    if ($_SESSION['rol'] !== $rolNecesario) {
        $destino = $_SESSION['rol'] === 'admin' ? 'admin' : 'profesor';
        header('Location: ' . BASE_URL . '/?vista=' . $destino);
        exit();
    }
}
