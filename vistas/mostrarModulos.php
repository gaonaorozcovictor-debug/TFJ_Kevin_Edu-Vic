<?php
require_once __DIR__ . '/../modelos/Modelo_modulos.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

$modeloModulos = new Modelo_modulos();
$modeloProfesores = new Modelo_profesores();

$modulos = $modeloModulos->obtenerModulos();
$profesores = $modeloProfesores->obtenerProfesores();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignación de Módulos</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
.drag-item { cursor: grab; transition: background 0.2s; }
.drag-item:active { cursor: grabbing; }
.drag-item:hover { background-color: #fb923c; color: white; }
.dropzone { min-height: 200px; }
</style>
</head>

<body class="bg-gray-100 min-h-screen font-sans p-6">

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4 md:mb-0">
            Asignación de Módulos
        </h1>
        <form action="/asignaciones/core/cerrar_sesion.php" method="post">
            <button type="submit" 
                class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-2xl shadow-md transition">
                Cerrar sesión
            </button>
        </form>
    </header>

    <!-- Subida de archivos -->
    <div class="flex flex-col md:flex-row gap-6 mb-8">
        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Subir Profesores</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="file" name="archivo_profesores" accept=".xls,.xlsx" required
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-orange-300 focus:outline-none">
                <button type="submit" name="subir_profesores"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-2xl shadow-md transition">
                    Subir Profesores
                </button>
            </form>
        </div>
        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Subir Módulos</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="file" name="archivo_modulos" accept=".xls,.xlsx" required
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-orange-300 focus:outline-none">
                <button type="submit" name="subir_modulos"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-2xl shadow-md transition">
                    Subir Módulos
                </button>
            </form>
        </div>
    </div>

    <!-- Módulos disponibles y Profesores -->
    <div class="flex flex-col md:flex-row gap-6">

        <!-- Módulos disponibles -->
        <div class="flex-1 bg-white p-6 rounded-2xl shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Módulos disponibles</h2>
            <div id="modulos" class="space-y-2">
                <?php foreach($modulos as $modulo): ?>
                    <?php if($modulo['profesor_id'] === null): ?>
                        <div class="drag-item p-3 border rounded-lg bg-orange-100 font-medium shadow-sm" draggable="true" data-id="<?= $modulo['id'] ?>">
                            <?= htmlspecialchars($modulo['grado'] . " - " . $modulo['modulo']) ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Profesores -->
        <div class="flex-1 flex flex-col gap-4">
            <?php foreach($profesores as $profesor): ?>
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 flex-1">
                    <h2 class="text-xl font-semibold mb-4"><?= htmlspecialchars($profesor['nombre']) ?></h2>
                    <div class="dropzone border-2 border-dashed border-gray-300 p-3 rounded-2xl flex flex-col gap-2" data-profesor-id="<?= $profesor['id'] ?>">
                        <?php foreach($modulos as $modulo): ?>
                            <?php if($modulo['profesor_id'] == $profesor['id']): ?>
                                <div class="drag-item p-3 border rounded-lg bg-orange-100 font-medium shadow-sm" draggable="true" data-id="<?= $modulo['id'] ?>">
                                    <?= htmlspecialchars($modulo['grado'] . " - " . $modulo['modulo']) ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- Guardar asignaciones -->
    <div class="mt-6 text-center">
        <button id="guardar" class="bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded-2xl shadow-md font-semibold transition">
            Guardar asignaciones
        </button>
    </div>

</div>

<script>
// Drag & Drop
const items = document.querySelectorAll('.drag-item');
const dropzones = document.querySelectorAll('.dropzone');

items.forEach(item => {
    item.addEventListener('dragstart', e => {
        e.dataTransfer.setData('text/plain', item.dataset.id);
        e.dataTransfer.effectAllowed = 'move';
    });
});

dropzones.forEach(zone => {
    zone.addEventListener('dragover', e => e.preventDefault());
    zone.addEventListener('drop', e => {
        e.preventDefault();
        const id = e.dataTransfer.getData('text/plain');
        const item = document.querySelector(`.drag-item[data-id='${id}']`);
        if(item && !zone.contains(item)) {
            zone.appendChild(item);
        }
    });
});

// Guardar asignaciones
document.getElementById('guardar').addEventListener('click', () => {
    const asignaciones = [];
    dropzones.forEach(zone => {
        const profesorId = zone.dataset.profesorId;
        zone.querySelectorAll('.drag-item').forEach(modulo => {
            asignaciones.push({modulo_id: modulo.dataset.id, profesor_id: profesorId});
        });
    });

    fetch('/asignaciones/controladores/Controlador_asignarMod.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(asignaciones)
    })
    .then(res => res.json())
    .then(data => {
        alert(data.mensaje);
        if(data.ok) location.reload();
    })
    .catch(err => console.error(err));
});
</script>

</body>
</html>