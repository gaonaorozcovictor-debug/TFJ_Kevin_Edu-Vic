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
$modulos = $modeloModulos->obtenerModulosConProfesor(); // Esto ya trae profesor_nombre

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
    .module-dragging { opacity: 0.4 !important; }
    .dropzone { transition: all 0.2s; min-height: 300px; }
    .modulo-item { cursor: grab; transition: all 0.2s; user-select: none; }
    .modulo-item:active { cursor: grabbing; }
    .badge-asignado {
        background-color: #ef4444;
        color: white;
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 9999px;
        margin-left: 0.5rem;
        display: inline-block;
    }
</style>
</head>
<body class="bg-gray-100 min-h-screen">

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
                                data-horas="<?= $p['total_horas'] ?>"
                                data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                <?= ($primerProfesor && $primerProfesor['orden'] == $p['orden']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nombre']) ?> (<?= $p['categoria'] ?>) - <?= $p['total_horas'] ?>h
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2 text-sm text-gray-600">
                Horas actuales del profesor: 
                <span id="horasProfesor" class="font-bold text-orange-600">
                    <?= $primerProfesor ? $primerProfesor['total_horas'] : '0' ?>h
                </span>
            </div>
        </div>
    </div>

    <!-- PANEL DE ASIGNACIÓN -->
    <div id="panelAsignacion">
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- MÓDULOS DISPONIBLES (IZQUIERDA) -->
            <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 text-orange-500">📚 Módulos disponibles</h2>
                <input type="text" id="buscador" placeholder="Buscar módulo..."
                       class="w-full p-2 mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400">

                <div id="listaModulos" class="space-y-2 max-h-[500px] overflow-auto">
                    <?php foreach($modulos as $modulo): ?>
                        <?php
                            $esPsPt = (stripos($modulo['nombre_modulo'], 'PS') !== false ||
                                       stripos($modulo['nombre_modulo'], 'PT') !== false);
                            $colorFondo = $esPsPt ? 'bg-purple-100' : 'bg-orange-100';
                            $borderColor = $esPsPt ? 'border-purple-500' : 'border-orange-500';
                            $estaAsignado = $modulo['profesor_id'] !== null;
                            $profesorAsignado = $modulo['profesor_nombre'] ?? 'Desconocido';
                        ?>
                        <div class="modulo-item <?= $colorFondo ?> p-3 rounded-lg shadow-sm flex justify-between items-center transition border-l-4 <?= $borderColor ?>"
                             data-id="<?= $modulo['id'] ?>"
                             data-nombre="<?= htmlspecialchars($modulo['nombre_modulo']) ?>"
                             data-grado="<?= htmlspecialchars($modulo['grado']) ?>"
                             data-horas="<?= $modulo['horas'] ?>"
                             data-asignado="<?= $estaAsignado ? '1' : '0' ?>"
                             data-profesor-asignado="<?= htmlspecialchars($profesorAsignado) ?>"
                             data-profesor-id="<?= $modulo['profesor_id'] ?? '' ?>">
                            <div class="modulo-info flex-1">
                                <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">
                                    <?= htmlspecialchars($modulo['grado']) ?>
                                </span>
                                <span class="font-medium ml-2"><?= htmlspecialchars($modulo['nombre_modulo']) ?></span>
                                <span class="text-sm text-gray-500 ml-1">(<?= $modulo['horas'] ?>h)</span>
                                <?php if($esPsPt): ?>
                                    <span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>
                                <?php endif; ?>
                                <?php if($estaAsignado): ?>
                                    <span class="badge-asignado">
                                        👤 <?= htmlspecialchars($profesorAsignado) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button class="btn-asignar px-3 py-1 rounded text-sm <?= $estaAsignado ? 'bg-gray-400 cursor-not-allowed' : 'bg-orange-500 hover:bg-orange-600 text-white' ?>"
                                    <?= $estaAsignado ? 'disabled' : '' ?>>
                                <?= $estaAsignado ? '✓ Ocupado' : 'Asignar →' ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MÓDULOS ASIGNADOS AL PROFESOR (DERECHA) -->
            <div class="flex-1 bg-white p-5 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold mb-2 text-orange-500">📋 Módulos de <span id="nombreProfesorActual">este profesor</span></h2>
                <p class="text-sm text-gray-500 mb-4">
                    Horas acumuladas: <span id="horasAcumuladas" class="font-bold text-orange-600">0h</span>
                </p>

                <div id="dropzoneAsignados" class="dropzone border-2 border-dashed border-orange-300 bg-orange-50 p-4 rounded-lg transition">
                    <div id="asignadosContainer" class="space-y-2"></div>
                    <p id="mensajeVacio" class="text-gray-400 text-center mt-10 text-sm">
                        Selecciona un profesor para ver sus módulos
                    </p>
                </div>
            </div>
        </div>

        <!-- BOTÓN GUARDAR -->
        <div class="mt-6 flex justify-end">
            <button id="guardarBtn"
                class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-lg shadow-md transition font-semibold text-lg">
                💾 Guardar asignación
            </button>
        </div>
    </div>

    <!-- LISTADO DE PROFESORES POR CATEGORÍA -->
    <div class="mt-10 bg-white p-5 rounded-xl shadow-md border border-gray-200">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">👨‍🏫 Listado de profesores</h2>
        
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
                                <span class="font-medium"><?= htmlspecialchars($prof['nombre']) ?></span>
                                <span class="text-xs text-gray-500"><?= $horasProf ?>h asignadas</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
// ============================================================
// VARIABLES
// ============================================================
const selectProfesor = document.getElementById('selectProfesor');
const asignadosContainer = document.getElementById('asignadosContainer');
const mensajeVacio = document.getElementById('mensajeVacio');
const horasAcumuladasSpan = document.getElementById('horasAcumuladas');
const horasProfesorSpan = document.getElementById('horasProfesor');
const nombreProfesorActualSpan = document.getElementById('nombreProfesorActual');
const listaModulosDiv = document.getElementById('listaModulos');
const buscador = document.getElementById('buscador');
const dropzone = document.getElementById('dropzoneAsignados');

let horasAcumuladas = 0;
let profesorActualId = null;
let draggedItem = null;

// ============================================================
// FUNCIÓN: Cargar módulos asignados al profesor
// ============================================================
async function cargarModulosAsignados(profesorId, nombreProfesor) {
    // Limpiar contenedor
    asignadosContainer.innerHTML = '';
    horasAcumuladas = 0;
    
    if (!profesorId) {
        horasAcumuladasSpan.textContent = '0h';
        mensajeVacio.textContent = 'Selecciona un profesor para ver sus módulos';
        mensajeVacio.style.display = 'block';
        nombreProfesorActualSpan.textContent = 'este profesor';
        return;
    }
    
    nombreProfesorActualSpan.textContent = nombreProfesor || 'este profesor';
    mensajeVacio.textContent = 'No tiene módulos asignados';
    
    try {
        const res = await fetch(`/TFG/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=${profesorId}`);
        const data = await res.json();
        
        if (!data.ok) {
            console.error('Error:', data.mensaje);
            return;
        }
        
        // Filtrar módulos asignados a ESTE profesor
        const modulosAsignados = data.modulos.filter(mod => mod.asignado_a_profe === true);
        
        modulosAsignados.forEach(modulo => {
            const esPsPt = modulo.nombre_modulo.includes('PS') || modulo.nombre_modulo.includes('PT');
            const colorFondo = esPsPt ? 'bg-purple-100' : 'bg-orange-100';
            const borderColor = esPsPt ? 'border-purple-500' : 'border-orange-500';
            
            const div = document.createElement('div');
            div.className = `modulo-item ${colorFondo} p-3 rounded-lg shadow-sm flex justify-between items-center transition border-l-4 ${borderColor}`;
            div.setAttribute('data-id', modulo.id);
            div.setAttribute('data-nombre', modulo.nombre_modulo);
            div.setAttribute('data-grado', modulo.grado);
            div.setAttribute('data-horas', modulo.horas);
            div.setAttribute('data-asignado', '0');
            div.setAttribute('draggable', 'true');
            
            div.innerHTML = `
                <div class="modulo-info flex-1">
                    <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">
                        ${modulo.grado}
                    </span>
                    <span class="font-medium ml-2">${modulo.nombre_modulo}</span>
                    <span class="text-sm text-gray-500 ml-1">(${modulo.horas}h)</span>
                    ${esPsPt ? '<span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>' : ''}
                </div>
                <button class="btn-quitar bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    ✕ Quitar
                </button>
            `;
            
            configurarDragDrop(div);
            asignadosContainer.appendChild(div);
            horasAcumuladas += modulo.horas;
        });
        
        horasAcumuladasSpan.textContent = horasAcumuladas + 'h';
        mensajeVacio.style.display = modulosAsignados.length === 0 ? 'block' : 'none';
        
    } catch (error) {
        console.error('Error:', error);
    }
}

// ============================================================
// FUNCIÓN: Mover módulo a asignados (desde izquierda)
// ============================================================
function moverAAsignados(modulo) {
    // Verificar si ya está en asignados
    if (modulo.parentElement?.id === 'asignadosContainer') return;
    
    // Verificar si el módulo ya está asignado a otro profesor
    if (modulo.dataset.asignado === '1') {
        return; // Silenciosamente ignorar
    }
    
    const id = modulo.dataset.id;
    const nombre = modulo.dataset.nombre;
    const grado = modulo.dataset.grado;
    const horas = parseInt(modulo.dataset.horas);
    const esPsPt = modulo.querySelector('.bg-purple-200') !== null;
    
    // Ocultar original
    modulo.style.display = 'none';
    
    // Crear clon para asignados
    const colorFondo = esPsPt ? 'bg-purple-100' : 'bg-orange-100';
    const borderColor = esPsPt ? 'border-purple-500' : 'border-orange-500';
    
    const clon = document.createElement('div');
    clon.className = `modulo-item ${colorFondo} p-3 rounded-lg shadow-sm flex justify-between items-center transition border-l-4 ${borderColor}`;
    clon.setAttribute('data-id', id);
    clon.setAttribute('data-nombre', nombre);
    clon.setAttribute('data-grado', grado);
    clon.setAttribute('data-horas', horas);
    clon.setAttribute('data-asignado', '0');
    clon.setAttribute('draggable', 'true');
    
    clon.innerHTML = `
        <div class="modulo-info flex-1">
            <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">
                ${grado}
            </span>
            <span class="font-medium ml-2">${nombre}</span>
            <span class="text-sm text-gray-500 ml-1">(${horas}h)</span>
            ${esPsPt ? '<span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>' : ''}
        </div>
        <button class="btn-quitar bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
            ✕ Quitar
        </button>
    `;
    
    configurarDragDrop(clon);
    asignadosContainer.appendChild(clon);
    
    // Actualizar horas
    horasAcumuladas += horas;
    horasAcumuladasSpan.textContent = horasAcumuladas + 'h';
    mensajeVacio.style.display = 'none';
}

// ============================================================
// FUNCIÓN: Devolver módulo a disponibles
// ============================================================
function devolverAModulos(modulo) {
    const id = modulo.dataset.id;
    const nombre = modulo.dataset.nombre;
    const grado = modulo.dataset.grado;
    const horas = parseInt(modulo.dataset.horas);
    
    // Buscar el original en la lista de disponibles
    const originalMod = document.querySelector(`#listaModulos .modulo-item[data-id="${id}"]`);
    if (originalMod) {
        originalMod.style.display = 'flex';
    } else {
        // Si no existe, recrearlo (por si acaso)
        const disponiblesContainer = document.getElementById('listaModulos');
        const esPsPt = modulo.querySelector('.bg-purple-200') !== null;
        const colorFondo = esPsPt ? 'bg-purple-100' : 'bg-orange-100';
        const borderColor = esPsPt ? 'border-purple-500' : 'border-orange-500';
        
        const nuevoMod = document.createElement('div');
        nuevoMod.className = `modulo-item ${colorFondo} p-3 rounded-lg shadow-sm flex justify-between items-center transition border-l-4 ${borderColor}`;
        nuevoMod.setAttribute('data-id', id);
        nuevoMod.setAttribute('data-nombre', nombre);
        nuevoMod.setAttribute('data-grado', grado);
        nuevoMod.setAttribute('data-horas', horas);
        nuevoMod.setAttribute('data-asignado', '0');
        nuevoMod.setAttribute('draggable', 'true');
        
        nuevoMod.innerHTML = `
            <div class="modulo-info flex-1">
                <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">
                    ${grado}
                </span>
                <span class="font-medium ml-2">${nombre}</span>
                <span class="text-sm text-gray-500 ml-1">(${horas}h)</span>
                ${esPsPt ? '<span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>' : ''}
            </div>
            <button class="btn-asignar bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm">
                Asignar →
            </button>
        `;
        
        configurarDragDrop(nuevoMod);
        disponiblesContainer.appendChild(nuevoMod);
    }
    
    // Eliminar clon
    modulo.remove();
    
    // Actualizar horas
    horasAcumuladas -= horas;
    if (horasAcumuladas < 0) horasAcumuladas = 0;
    horasAcumuladasSpan.textContent = horasAcumuladas + 'h';
    
    if (asignadosContainer.children.length === 0) {
        mensajeVacio.style.display = 'block';
    }
}

// ============================================================
// FUNCIÓN: Configurar Drag & Drop
// ============================================================
function configurarDragDrop(elemento) {
    elemento.setAttribute('draggable', 'true');
    
    elemento.addEventListener('dragstart', function(e) {
        const btn = this.querySelector('button');
        if (btn && btn.disabled) {
            e.preventDefault();
            return false;
        }
        draggedItem = this;
        e.dataTransfer.setData('text/plain', this.dataset.id);
        e.dataTransfer.effectAllowed = 'move';
        this.classList.add('module-dragging');
    });
    
    elemento.addEventListener('dragend', function(e) {
        this.classList.remove('module-dragging');
        draggedItem = null;
    });
}

// ============================================================
// CONFIGURAR DROPZONE
// ============================================================
function configurarDropzone() {
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        this.classList.add('drag-over');
    });
    
    dropzone.addEventListener('dragleave', function(e) {
        this.classList.remove('drag-over');
    });
    
    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        
        if (!draggedItem) return;
        if (draggedItem.parentElement?.id === 'asignadosContainer') return;
        if (draggedItem.dataset.asignado === '1') return;
        
        moverAAsignados(draggedItem);
        draggedItem = null;
    });
}

// ============================================================
// CONFIGURAR DROPZONE PARA DEVOLVER
// ============================================================
function configurarDropzoneDevolver() {
    asignadosContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    });
    
    asignadosContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        if (!draggedItem) return;
        if (draggedItem.parentElement?.id !== 'asignadosContainer') return;
        
        devolverAModulos(draggedItem);
        draggedItem = null;
    });
}

// ============================================================
// CONFIGURAR DRAG EN MÓDULOS EXISTENTES
// ============================================================
function configurarDragModulosExistentes() {
    document.querySelectorAll('#listaModulos .modulo-item').forEach(mod => {
        const btn = mod.querySelector('button');
        if (btn && !btn.disabled) {
            configurarDragDrop(mod);
        }
    });
}

// ============================================================
// EVENTOS
// ============================================================

// Cambio de profesor
selectProfesor.addEventListener('change', async function() {
    profesorActualId = this.value;
    const nombreProfesor = this.options[this.selectedIndex]?.dataset.nombre || '';
    const horas = this.options[this.selectedIndex]?.dataset.horas || '0';
    
    horasProfesorSpan.textContent = horas + 'h';
    await cargarModulosAsignados(profesorActualId, nombreProfesor);
});

// Botones "Asignar"
listaModulosDiv.addEventListener('click', e => {
    const btn = e.target.closest('.btn-asignar');
    if (!btn) return;
    if (btn.disabled) return;
    const modulo = btn.closest('.modulo-item');
    moverAAsignados(modulo);
});

// Botones "Quitar"
asignadosContainer.addEventListener('click', e => {
    const btn = e.target.closest('.btn-quitar');
    if (!btn) return;
    const modulo = btn.closest('.modulo-item');
    devolverAModulos(modulo);
});

// Buscador
buscador.addEventListener('input', function() {
    const texto = this.value.toLowerCase();
    document.querySelectorAll('#listaModulos .modulo-item').forEach(mod => {
        const nombre = mod.dataset.nombre?.toLowerCase() || '';
        mod.style.display = nombre.includes(texto) ? 'flex' : 'none';
    });
});

// Guardar asignación
document.getElementById('guardarBtn').addEventListener('click', async () => {
    if (!profesorActualId) {
        alert('Selecciona un profesor primero');
        return;
    }
    
    if (horasAcumuladas > 20) {
        const confirmar = confirm(`⚠️ AVISO: El profesor tendrá ${horasAcumuladas} horas (supera el límite de 20).\n\n¿Deseas guardar igualmente?`);
        if (!confirmar) return;
    }
    
    const modulosAsignados = Array.from(asignadosContainer.children).map(mod => ({
        modulo_id: parseInt(mod.dataset.id)
    }));
    
    const guardarBtn = document.getElementById('guardarBtn');
    const textoOriginal = guardarBtn.textContent;
    guardarBtn.textContent = '💾 Guardando...';
    guardarBtn.disabled = true;
    
    try {
        const res = await fetch('/TFG/controladores/Controlador_asignarMod.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                asignaciones: modulosAsignados,
                profesor_id: parseInt(profesorActualId)
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
    } finally {
        guardarBtn.textContent = textoOriginal;
        guardarBtn.disabled = false;
    }
});

// ============================================================
// INICIALIZAR
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    configurarDropzone();
    configurarDropzoneDevolver();
    configurarDragModulosExistentes();
    
    // Cargar profesor por defecto si existe
    if (selectProfesor.value) {
        profesorActualId = selectProfesor.value;
        const nombreProfesor = selectProfesor.options[selectProfesor.selectedIndex]?.dataset.nombre || '';
        cargarModulosAsignados(profesorActualId, nombreProfesor);
    }
});
</script>

</body>
</html>