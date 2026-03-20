<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar Módulos</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
.drag-item { cursor: grab; }
.drag-item:active { cursor: grabbing; }
</style>

</head>

<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6">Asignar Módulos</h1>

<div class="flex gap-6">

    <!-- Módulos disponibles -->
    <div class="flex-1 bg-white p-4 rounded shadow">
        <h2 class="font-bold mb-4">Disponibles</h2>

        <?php foreach($modulos as $modulo): ?>
            <?php if($modulo['profesor_id'] === null): ?>
                <div class="drag-item bg-orange-200 p-2 mb-2 rounded"
                     draggable="true"
                     data-id="<?= $modulo['id'] ?>">
                    <?= $modulo['grado'] . " - " . $modulo['modulo'] ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Zona del profesor -->
    <div class="flex-1 bg-white p-4 rounded shadow">
        <h2 class="font-bold mb-4">Tus módulos</h2>

        <div class="dropzone border-2 border-dashed p-4 min-h-[200px]"
             data-profesor-id="<?= $_SESSION['profesor_id'] ?>">
        </div>
    </div>

</div>

<button id="guardar" class="mt-6 bg-green-500 text-white px-4 py-2 rounded">
Guardar
</button>

<script>
const items = document.querySelectorAll('.drag-item');
const zone = document.querySelector('.dropzone');

items.forEach(item=>{
    item.addEventListener('dragstart', e=>{
        e.dataTransfer.setData('id', item.dataset.id);
    });
});

zone.addEventListener('dragover', e=>e.preventDefault());

zone.addEventListener('drop', e=>{
    e.preventDefault();
    const id = e.dataTransfer.getData('id');
    const item = document.querySelector(`[data-id='${id}']`);
    zone.appendChild(item);
});

document.getElementById('guardar').addEventListener('click', ()=>{
    const asignaciones = [];

    zone.querySelectorAll('.drag-item').forEach(item=>{
        asignaciones.push({
            modulo_id: item.dataset.id,
            profesor_id: zone.dataset.profesorId
        });
    });

    fetch('../controladores/Controlador_asignarMod.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(asignaciones)
    })
    .then(res=>res.json())
    .then(data=>{
        alert(data.mensaje);
        location.reload();
    });
});
</script>

</body>
</html>