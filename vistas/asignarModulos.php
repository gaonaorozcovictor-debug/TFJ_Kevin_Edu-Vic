<?php
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
<title>Asignar Módulos</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<!-- HEADER -->
<div class="bg-white shadow-md px-6 py-4 flex justify-between items-center border-b-4 border-orange-400">

    <div class="text-gray-700 font-semibold">
        Bienvenido, <span class="text-orange-500"><?= $_SESSION['nombre'] ?? 'Usuario' ?></span>
    </div>

    <form action="./core/cerrar_sesion.php" method="post">
        <button type="submit" 
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow transition">
            Cerrar sesión
        </button>
    </form>

</div>

<div class="p-6">

    <h1 class="text-3xl font-bold mb-8 text-gray-800">
        Asignación de módulos
    </h1>

    <div class="flex gap-6">

        <!-- MÓDULOS DISPONIBLES -->
        <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">

            <h2 class="text-xl font-semibold mb-4 text-orange-500">
                Módulos disponibles
            </h2>

            <!-- BUSCADOR -->
            <input type="text" id="buscador" placeholder="Buscar módulo..."
                   class="w-full p-2 mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400">

            <!-- LISTA -->
            <div id="listaModulos" class="space-y-2 max-h-[500px] overflow-auto">

                <?php foreach($modulos as $modulo): ?>

                    <?php if($modulo['profesor_id'] === null): ?>
                        <?php 
                            // Determinar si es PS o PT
                            $esPsPt = (stripos($modulo['nombre_modulo'], 'PS') !== false || 
                                    stripos($modulo['nombre_modulo'], 'PT') !== false);
                            $colorFondo = $esPsPt ? 'bg-purple-100 hover:bg-purple-200' : 'bg-orange-100 hover:bg-orange-200';
                            $borderColor = $esPsPt ? 'border-purple-500' : 'border-orange-500';
                        ?>

                        <div class="modulo-item <?= $colorFondo ?> p-3 rounded-lg shadow-sm flex justify-between items-center transition border-l-4 <?= $borderColor ?>"
                             data-id="<?= $modulo['id'] ?>"
                             data-nombre="<?= htmlspecialchars($modulo['nombre_modulo']) ?>"
                             data-grado="<?= htmlspecialchars($modulo['grado']) ?>"
                             data-horas="<?= $modulo['horas'] ?>"
                             data-es-pspt="<?= $esPsPt ? '1' : '0' ?>">

                            <div>
                                <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded"><?= htmlspecialchars($modulo['grado']) ?></span>
                                <span class="font-medium ml-2"><?= htmlspecialchars($modulo['nombre_modulo']) ?></span>
                                <span class="text-sm text-gray-500 ml-1">(<?= $modulo['horas'] ?>h)</span>
                                <?php if($esPsPt): ?>
                                    <span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>
                                <?php endif; ?>
                            </div>

                            <button class="asignar bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm">
                                Asignar
                            </button>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </div>

        <!-- ASIGNADOS -->
        <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">

            <h2 class="text-xl font-semibold mb-4 text-orange-500">
                Módulos asignados
            </h2>

            <div class="border-2 border-dashed border-orange-300 bg-orange-50 p-4 min-h-[300px] rounded-lg">

                <div id="asignados" class="space-y-2"></div>

                <p id="mensajeDrop" class="text-gray-500 text-center mt-10">
                    Usa "Asignar" para añadir módulos
                </p>

            </div>

        </div>

    </div>

    <!-- BOTÓN GUARDAR -->
    <div class="mt-8 flex justify-end">

        <button id="guardar" 
            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg shadow-md transition font-semibold">
            Guardar cambios
        </button>

    </div>

</div>

<script>

const buscador = document.getElementById('buscador');
const modulos = document.querySelectorAll('.modulo-item');
const asignados = document.getElementById('asignados');
const mensaje = document.getElementById('mensajeDrop');

// Función para actualizar el contenido visual del módulo (mantiene ciclo, horas y badge)
function actualizarContenidoModulo(modulo) {
    const nombre = modulo.dataset.nombre;
    const grado = modulo.dataset.grado;
    const horas = modulo.dataset.horas;
    const esPsPt = modulo.dataset.esPspt === '1';
    
    const divContenido = modulo.querySelector('div:first-child');
    divContenido.innerHTML = `
        <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">${escapeHtml(grado)}</span>
        <span class="font-medium ml-2">${escapeHtml(nombre)}</span>
        <span class="text-sm text-gray-500 ml-1">(${horas}h)</span>
        ${esPsPt ? '<span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>' : ''}
    `;
}

// Función auxiliar para escapar HTML
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

//BUSCADOR
buscador.addEventListener('input', function () {

    const valor = this.value.toLowerCase();

    modulos.forEach(modulo => {

        const nombre = modulo.dataset.nombre.toLowerCase();

        modulo.style.display = nombre.includes(valor) ? 'flex' : 'none';

    });

});

//ASIGNAR
document.querySelectorAll('.asignar').forEach(btn => {

    btn.addEventListener('click', function () {

        const modulo = this.closest('.modulo-item');
        
        // Actualizar contenido por si acaso
        actualizarContenidoModulo(modulo);

        // Crear botón quitar
        const btnQuitar = document.createElement('button');
        btnQuitar.textContent = "Quitar";
        btnQuitar.className = "bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm";

        btnQuitar.addEventListener('click', function () {

            document.getElementById('listaModulos').appendChild(modulo);
            this.remove();

            // Actualizar contenido al regresar a disponibles
            actualizarContenidoModulo(modulo);

            // volver a añadir botón asignar
            const btnAsignar = document.createElement('button');
            btnAsignar.textContent = "Asignar";
            btnAsignar.className = "asignar bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm";

            btnAsignar.addEventListener('click', function () {
                asignados.appendChild(modulo);
                actualizarContenidoModulo(modulo);
            });

            modulo.appendChild(btnAsignar);

            if (asignados.children.length === 0) {
                mensaje.style.display = 'block';
            }

        });

        // quitar botón asignar
        this.remove();

        // añadir botón quitar
        modulo.appendChild(btnQuitar);

        asignados.appendChild(modulo);

        mensaje.style.display = 'none';

    });

});

// GUARDAR
document.getElementById('guardar').addEventListener('click', () => {

    const asignaciones = [];

    asignados.querySelectorAll('.modulo-item').forEach(item => {

        asignaciones.push({
            modulo_id: item.dataset.id,
            profesor_id: <?= $_SESSION['profesor_id'] ?? 'null' ?>
        });

    });

    fetch('../controladores/Controlador_asignarMod.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(asignaciones)
    })
    .then(res => res.json())
    .then(data => {
        alert(data.mensaje);
        if(data.ok) {
            location.reload();
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Error al guardar las asignaciones');
    });

});

</script>

</body>
</html>