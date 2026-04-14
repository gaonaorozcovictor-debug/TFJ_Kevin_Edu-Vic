<?php
// ─── VERIFICAR ADMIN ──────────────────────────────────────────────
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// ─── CARGAR DATOS ────────────────────────────────────────────────
require_once __DIR__ . '/../modelos/Modelo_modulos.php';
require_once __DIR__ . '/../modelos/Modelo_profesores.php';

$modeloModulos = new Modelo_modulos();
$modeloProfesores = new Modelo_profesores();

$profesoresConHoras = $modeloModulos->obtenerTodosProfesoresConHoras();
$profesores = $modeloProfesores->obtenerProfesores();
$modulos = $modeloModulos->obtenerModulos();

// Obtener el primer profesor para seleccionarlo por defecto
$primerProfesor = !empty($profesoresConHoras) ? $profesoresConHoras[0] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar Módulos</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .drag-over { background-color: #fed7aa !important; border: 2px dashed #f97316 !important; }
    .module-dragging { opacity: 0.5; }
    .dropzone { transition: all 0.2s; }
</style>
</head>
<body class="bg-gray-100 min-h-screen" data-primer-profesor="<?= $primerProfesor ? $primerProfesor['orden'] : '' ?>">

<!-- HEADER -->
<div class="bg-white shadow-md px-6 py-4 flex justify-between items-center border-b-4 border-orange-400">
    <div class="text-gray-700 font-semibold">
        Bienvenido, <span class="text-orange-500"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></span>
    </div>
    <div class="flex gap-3">
        <a href="./index.php?vista=admin"
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
                                data-categoria="<?= htmlspecialchars($p['categoria']) ?>"
                                data-horas="<?= $p['total_horas'] ?>"
                                <?= ($primerProfesor && $primerProfesor['orden'] == $p['orden']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nombre']) ?> (<?= $p['categoria'] ?>) - <?= $p['total_horas'] ?>h
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2 text-sm text-gray-600">
                Horas totales asignadas: 
                <span id="horasProfesor" class="font-bold text-orange-600">
                    <?= $primerProfesor ? $primerProfesor['total_horas'] : '0' ?>h
                </span>
            </div>
        </div>
    </div>

    <!-- PANEL DE ASIGNACIÓN - SIEMPRE VISIBLE -->
    <div id="panelAsignacion">

        <p class="text-gray-500 mb-4">2. Asigna módulos al profesor seleccionado</p>

        <div class="flex flex-col lg:flex-row gap-6">

            <!-- MÓDULOS DISPONIBLES (IZQUIERDA) -->
            <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 text-orange-500">📚 Módulos</h2>
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
                                 data-categoria="<?= htmlspecialchars($modulo['categoria'] ?? '') ?>"
                                 data-es-pspt="<?= $esPsPt ? '1' : '0' ?>">
                                <div class="modulo-info flex-1">
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
                        <?php else: ?>
                            <?php
                                $esPsPt = (stripos($modulo['nombre_modulo'], 'PS') !== false ||
                                           stripos($modulo['nombre_modulo'], 'PT') !== false);
                                $colorFondo  = $esPsPt ? 'bg-purple-100' : 'bg-gray-100';
                                $borderColor = $esPsPt ? 'border-purple-500' : 'border-gray-400';
                            ?>
                            <div class="modulo-item <?= $colorFondo ?> p-3 rounded-lg shadow-sm flex justify-between items-center border-l-4 <?= $borderColor ?> opacity-60"
                                 data-id="<?= $modulo['id'] ?>"
                                 data-nombre="<?= htmlspecialchars($modulo['nombre_modulo']) ?>"
                                 data-grado="<?= htmlspecialchars($modulo['grado']) ?>"
                                 data-horas="<?= $modulo['horas'] ?>"
                                 data-categoria="<?= htmlspecialchars($modulo['categoria'] ?? '') ?>"
                                 data-es-pspt="<?= $esPsPt ? '1' : '0' ?>">
                                <div class="modulo-info flex-1">
                                    <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">
                                        <?= htmlspecialchars($modulo['grado']) ?>
                                    </span>
                                    <span class="font-medium ml-2"><?= htmlspecialchars($modulo['nombre_modulo']) ?></span>
                                    <span class="text-sm text-gray-500 ml-1">(<?= $modulo['horas'] ?>h)</span>
                                    <?php if($esPsPt): ?>
                                        <span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>
                                    <?php endif; ?>
                                    <span class="text-xs text-red-500 ml-2">(Asignado a: <?= htmlspecialchars($modulo['profesor_nombre'] ?? 'otro profesor') ?>)</span>
                                </div>
                                <button class="btn-asignar bg-gray-400 cursor-not-allowed text-white px-3 py-1 rounded text-sm" disabled>
                                    Asignar →
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MÓDULOS ASIGNADOS AL PROFESOR (DERECHA - DROPZONE) -->
            <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-2 text-orange-500">📋 Módulos asignados</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Horas acumuladas: <span id="horasAcumuladas" class="font-bold text-orange-600">0h</span>
                </p>

                <div id="dropzoneAsignados" 
                     class="dropzone border-2 border-dashed border-orange-300 bg-orange-50 p-4 min-h-[300px] rounded-lg transition">
                    <div id="asignados" class="space-y-2"></div>
                    <p id="mensajeVacio" class="text-gray-400 text-center mt-10 text-sm">
                        Selecciona un profesor para ver sus módulos
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

    <!-- LISTADO DE PROFESORES AGRUPADOS POR CATEGORÍA -->
    <div class="mt-10 bg-white p-5 rounded-xl shadow-md border border-gray-200">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">👨‍🏫 Profesores por categoría</h2>
        
        <?php 
        $profesoresPorCategoria = [];
        foreach($profesores as $prof) {
            $cat = $prof['categoria'] ?? 'Sin categoría';
            if(!isset($profesoresPorCategoria[$cat])) {
                $profesoresPorCategoria[$cat] = [];
            }
            $profesoresPorCategoria[$cat][] = $prof;
        }
        ksort($profesoresPorCategoria);
        ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach($profesoresPorCategoria as $categoria => $lista): ?>
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-orange-500 text-white px-4 py-2 font-semibold">
                        <?= htmlspecialchars($categoria) ?>
                    </div>
                    <div class="p-3 space-y-2">
                        <?php foreach($lista as $prof): 
                            $horasProf = 0;
                            foreach($profesoresConHoras as $ph) {
                                if($ph['orden'] == $prof['orden']) {
                                    $horasProf = $ph['total_horas'];
                                    break;
                                }
                            }
                        ?>
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <div>
                                    <span class="font-medium"><?= htmlspecialchars($prof['nombre']) ?></span>
                                    <span class="text-xs text-gray-500 ml-2">(<?= $horasProf ?>h)</span>
                                </div>
                                <span class="text-xs text-gray-400">ID: <?= $prof['orden'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
// ─── VARIABLES ──────────────────────────────────────────────────────
const selectProfesor = document.getElementById('selectProfesor');
const asignados = document.getElementById('asignados');
const mensajeVacio = document.getElementById('mensajeVacio');
const horasAcumuladasSpan = document.getElementById('horasAcumuladas');
const horasProfesorSpan = document.getElementById('horasProfesor');
const listaModulosDiv = document.getElementById('listaModulos');
const buscador = document.getElementById('buscador');
const dropzone = document.getElementById('dropzoneAsignados');

let horasAcumuladasVal = 0;
let profesorSeleccionado = null;
let profesorCategoria = null;
let datosCargados = false;

// Guardar referencia a todos los módulos originales
const todosLosModulos = Array.from(document.querySelectorAll('#listaModulos .modulo-item'));

// ─── FUNCIÓN PARA CARGAR MÓDULOS DEL PROFESOR ───────────────────────
async function cargarModulosProfesor(profesorId, categoria, horasActuales) {
    horasProfesorSpan.textContent = horasActuales + 'h';
    profesorCategoria = categoria;
    mensajeVacio.textContent = 'Arrastra módulos aquí o usa "Asignar →"';

    // Limpiar asignados
    asignados.innerHTML = '';
    horasAcumuladasVal = 0;
    
    // Mostrar todos los módulos inicialmente
    todosLosModulos.forEach(mod => {
        mod.style.display = 'flex';
        // Restaurar botones a estado original
        const btn = mod.querySelector('button');
        btn.textContent = 'Asignar →';
        btn.disabled = false;
        btn.classList.remove('btn-quitar', 'bg-red-500', 'hover:bg-red-600', 'bg-gray-400', 'cursor-not-allowed');
        btn.classList.add('btn-asignar', 'bg-orange-500', 'hover:bg-orange-600');
        // Quitar mensajes adicionales
        const infoSpan = mod.querySelector('.modulo-info');
        if(infoSpan) {
            // Eliminar spans añadidos
            const extraSpans = infoSpan.querySelectorAll('.extra-info');
            extraSpans.forEach(span => span.remove());
        }
        mod.classList.remove('opacity-60');
    });

    // Traer módulos del profesor desde el servidor
    const res = await fetch(`/TFG/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=${profesorId}`);
    const data = await res.json();
    if (!data.ok) return alert('Error al cargar módulos');

    // Procesar cada módulo
    data.modulos.forEach(mod => {
        const moduloElem = todosLosModulos.find(el => parseInt(el.dataset.id) === mod.id);
        if(!moduloElem) return;
        
        if (mod.asignado_a_profe) {
            // Mover a asignados
            moduloElem.style.display = 'none';
            const clon = moduloElem.cloneNode(true);
            clon.style.display = 'flex';
            const btn = clon.querySelector('button');
            btn.textContent = '✕ Quitar';
            btn.disabled = false;
            btn.classList.remove('btn-asignar', 'bg-orange-500', 'hover:bg-orange-600');
            btn.classList.add('btn-quitar', 'bg-red-500', 'hover:bg-red-600');
            asignados.appendChild(clon);
            horasAcumuladasVal += parseInt(clon.dataset.horas);
        } else if (mod.asignado_a_otro) {
            moduloElem.style.display = 'flex';
            moduloElem.classList.add('opacity-60');
            const btn = moduloElem.querySelector('button');
            btn.disabled = true;
            btn.classList.remove('bg-orange-500', 'hover:bg-orange-600');
            btn.classList.add('bg-gray-400', 'cursor-not-allowed');
            const infoSpan = moduloElem.querySelector('.modulo-info');
            if(infoSpan && !infoSpan.querySelector('.extra-info')) {
                const span = document.createElement('span');
                span.className = 'text-xs text-red-500 ml-2 extra-info';
                span.textContent = `(Asignado a: ${mod.profesor_nombre || 'otro'})`;
                infoSpan.appendChild(span);
            }
        } else {
            moduloElem.style.display = 'flex';
            moduloElem.classList.remove('opacity-60');
            const categoriaModulo = moduloElem.dataset.categoria || '';
            const btn = moduloElem.querySelector('button');
            
            const esCompatible = (categoriaModulo === profesorCategoria) || 
                                 (profesorCategoria === 'CATEDRÁTICO' && categoriaModulo !== '') ||
                                 (profesorCategoria === 'TITULAR' && categoriaModulo !== '');
            
            if(!esCompatible && categoriaModulo) {
                btn.disabled = true;
                btn.classList.remove('bg-orange-500', 'hover:bg-orange-600');
                btn.classList.add('bg-gray-400', 'cursor-not-allowed');
                const infoSpan = moduloElem.querySelector('.modulo-info');
                if(infoSpan && !infoSpan.querySelector('.extra-info')) {
                    const span = document.createElement('span');
                    span.className = 'text-xs text-gray-400 ml-2 extra-info';
                    span.textContent = `(Requiere: ${categoriaModulo})`;
                    infoSpan.appendChild(span);
                }
            } else {
                btn.disabled = false;
                btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                btn.classList.add('bg-orange-500', 'hover:bg-orange-600');
            }
        }
    });
    
    horasAcumuladasSpan.textContent = horasAcumuladasVal + 'h';
    mensajeVacio.style.display = asignados.children.length === 0 ? 'block' : 'none';
    datosCargados = true;
}

// ─── INICIALIZAR CON EL PRIMER PROFESOR ─────────────────────────────
document.addEventListener('DOMContentLoaded', async function() {
    const primerProfesorId = document.body.dataset.primerProfesor;
    if (primerProfesorId && selectProfesor.value) {
        const option = selectProfesor.options[selectProfesor.selectedIndex];
        const categoria = option.dataset.categoria;
        const horas = option.dataset.horas;
        await cargarModulosProfesor(primerProfesorId, categoria, horas);
    }
});

// ─── CAMBIO DE PROFESOR ─────────────────────────────────────────────
selectProfesor.addEventListener('change', async function () {
    if (!this.value) {
        horasProfesorSpan.textContent = '0h';
        profesorSeleccionado = null;
        profesorCategoria = null;
        asignados.innerHTML = '';
        horasAcumuladasVal = 0;
        horasAcumuladasSpan.textContent = '0h';
        mensajeVacio.textContent = 'Selecciona un profesor para ver sus módulos';
        todosLosModulos.forEach(mod => {
            mod.style.display = 'flex';
        });
        return;
    }

    const profesorId = this.value;
    const categoria = this.options[this.selectedIndex].dataset.categoria;
    const horas = this.options[this.selectedIndex].dataset.horas;
    await cargarModulosProfesor(profesorId, categoria, horas);
});

// ─── FUNCIONES DE MOVIMIENTO ───────────────────────────────────────
function moverAAsignados(modulo, sumarHoras = true) {
    if(modulo.parentElement && modulo.parentElement.id === 'asignados') return;
    if(!datosCargados) return;
    
    const originalMod = todosLosModulos.find(m => parseInt(m.dataset.id) === parseInt(modulo.dataset.id));
    if(originalMod) originalMod.style.display = 'none';
    
    const clon = modulo.cloneNode(true);
    clon.style.display = 'flex';
    const btn = clon.querySelector('button');
    btn.textContent = '✕ Quitar';
    btn.disabled = false;
    btn.classList.remove('btn-asignar', 'bg-orange-500', 'hover:bg-orange-600');
    btn.classList.add('btn-quitar', 'bg-red-500', 'hover:bg-red-600');
    
    asignados.appendChild(clon);
    if (sumarHoras) {
        horasAcumuladasVal += parseInt(clon.dataset.horas);
        actualizarHoras();
    }
    mensajeVacio.style.display = 'none';
}

function devolverModulo(modulo) {
    if(!datosCargados) return;
    
    const id = parseInt(modulo.dataset.id);
    const originalMod = todosLosModulos.find(m => parseInt(m.dataset.id) === id);
    if(originalMod) {
        originalMod.style.display = 'flex';
        const categoriaModulo = originalMod.dataset.categoria || '';
        const btn = originalMod.querySelector('button');
        btn.textContent = 'Asignar →';
        if(categoriaModulo && profesorCategoria && categoriaModulo !== profesorCategoria) {
            btn.disabled = true;
            btn.classList.remove('bg-orange-500', 'hover:bg-orange-600');
            btn.classList.add('bg-gray-400', 'cursor-not-allowed');
        } else {
            btn.disabled = false;
            btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btn.classList.add('bg-orange-500', 'hover:bg-orange-600');
        }
    }
    
    modulo.remove();
    horasAcumuladasVal -= parseInt(modulo.dataset.horas);
    if (horasAcumuladasVal < 0) horasAcumuladasVal = 0;
    actualizarHoras();
}

function actualizarHoras() {
    horasAcumuladasSpan.textContent = horasAcumuladasVal + 'h';
    mensajeVacio.style.display = asignados.children.length === 0 ? 'block' : 'none';
}

// ─── EVENTOS CLICK ───────────────────────────────────────────────
listaModulosDiv.addEventListener('click', e => {
    const btn = e.target.closest('button.btn-asignar');
    if (!btn) return;
    if(btn.disabled) {
        alert('❌ No puedes asignar este módulo porque la categoría no coincide o ya está asignado.');
        return;
    }
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
        if(mod.style.display === 'none') return;
        const nombre = mod.dataset.nombre.toLowerCase();
        mod.style.display = nombre.includes(valor) ? 'flex' : 'none';
    });
});

// ─── GUARDAR ASIGNACIÓN ──────────────────────────────────────────
document.getElementById('guardar').addEventListener('click', async () => {
    const profesor_id = selectProfesor.value;
    if (!profesor_id) return alert('Selecciona un profesor primero');

    if (horasAcumuladasVal > 20) {
        const confirmar = confirm(`⚠️ AVISO: El profesor tendrá ${horasAcumuladasVal} horas (supera el límite de 20).\n\n¿Deseas guardar igualmente?`);
        if (!confirmar) return;
    }

    const modulosAsignados = Array.from(asignados.children).map(mod => ({ modulo_id: parseInt(mod.dataset.id) }));

    try {
        const res = await fetch('/TFG/controladores/Controlador_asignarMod.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                asignaciones: modulosAsignados, 
                profesor_id: parseInt(profesor_id)
            })
        });
        const data = await res.json();
        if (data.ok) {
            alert(data.supera_20 ? '⚠️ ' + data.mensaje : '✅ ' + data.mensaje);
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