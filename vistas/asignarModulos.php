<?php

// ─── VERIFICAR ADMIN ──────────────────────────────────────────────
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// ─── CARGAR DATOS ────────────────────────────────────────────────
require_once __DIR__ . '/../modelos/Modelo_modulos.php';
$modeloModulos = new Modelo_modulos();
$profesoresConHoras = $modeloModulos->obtenerTodosProfesoresConHoras();
$modulos = $modeloModulos->obtenerModulos();
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
        Bienvenido, <span class="text-orange-500"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></span>
    </div>
    <div class="flex gap-3">
        <a href="./index.php?vista=profesor"
           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition">
            ← Volver al panel
        </a>
        <a href="./core/cerrar_sesion.php"
           class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow transition">
            Cerrar sesión
        </a>
    </div>
</div>

<div class="p-6">

    <h1 class="text-3xl font-bold mb-6 text-gray-800">Asignación de módulos</h1>

    <!-- MENSAJES -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- SELECTOR DE PROFESOR -->
    <div class="bg-white p-5 rounded-xl shadow-md border border-gray-200 mb-6">
        <h2 class="text-lg font-semibold mb-3 text-gray-700">1. Selecciona un profesor</h2>
        <div class="flex gap-4 items-end flex-wrap">
            <div class="flex-1 min-w-[250px]">
                <select id="selectProfesor"
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">-- Selecciona un profesor --</option>
                    <?php foreach($profesoresConHoras as $p): ?>
                        <option value="<?= $p['orden'] ?>"
                                data-horas="<?= $p['total_horas'] ?>">
                            <?= htmlspecialchars($p['nombre']) ?>
                            (<?= $p['total_horas'] ?>h asignadas)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2 text-sm text-gray-600">
                Horas totales asignadas: 
                <span id="horasProfesor" class="font-bold text-orange-600">0h</span>
            </div>
        </div>
    </div>

    <!-- AVISO SI NO HAY PROFESOR SELECCIONADO -->
    <div id="avisoProfeSin" class="bg-yellow-50 border border-yellow-300 text-yellow-700 p-4 rounded-lg mb-6">
        ⚠️ Selecciona un profesor para empezar a asignar módulos.
    </div>

    <!-- PANEL DE ASIGNACIÓN -->
    <div id="panelAsignacion" class="hidden">

        <p class="text-gray-500 mb-4">2. Asigna módulos al profesor seleccionado</p>

        <div class="flex gap-6">

            <!-- MÓDULOS DISPONIBLES -->
            <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 text-orange-500">Módulos sin asignar</h2>
                <input type="text" id="buscador" placeholder="Buscar módulo..."
                       class="w-full p-2 mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400">

                <div id="listaModulos" class="space-y-2 max-h-[500px] overflow-auto">
                    <?php foreach($modulos as $modulo): ?>
                        <?php if($modulo['profesor_id'] === null): ?>
                            <?php
                                $esPsPt = (stripos($modulo['nombre_modulo'], 'PS') !== false ||
                                           stripos($modulo['nombre_modulo'], 'PT') !== false);
                                $colorFondo  = $esPsPt ? 'bg-purple-100 hover:bg-purple-200' : 'bg-orange-100 hover:bg-orange-200';
                                $borderColor = $esPsPt ? 'border-purple-500' : 'border-orange-500';
                            ?>
                            <div class="modulo-item <?= $colorFondo ?> p-3 rounded-lg shadow-sm flex justify-between items-center transition border-l-4 <?= $borderColor ?>"
                                 data-id="<?= $modulo['id'] ?>"
                                 data-nombre="<?= htmlspecialchars($modulo['nombre_modulo']) ?>"
                                 data-grado="<?= htmlspecialchars($modulo['grado']) ?>"
                                 data-horas="<?= $modulo['horas'] ?>"
                                 data-es-pspt="<?= $esPsPt ? '1' : '0' ?>">
                                <div>
                                    <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">
                                        <?= htmlspecialchars($modulo['grado']) ?>
                                    </span>
                                    <span class="font-medium ml-2"><?= htmlspecialchars($modulo['nombre_modulo']) ?></span>
                                    <span class="text-sm text-gray-500 ml-1">(<?= $modulo['horas'] ?>h)</span>
                                    <?php if($esPsPt): ?>
                                        <span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>
                                    <?php endif; ?>
                                </div>
                                <button class="btn-asignar bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm">
                                    Asignar →
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MÓDULOS ASIGNADOS AL PROFESOR -->
            <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-2 text-orange-500">Módulos del profesor</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Horas acumuladas: <span id="horasAcumuladas" class="font-bold text-orange-600">0h</span>
                </p>

                <div class="border-2 border-dashed border-orange-300 bg-orange-50 p-4 min-h-[300px] rounded-lg">
                    <div id="asignados" class="space-y-2"></div>
                    <p id="mensajeVacio" class="text-gray-400 text-center mt-10 text-sm">
                        Usa "Asignar →" para añadir módulos aquí
                    </p>
                </div>
            </div>

        </div>

        <!-- BOTÓN GUARDAR -->
        <div class="mt-6 flex justify-end">
            <button id="guardar"
                class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-lg shadow-md transition font-semibold text-lg">
                💾 Guardar asignación
            </button>
        </div>

    </div>
</div>

<script>
// ─── VARIABLES ──────────────────────────────────────────────────────
const selectProfesor = document.getElementById('selectProfesor');
const panelAsignacion = document.getElementById('panelAsignacion');
const avisoProfeSin = document.getElementById('avisoProfeSin');
const asignados = document.getElementById('asignados');
const mensajeVacio = document.getElementById('mensajeVacio');
const horasAcumuladas = document.getElementById('horasAcumuladas');
const horasProfesor = document.getElementById('horasProfesor');
const listaModulosDiv = document.getElementById('listaModulos');
const buscador = document.getElementById('buscador');

let horasAcumuladasVal = 0;

// ─── CAMBIO DE PROFESOR ─────────────────────────────────────────────
selectProfesor.addEventListener('change', async function () {
    if (!this.value) {
        panelAsignacion.classList.add('hidden');
        avisoProfeSin.classList.remove('hidden');
        horasProfesor.textContent = '0h';
        return;
    }

    panelAsignacion.classList.remove('hidden');
    avisoProfeSin.classList.add('hidden');

    // Limpiar paneles
    listaModulosDiv.innerHTML = '';
    asignados.innerHTML = '';
    horasAcumuladasVal = 0;

    // Traer módulos del profesor
    const res = await fetch(`/TFG/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=${this.value}`);
    const data = await res.json();
    if (!data.ok) return alert('Error al cargar módulos');

    // Crear módulos en lista de disponibles y asignados
    data.modulos.forEach(mod => {
        const moduloElem = crearModuloItem(mod);

        if (mod.asignado_a_profe) {
            moverAAsignados(moduloElem, false);
        } else if (mod.asignado_a_otro) {
            moduloElem.classList.add('opacity-50', 'pointer-events-none');
            listaModulosDiv.appendChild(moduloElem);
        } else {
            listaModulosDiv.appendChild(moduloElem);
        }
    });

    // Calcular horas acumuladas iniciales
    horasAcumuladasVal = Array.from(asignados.children).reduce((sum, mod) => sum + parseInt(mod.dataset.horas), 0);
    horasAcumuladas.textContent = horasAcumuladasVal + 'h';
    mensajeVacio.style.display = asignados.children.length === 0 ? 'block' : 'none';
});

// ─── FUNCIONES ─────────────────────────────────────────────────────
function crearModuloItem(mod) {
    // ✅ CORREGIDO: Detectar correctamente PS/PT (funciona con booleano o número)
    const esPsPt = (mod.es_pspt === true || mod.es_pspt === 1 || mod.es_pspt === '1') ||
                   (mod.nombre_modulo && (mod.nombre_modulo.includes('PS') || mod.nombre_modulo.includes('PT')));
    
    const colorFondo = esPsPt ? 'bg-purple-100 hover:bg-purple-200' : 'bg-orange-100 hover:bg-orange-200';
    const borderColor = esPsPt ? 'border-purple-500' : 'border-orange-500';

    const div = document.createElement('div');
    div.className = `modulo-item ${colorFondo} p-3 rounded-lg shadow-sm flex justify-between items-center transition border-l-4 ${borderColor}`;
    div.dataset.id = mod.id;
    div.dataset.nombre = mod.nombre_modulo;
    div.dataset.grado = mod.grado;
    div.dataset.horas = mod.horas;
    div.dataset.esPspt = esPsPt ? '1' : '0';

    div.innerHTML = `
        <div>
            <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">${mod.grado}</span>
            <span class="font-medium ml-2">${mod.nombre_modulo}</span>
            <span class="text-sm text-gray-500 ml-1">(${mod.horas}h)</span>
            ${esPsPt ? '<span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>' : ''}
        </div>
        <button class="btn-asignar bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm">Asignar →</button>
    `;
    return div;
}

function moverAAsignados(modulo, sumarHoras = true) {
    const btn = modulo.querySelector('button');
    btn.textContent = '✕ Quitar';
    btn.classList.remove('btn-asignar', 'bg-orange-500', 'hover:bg-orange-600');
    btn.classList.add('btn-quitar', 'bg-red-500', 'hover:bg-red-600');

    asignados.appendChild(modulo);
    if (sumarHoras) horasAcumuladasVal += parseInt(modulo.dataset.horas);
    actualizarHoras();
}

function devolverModulo(modulo) {
    const btn = modulo.querySelector('button');
    btn.textContent = 'Asignar →';
    btn.classList.remove('btn-quitar', 'bg-red-500', 'hover:bg-red-600');
    btn.classList.add('btn-asignar', 'bg-orange-500', 'hover:bg-orange-600');

    listaModulosDiv.appendChild(modulo);
    horasAcumuladasVal -= parseInt(modulo.dataset.horas);
    if (horasAcumuladasVal < 0) horasAcumuladasVal = 0;
    actualizarHoras();
}

function actualizarHoras() {
    horasAcumuladas.textContent = horasAcumuladasVal + 'h';
    mensajeVacio.style.display = asignados.children.length === 0 ? 'block' : 'none';
}

// ─── EVENTOS CLICK ───────────────────────────────────────────────
listaModulosDiv.addEventListener('click', e => {
    const btn = e.target.closest('button.btn-asignar');
    if (!btn) return;
    moverAAsignados(btn.closest('.modulo-item'));
});

asignados.addEventListener('click', e => {
    const btn = e.target.closest('button.btn-quitar');
    if (!btn) return;
    devolverModulo(btn.closest('.modulo-item'));
});

// ─── BUSCADOR ────────────────────────────────────────────────────
buscador.addEventListener('input', function () {
    const valor = this.value.toLowerCase();
    document.querySelectorAll('#listaModulos .modulo-item').forEach(mod => {
        const nombre = mod.dataset.nombre.toLowerCase();
        mod.style.display = nombre.includes(valor) ? 'flex' : 'none';
    });
});

// ─── GUARDAR ASIGNACIÓN (MODIFICADO: SOLO AVISO, NO BLOQUEO) ─────
document.getElementById('guardar').addEventListener('click', async () => {
    const profesor_id = selectProfesor.value;
    if (!profesor_id) return alert('Selecciona un profesor primero');

    // ✅ CAMBIO: Solo mostrar aviso si supera 20 horas, pero permitir continuar
    if (horasAcumuladasVal > 20) {
        const confirmar = confirm(`⚠️ AVISO: El profesor tendrá ${horasAcumuladasVal} horas (supera el límite de 20).\n\n¿Deseas guardar igualmente?`);
        if (!confirmar) {
            return;
        }
    }

    const modulosAsignados = Array.from(asignados.children).map(mod => ({ modulo_id: parseInt(mod.dataset.id) }));

    // ✅ CAMBIO: Permitir desasignar todos los módulos (no obligatorio tener al menos uno)
    // if (modulosAsignados.length === 0) return alert('No has asignado ningún módulo');

    try {
        const res = await fetch('/TFG/controladores/Controlador_asignarMod.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                asignaciones: modulosAsignados, 
                profesor_id: parseInt(profesor_id),
                ignorar_limite: horasAcumuladasVal > 20  // Flag opcional para el backend
            })
        });
        const data = await res.json();
        if (data.ok) {
            if (data.supera_20) {
                alert('⚠️ ' + data.mensaje);
            } else {
                alert('✅ ' + data.mensaje);
            }
            location.reload();
        } else {
            alert('❌ ' + data.mensaje);
        }
    } catch(err) {
        console.error(err);
        alert('Error de conexión al guardar');
    }
});
</script>

</body>
</html>