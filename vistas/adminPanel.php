<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?vista=login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="w-full px-6 py-6">

    <!-- HEADER CON TÍTULO Y BOTÓN ASIGNACIÓN -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Panel Administrador</h1>
        
<!--ADAPTEN SU RUTAS PARA QUE LES FUNCIONE AQUI POR EJEMPLO-->
        <!-- ACCESO DIRECTO A ASIGNACIÓN -->
        <a href="./index.php?vista=asignacion" 
           class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg shadow transition font-semibold">
            Ir a Asignación de módulos →
        </a>
    </div>

    <!-- MENSAJE -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="bg-green-500 text-white p-4 mb-6 rounded">
            <?= $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- ERROR -->
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-500 text-white p-4 mb-6 rounded">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- BOTONES SUPERIORES -->
    <div class="flex gap-4 mb-6">
        <form action="./core/cerrar_sesion.php" method="post">
            <button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow transition">
                Cerrar sesión
            </button>
        </form>
        
        <!-- BOTÓN ELIMINAR DATOS -->
        <button id="btnEliminarDatos" 
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow transition">
            🗑️ Eliminar todos los datos
        </button>
    </div>

    <!-- GRID PRINCIPAL -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full min-h-[80vh]">

        <!-- PROFESORES -->
        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-xl font-bold mb-4">Profesores</h2>

            <?php if(isset($profesores) && count($profesores) > 0): ?>

                <p class="text-green-600 mb-4">
                    Hay <?= count($profesores) ?> profesores cargados.
                </p>

            <?php else: ?>

                <p class="text-gray-500 mb-4">
                    No hay profesores cargados.
                </p>

                <form method="POST" enctype="multipart/form-data" class="mb-4">
                    <input type="file" name="archivo_profesores" required class="mb-3">

                    <button type="submit" name="subir_profesores"
                        class="bg-orange-500 text-white px-4 py-2 rounded">
                        Subir profesores
                    </button>
                </form>

            <?php endif; ?>

            <!-- TABLA -->
            <?php if(isset($profesores) && count($profesores) > 0): ?>

            <div class="overflow-auto max-h-[60vh]">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Orden</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Categoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($profesores as $profesor): ?>
                        <tr class="border-t">
                            <td class="p-2"><?= $profesor['orden'] ?></td>
                            <td class="p-2"><?= $profesor['nombre'] ?></td>
                            <td class="p-2"><?= $profesor['categoria'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php else: ?>
                <p class="text-gray-500">No hay profesores aún.</p>
            <?php endif; ?>

        </div>

        <!-- MÓDULOS -->
        <div class="bg-white p-6 rounded shadow w-full h-full">

            <h2 class="text-xl font-bold mb-4">Módulos</h2>

            <?php if(isset($modulos) && count($modulos) > 0): ?>

                <p class="text-green-600 mb-4">
                    Hay <?= count($modulos) ?> módulos cargados.
                </p>

            <?php else: ?>

                <p class="text-gray-500 mb-4">
                    No hay módulos cargados.
                </p>

                <form method="POST" enctype="multipart/form-data" class="mb-4">
                    <input type="file" name="archivo_modulos" required class="mb-3">

                    <button type="submit" name="subir_modulos"
                        class="bg-orange-500 text-white px-4 py-2 rounded">
                        Subir módulos
                    </button>
                </form>

            <?php endif; ?>

            <!-- TABLA -->
            <?php if(isset($modulos) && count($modulos) > 0): ?>

            <div class="overflow-auto max-h-[60vh]">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID</th>
                            <th class="p-2">Ciclo</th>
                            <th class="p-2">Nombre módulo</th>
                            <th class="p-2">Horas</th>
                            <th class="p-2">Profesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($modulos as $modulo): ?>
                        <tr class="border-t">
                            <td class="p-2"><?= $modulo['id'] ?></td>
                            <td class="p-2"><?= htmlspecialchars($modulo['grado'] ?? '') ?></td>
                            <td class="p-2"><?= htmlspecialchars($modulo['nombre_modulo']) ?></td>
                            <td class="p-2"><?= $modulo['horas'] ?>h</td>
                            <td class="p-2"><?= $modulo['profesor_nombre'] ?? 'Sin asignar' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php else: ?>
                <p class="text-gray-500">No hay módulos aún.</p>
            <?php endif; ?>

        </div>

    </div>

</div>

<!-- MODAL DE CONFIRMACIÓN -->
<div id="modalConfirmacion" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <h3 class="text-xl font-bold mb-4 text-red-600">⚠️ Confirmar eliminación</h3>
        <p class="mb-6">¿Estás seguro de que quieres eliminar TODOS los profesores y módulos?<br>
        <span class="text-red-500 font-semibold">Esta acción no se puede deshacer.</span></p>
        <div class="flex justify-end gap-3">
            <button id="btnCancelar" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded transition">
                Cancelar
            </button>
            <a id="btnConfirmarEliminar" href="./controladores/Controlador_eliminar_datos.php" 
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                Sí, eliminar todo
            </a>
        </div>
    </div>
</div>




<script>
const btnEliminar = document.getElementById('btnEliminarDatos');
const modal = document.getElementById('modalConfirmacion');
const btnCancelar = document.getElementById('btnCancelar');

if(btnEliminar) {
    btnEliminar.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });
}

if(btnCancelar) {
    btnCancelar.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });
}

if(modal) {
    modal.addEventListener('click', (e) => {
        if(e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
}
</script>

</body>
</html>