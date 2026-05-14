<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asignar módulos — FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --accent:   #e35f1f;
    --accent2:  #f7841a;
    --dark:     #1a1a2e;
    --light:    #f4f1eb;
    --white:    #ffffff;
    --muted:    #888;
    --border:   #e2dfd8;
    --success:  #2cb67d;
    --danger:   #e05252;
    --purple:   #7c3aed;
    --radius:   12px;
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--light);
    color: var(--dark);
    min-height: 100vh;
  }

  .navbar {
    background: var(--dark);
    padding: 0 28px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky; top: 0; z-index: 100;
  }
  .navbar-brand { font-family: 'DM Serif Display', serif; color: #fff; font-size: 1.1rem; }
  .navbar-brand span { color: var(--accent2); }
  .navbar-nav { display: flex; gap: 8px; align-items: center; }

  .btn {
    padding: 7px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-family: inherit;
    font-size: .85rem;
    font-weight: 500;
    transition: all .2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .btn-primary { background: var(--accent); color: #fff; }
  .btn-primary:hover { background: #c9531a; }
  .btn-primary:disabled { background: #ccc; cursor: not-allowed; }
  .btn-ghost { background: rgba(255,255,255,.09); color: #fff; }
  .btn-ghost:hover { background: rgba(255,255,255,.16); }
  .btn-success { background: var(--success); color: #fff; }
  .btn-success:hover { filter: brightness(1.1); }
  .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--dark); }
  .btn-outline:hover { background: var(--light); }

  .page { max-width: 1600px; margin: 0 auto; padding: 28px 20px; }
  .page-title { font-family: 'DM Serif Display', serif; font-size: 1.8rem; margin-bottom: 20px; }

  .alert { padding: 12px 16px; border-radius: var(--radius); font-size: .875rem; margin-bottom: 16px; border-left: 4px solid; }
  .alert-success { background: #edfaf4; color: #1f7a52; border-color: var(--success); }
  .alert-error   { background: #fdf0f0; color: #a03030; border-color: var(--danger); }
  .alert-warn    { background: #fff8e8; color: #8a5c00; border-color: var(--accent2); }

  .selector-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
  }
  .filter-group { display: flex; gap: 12px; align-items: center; background: var(--light); padding: 6px 16px; border-radius: 40px; }
  .filter-label { font-size: .75rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
  .filter-btn { padding: 4px 12px; border-radius: 24px; border: 1px solid var(--border); background: white; cursor: pointer; font-size: .8rem; transition: all .2s; }
  .filter-btn.active { background: var(--dark); color: white; border-color: var(--dark); }
  
  .selector-label { font-weight: 600; font-size: .85rem; text-transform: uppercase; color: var(--muted); }
  .select-wrap { position: relative; flex: 1; min-width: 260px; max-width: 440px; }
  .select-wrap::after { content: '▾'; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
  select { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: .9rem; background: var(--white); outline: none; appearance: none; }
  select:focus { border-color: var(--accent); }

  .horas-config-panel {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
  }
  .horas-config-panel.disabled { opacity: 0.5; pointer-events: none; background: #f5f5f5; }
  .horas-config-title { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: var(--muted); letter-spacing: .05em; }
  .horas-fields { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
  .horas-field { display: flex; flex-direction: column; gap: 4px; }
  .horas-field label { font-size: .7rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
  .horas-field input { width: 80px; padding: 8px 10px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: .9rem; font-weight: 600; text-align: center; }
  .horas-field.minimo input { border-color: var(--danger); }
  .horas-field.objetivo input { border-color: var(--success); }
  .horas-field.maximo input { border-color: var(--purple); }
  .btn-config { padding: 8px 20px; background: var(--dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
  .btn-config:hover { background: #2a2a4a; }
  .btn-config.saved { background: var(--success); }

  .horas-badge-large {
    background: var(--dark);
    border-radius: 40px;
    padding: 4px 20px 4px 24px;
    display: flex;
    align-items: baseline;
    gap: 12px;
    font-weight: 500;
    color: white;
  }
  .horas-badge-large span:first-child { font-size: .7rem; opacity: .8; text-transform: uppercase; letter-spacing: .05em; }
  .horas-badge-large strong { font-size: 1.5rem; font-weight: 700; margin: 0 4px; }

  /* MODIFICADO: Grid con proporción 2:1 para dar más espacio a disponibles */
  .asign-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
  @media (max-width: 900px) { .asign-grid { grid-template-columns: 1fr; } }
  .col-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); display: flex; flex-direction: column; height: calc(100vh - 380px); min-height: 550px; }
  .col-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; font-weight: 600; }
  .search-wrap { padding: 12px 16px; border-bottom: 1px solid var(--border); }
  .search-wrap input { width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px; outline: none; }
  .modulos-list { flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px; }
  
  .mod-card {
    border-radius: 8px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 4px solid;
    background: #fff8f4;
    border-color: var(--accent2);
    font-size: .875rem;
    transition: all .1s;
    cursor: grab;
  }
  .mod-card.dragging { opacity: 0.5; cursor: grabbing; }
  .mod-card.tipo-pspt { background: #f3f0ff; border-color: var(--purple); }
  .mod-card.ocupado { background: #fafafa; border-color: #ddd; opacity: 0.7; cursor: not-allowed; }
  .mod-info { flex: 1; }
  .mod-titulo { font-weight: 600; margin-bottom: 4px; }
  .mod-detalle { display: flex; gap: 12px; font-size: .7rem; color: #666; flex-wrap: wrap; }
  .mod-horas { font-weight: 700; color: var(--accent); white-space: nowrap; }
  .badge-cat { font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; background: #e2e3e8; }
  .badge-ocupante { background: #5b21b6; color: white; padding: 2px 8px; border-radius: 12px; font-size: .7rem; white-space: nowrap; }
  .badge-especialidad { font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; }
  .badge-sai { background: #dbeafe; color: #1e40af; }
  .badge-inf { background: #fce7f3; color: #9d174d; }
  .btn-asignar, .btn-quitar { border: none; border-radius: 6px; padding: 6px 12px; font-weight: 600; cursor: pointer; }
  .btn-asignar { background: var(--accent); color: white; }
  .btn-quitar { background: #fee2e2; color: var(--danger); }

  .resumen-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); margin-top: 28px; overflow: hidden; }
  .resumen-header { padding: 18px 24px; border-bottom: 1px solid var(--border); font-weight: 600; }
  .prof-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 0; }
  .prof-item { padding: 16px 20px; border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); }
  .prof-nombre { font-weight: 600; margin-bottom: 8px; }
  .mini-bar-bg { height: 6px; background: #eee; border-radius: 4px; overflow: hidden; margin: 8px 0; }
  .mini-bar-fill { height: 100%; border-radius: 4px; transition: width 0.3s, background 0.2s; }
  .prof-horas-label { font-size: .75rem; color: #666; margin-top: 6px; font-weight: 500; line-height: 1.4; }

  .save-bar { padding: 20px 0; display: flex; justify-content: flex-end; gap: 16px; }
  #toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%); background: var(--dark); color: white; padding: 12px 24px; border-radius: 40px; opacity: 0; transition: opacity .3s; z-index: 1000; pointer-events: none; }
  #toast.show { opacity: 1; }
  .empty-list { padding: 32px; text-align: center; color: var(--muted); }

  .loading { opacity: 0.6; pointer-events: none; }
</style>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: /asignaciones/');
    exit();
}

// Nota: Asegúrate de que estas variables estén definidas antes de incluir este archivo
// $profesoresConHoras = [...];
// $modulos = [...];
?>

<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <div class="navbar-nav">
    <a href="/asignaciones/?vista=admin" class="btn btn-ghost">← Panel admin</a>
    <a href="/asignaciones/?vista=logout" class="btn btn-ghost">Cerrar sesión</a>
  </div>
</nav>

<div class="page">
  <div class="page-title">Asignación de módulos</div>

  <div id="alertContainer"></div>

  <!-- Selector de profesor -->
  <div class="selector-card">
    <div class="filter-group">
      <span class="filter-label">Filtrar profesor por:</span>
      <button class="filter-btn" data-filtro="PT" onclick="toggleFiltroCategoria('PT')">Solo PT</button>
      <button class="filter-btn" data-filtro="PS" onclick="toggleFiltroCategoria('PS')">Solo PS</button>
      <button class="filter-btn" onclick="limpiarFiltroCategoria()" style="border-color:var(--danger); color:var(--danger);">✕ Quitar filtro</button>
    </div>
    
    <span class="selector-label">Profesor:</span>
    <div class="select-wrap">
      <select id="selectProfesor">
        <option value="0">🔍 -- Mostrar todos los módulos --</option>
        <?php if (isset($profesoresConHoras) && is_array($profesoresConHoras)): ?>
          <?php foreach ($profesoresConHoras as $p): ?>
            <option value="<?= $p['orden'] ?>"
                    data-horas="<?= $p['total_horas'] ?>"
                    data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                    data-categoria="<?= htmlspecialchars(strtoupper($p['categoria'] ?? '')) ?>">
              <?= htmlspecialchars($p['nombre']) ?> (<?= htmlspecialchars($p['categoria'] ?? '') ?>) · <?= $p['total_horas'] ?>h
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="horas-badge-large">
      <span>HORAS TOTALES</span>
      <strong id="horasActuales">0</strong>
      <span>h</span>
    </div>
    
    <button id="btnGuardarPrincipal" class="btn btn-success" onclick="guardarAsignacion()">💾 Guardar</button>
    <button id="btnMostrarTodos" class="btn btn-outline" onclick="toggleMostrarTodos()">📋 Mostrar todos los módulos</button>
  </div>

  <!-- Panel configuración horas objetivo - AHORA GLOBAL PARA TODOS LOS PROFESORES -->
  <div class="horas-config-panel" id="horasConfigPanel">
    <span class="horas-config-title">🎯 Configuración GLOBAL de horas objetivo (todos los profesores)</span>
    <div class="horas-fields">
      <div class="horas-field minimo">
        <label>Mínimo 🔴</label>
        <input type="number" id="horasMin" min="0" max="40" value="18" step="1">
      </div>
      <div class="horas-field objetivo">
        <label>Objetivo 🟢</label>
        <input type="number" id="horasObjetivo" min="0" max="40" value="20" step="1">
      </div>
      <div class="horas-field maximo">
        <label>Máximo 🟣</label>
        <input type="number" id="horasMax" min="0" max="40" value="22" step="1">
      </div>
    </div>
    <button class="btn-config" id="btnGuardarHoras" onclick="guardarConfigHorasGlobal()">Aplicar a todos</button>
  </div>

  <!-- Columnas de asignación -->
  <div class="asign-grid">
    <div class="col-card">
      <div class="col-header">
        <span>📚 Módulos disponibles</span>
        <span id="countDisp">0 módulos</span>
      </div>
      <div class="search-wrap"><input type="text" id="buscador" placeholder="Buscar módulo..." oninput="filtrarModulos()"></div>
      <div class="modulos-list" id="listaDisponibles"></div>
    </div>

    <div class="col-card">
      <div class="col-header">
        <span>📋 Asignados a <span id="nomProfesor">—</span></span>
        <span id="horasAsignadasLabel">0h</span>
      </div>
      <div class="modulos-list" id="listaAsignados"></div>
    </div>
  </div>

  <div class="save-bar">
    <button class="btn btn-primary" onclick="guardarAsignacion()">💾 Guardar cambios</button>
  </div>

  <!-- Resumen inferior -->
  <div class="resumen-card">
    <div class="resumen-header">👨‍🏫 Resumen de carga horaria por profesor</div>
    <div class="prof-grid" id="resumenGrid">
      <?php if (isset($profesoresConHoras) && is_array($profesoresConHoras)): ?>
        <?php foreach ($profesoresConHoras as $p): ?>
          <div class="prof-item" data-orden="<?= $p['orden'] ?>">
            <div class="prof-nombre"><?= htmlspecialchars($p['nombre']) ?></div>
            <div class="prof-cat" style="font-size:.7rem;color:#888;"><?= htmlspecialchars($p['categoria'] ?? '') ?></div>
            <div class="mini-bar-bg"><div class="mini-bar-fill" style="width:0%"></div></div>
            <div class="prof-horas-label" id="profLabel_<?= $p['orden'] ?>">--h</div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="toast"></div>

<script>
// Variables globales
const TODOS_MODULOS = <?php echo isset($modulos) ? json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) : '[]'; ?>;
const BASE_URL = '/asignaciones';
const PROFESORES_MAP = <?php echo isset($profesoresConHoras) ? json_encode(array_column($profesoresConHoras, 'nombre', 'orden'), JSON_UNESCAPED_UNICODE) : '{}'; ?>;

let modulosEstado = [];
let profesorActual = 0;
let categoriaProfesor = '';
let modulosAsignados = [];
let filtroCategoriaActivo = null;
let mostrarTodosModulos = false;

// Configuración GLOBAL de horas (para todos los profesores)
let horasConfigGlobal = { min: 18, objetivo: 20, max: 22 };

const STORAGE_KEY = 'horasConfigGlobal';

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    cargarConfigStorage();
    inicializarModulos();
    
    const selectProfesor = document.getElementById('selectProfesor');
    if (selectProfesor) {
        selectProfesor.addEventListener('change', cargarProfesor);
    }
    
    cargarProfesor();
    setupDragAndDrop();
});

function inicializarModulos() {
    modulosEstado = TODOS_MODULOS.map(m => ({
        ...m,
        asignado_a_profe: false,
        asignado_a_otro: false
    }));
}

function cargarConfigStorage() {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) {
            const parsed = JSON.parse(stored);
            horasConfigGlobal = { ...horasConfigGlobal, ...parsed };
        }
    } catch(e) {
        console.error('Error loading config:', e);
    }
    // Actualizar inputs con valores cargados
    document.getElementById('horasMin').value = horasConfigGlobal.min;
    document.getElementById('horasObjetivo').value = horasConfigGlobal.objetivo;
    document.getElementById('horasMax').value = horasConfigGlobal.max;
}

function guardarConfigStorage() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(horasConfigGlobal));
}

function guardarConfigHorasGlobal() {
    const min = parseInt(document.getElementById('horasMin').value) || 18;
    const objetivo = parseInt(document.getElementById('horasObjetivo').value) || 20;
    const max = parseInt(document.getElementById('horasMax').value) || 22;
    
    if (min > objetivo || objetivo > max) {
        mostrarToast('Los valores deben cumplir: mínimo ≤ objetivo ≤ máximo', 'error');
        return;
    }
    
    horasConfigGlobal = { min, objetivo, max };
    guardarConfigStorage();
    
    // Actualizar resumen completo
    actualizarResumenHorasLocal();
    
    const btn = document.getElementById('btnGuardarHoras');
    const originalText = btn.textContent;
    btn.textContent = '✅ Aplicado a todos';
    btn.classList.add('saved');
    setTimeout(() => {
        btn.textContent = originalText;
        btn.classList.remove('saved');
    }, 1500);
    mostrarToast(`Configuración global aplicada: min ${min} / obj ${objetivo} / max ${max}`, 'ok');
}

function getHorasConfig() {
    return horasConfigGlobal;
}

function calcularColorPorHoras(horas) {
    const cfg = getHorasConfig();
    if (horas < cfg.min) return '#e05252';
    if (horas < cfg.objetivo) return '#f7841a';
    if (horas <= cfg.max) return '#2cb67d';
    return '#7c3aed';
}

function obtenerTextoAviso(horas) {
    const cfg = getHorasConfig();
    if (horas < cfg.min) return `🔴 Por debajo del mínimo (${cfg.min}h)`;
    if (horas < cfg.objetivo) return `⚠️ Faltan ${cfg.objetivo - horas}h para objetivo`;
    if (horas === cfg.objetivo) return `✅ Objetivo cumplido (${cfg.objetivo}h)`;
    if (horas <= cfg.max) return `✅ En rango (+${horas - cfg.objetivo}h sobre objetivo)`;
    return `⚠️ Supera el máximo (+${horas - cfg.max}h)`;
}

function obtenerEspecialidadModulo(categoria) {
    const cat = (categoria || '').toUpperCase();
    if (cat === 'SAI') return { clase: 'badge-sai', texto: 'SAI' };
    if (cat === 'INF') return { clase: 'badge-inf', texto: 'INF' };
    return null;
}

function toggleMostrarTodos() {
    mostrarTodosModulos = !mostrarTodosModulos;
    const btn = document.getElementById('btnMostrarTodos');
    if (btn) {
        btn.style.background = mostrarTodosModulos ? 'var(--accent2)' : '';
        btn.style.color = mostrarTodosModulos ? 'white' : '';
    }
    if (profesorActual !== 0) {
        renderListasPorProfesor();
    }
}

function actualizarResumenHorasLocal() {
    const horasPorProf = {};
    
    // Calcular horas desde módulos estado
    modulosEstado.forEach(modulo => {
        if (modulo.profesor_id && modulo.profesor_id !== null && modulo.profesor_id !== 0) {
            const profId = modulo.profesor_id;
            const horasMod = parseInt(modulo.horas) || 0;
            horasPorProf[profId] = (horasPorProf[profId] || 0) + horasMod;
        }
    });
    
    const cfg = getHorasConfig();
    
    // Actualizar resumen en la UI
    document.querySelectorAll('.prof-item[data-orden]').forEach(el => {
        const orden = parseInt(el.dataset.orden);
        const horas = horasPorProf[orden] || 0;
        const pct = cfg.objetivo > 0 ? Math.min(100, Math.round(horas / cfg.objetivo * 100)) : 0;
        const color = calcularColorPorHoras(horas);
        const aviso = obtenerTextoAviso(horas);
        
        const fill = el.querySelector('.mini-bar-fill');
        const label = el.querySelector('.prof-horas-label');
        if (fill) {
            fill.style.width = pct + '%';
            fill.style.background = color;
        }
        if (label) {
            label.innerHTML = `${horas}h / ${cfg.objetivo}h objetivo<br><span style="color:${color}; font-size:.7rem;">${aviso}</span>`;
        }
    });
}

function setupDragAndDrop() {
    const listaDisp = document.getElementById('listaDisponibles');
    const listaAsig = document.getElementById('listaAsignados');
    
    [listaDisp, listaAsig].forEach(zona => {
        if (zona) {
            zona.addEventListener('dragover', e => e.preventDefault());
            zona.addEventListener('drop', manejarDrop);
        }
    });
}

function manejarDragStart(e) {
    const card = e.target.closest('.mod-card');
    if (card && card.dataset.id) {
        e.dataTransfer.setData('text/plain', card.dataset.id);
        card.classList.add('dragging');
    }
}

function manejarDragEnd(e) {
    const card = e.target.closest('.mod-card');
    if (card) card.classList.remove('dragging');
}

function manejarDrop(e) {
    e.preventDefault();
    const id = parseInt(e.dataTransfer.getData('text/plain'));
    const zonaDestino = e.currentTarget.id;
    
    if (zonaDestino === 'listaAsignados') {
        asignar(id);
    } else if (zonaDestino === 'listaDisponibles') {
        quitar(id);
    }
}

async function cargarProfesor() {
    const sel = document.getElementById('selectProfesor');
    if (!sel) return;
    
    profesorActual = parseInt(sel.value) || 0;
    const nomSpan = document.getElementById('nomProfesor');
    const horasElement = document.getElementById('horasActuales');
    
    if (profesorActual === 0) {
        if (nomSpan) nomSpan.textContent = '— Vista global —';
        if (horasElement) {
            horasElement.innerHTML = '0';
            horasElement.style.color = '#e05252';
        }
        
        try {
            mostrarLoading(true);
            const res = await fetch(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=0`);
            const data = await res.json();
            if (data.ok) {
                modulosEstado = data.modulos.map(m => ({...m}));
                modulosAsignados = [];
                renderListasGlobal();
                actualizarResumenHorasLocal();
            } else {
                mostrarToast(data.mensaje || 'Error al cargar vista global', 'error');
            }
        } catch(e) {
            console.error('Error:', e);
            mostrarToast('Error al cargar vista global', 'error');
        } finally {
            mostrarLoading(false);
        }
        return;
    }
    
    const opt = sel.options[sel.selectedIndex];
    if (nomSpan) nomSpan.innerHTML = opt.dataset.nombre || '';
    categoriaProfesor = (opt.dataset.categoria || '').toUpperCase();
    
    try {
        mostrarLoading(true);
        const res = await fetch(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=${profesorActual}`);
        const data = await res.json();
        
        if (!data.ok) throw new Error(data.mensaje || 'Error al cargar módulos');
        
        modulosEstado = data.modulos.map(m => ({...m}));
        modulosAsignados = modulosEstado.filter(m => m.asignado_a_profe).map(m => parseInt(m.id));
        renderListasPorProfesor();
        actualizarResumenHorasLocal();
        
        // Actualizar badge de horas
        const horasAcum = modulosAsignados.reduce((total, id) => {
            const mod = modulosEstado.find(m => m.id == id);
            return total + (parseInt(mod?.horas) || 0);
        }, 0);
        
        if (horasElement) {
            horasElement.innerHTML = horasAcum;
            horasElement.style.color = calcularColorPorHoras(horasAcum);
        }
        
        // Actualizar opción del select
        if (opt) {
            opt.dataset.horas = horasAcum;
            opt.textContent = opt.textContent.replace(/·\s*\d+h/, `· ${horasAcum}h`);
        }
        
    } catch(e) {
        console.error('Error:', e);
        mostrarToast(e.message, 'error');
    } finally {
        mostrarLoading(false);
    }
}

function renderListasGlobal() {
    const busq = document.getElementById('buscador')?.value.toLowerCase() || '';
    let disponibles = modulosEstado;
    
    if (busq) {
        disponibles = disponibles.filter(m => 
            (m.nombre_modulo || '').toLowerCase().includes(busq) || 
            (m.grado || '').toLowerCase().includes(busq)
        );
    }
    
    const countSpan = document.getElementById('countDisp');
    const listaDisp = document.getElementById('listaDisponibles');
    const listaAsig = document.getElementById('listaAsignados');
    
    if (countSpan) countSpan.innerHTML = disponibles.length + ' módulos';
    if (listaDisp) {
        listaDisp.innerHTML = disponibles.length ? 
            disponibles.map(m => tarjetaGlobal(m)).join('') : 
            '<div class="empty-list">No hay módulos.</div>';
    }
    if (listaAsig) {
        listaAsig.innerHTML = '<div class="empty-list">Selecciona un profesor para ver sus asignaciones.</div>';
    }
    
    attachDragEvents();
}

function renderListasPorProfesor() {
    const busq = document.getElementById('buscador')?.value.toLowerCase() || '';
    
    let disponibles = modulosEstado.filter(m => !m.asignado_a_profe);
    
    // Aplicar filtro de categoría si es necesario
    if (!mostrarTodosModulos && profesorActual !== 0 && categoriaProfesor) {
        disponibles = disponibles.filter(m => {
            const catMod = (m.categoria || '').toUpperCase();
            if (categoriaProfesor === 'PT') return catMod === 'SAI';
            if (categoriaProfesor === 'PS') return catMod === 'INF';
            return true;
        });
    }
    
    if (busq) {
        disponibles = disponibles.filter(m => 
            (m.nombre_modulo || '').toLowerCase().includes(busq) || 
            (m.grado || '').toLowerCase().includes(busq)
        );
    }
    
    const asignados = modulosEstado.filter(m => m.asignado_a_profe);
    const horasAcum = asignados.reduce((s, m) => s + (parseInt(m.horas) || 0), 0);
    
    const countSpan = document.getElementById('countDisp');
    const listaDisp = document.getElementById('listaDisponibles');
    const listaAsig = document.getElementById('listaAsignados');
    const horasAsignadasLabel = document.getElementById('horasAsignadasLabel');
    const horasElement = document.getElementById('horasActuales');
    
    if (countSpan) countSpan.innerHTML = disponibles.length + ' módulos';
    if (listaDisp) {
        listaDisp.innerHTML = disponibles.length ? 
            disponibles.map(m => tarjetaProfesor(m, 'asignar')).join('') : 
            '<div class="empty-list">No hay módulos disponibles.</div>';
    }
    if (listaAsig) {
        listaAsig.innerHTML = asignados.length ? 
            asignados.map(m => tarjetaProfesor(m, 'quitar')).join('') : 
            '<div class="empty-list">Sin módulos asignados.</div>';
    }
    if (horasAsignadasLabel) horasAsignadasLabel.innerHTML = `${horasAcum}h`;
    if (horasElement) {
        horasElement.innerHTML = horasAcum;
        horasElement.style.color = calcularColorPorHoras(horasAcum);
    }
    
    attachDragEvents();
}

function tarjetaGlobal(m) {
    const ocupadoPor = m.profesor_id && m.profesor_id !== 0 ? (PROFESORES_MAP[m.profesor_id] || null) : null;
    const especialidad = obtenerEspecialidadModulo(m.categoria);
    const badgeEsp = especialidad ? `<span class="badge-especialidad ${especialidad.clase}">${especialidad.texto}</span>` : '';
    
    return `
    <div class="mod-card ${m.es_pspt ? 'tipo-pspt' : ''}" data-id="${m.id}" draggable="false" style="cursor:default; opacity:0.8">
      <div class="mod-info">
        <div class="mod-titulo">${escapeHtml(m.nombre_modulo || '')}</div>
        <div class="mod-detalle">
          <span>${escapeHtml(m.grado || '')}</span>
          <span class="mod-horas">${m.horas || 0}h</span>
          ${badgeEsp}
        </div>
      </div>
      ${ocupadoPor ? `<span class="badge-ocupante">👤 ${escapeHtml(ocupadoPor)}</span>` : '<span class="badge-cat">Libre</span>'}
    </div>`;
}

function tarjetaProfesor(m, accion) {
    const yaAsignado = m.asignado_a_profe;
    const ocupadoOtro = m.asignado_a_otro;
    const isDraggable = !ocupadoOtro && !yaAsignado && accion === 'asignar';
    const especialidad = obtenerEspecialidadModulo(m.categoria);
    const badgeEsp = especialidad ? `<span class="badge-especialidad ${especialidad.clase}">${especialidad.texto}</span>` : '';
    
    let btn = '';
    if (!ocupadoOtro) {
        btn = accion === 'asignar' 
            ? `<button class="btn-asignar" onclick="asignar(${m.id})" ${yaAsignado ? 'disabled' : ''}>${yaAsignado ? '✓ Asignado' : '+ Asignar'}</button>`
            : `<button class="btn-quitar" onclick="quitar(${m.id})">✕ Quitar</button>`;
    }
    
    const badgeOcupante = ocupadoOtro ? `<span class="badge-ocupante">👤 ${escapeHtml(PROFESORES_MAP[m.profesor_id] || 'Otro')}</span>` : '';
    
    return `
    <div class="mod-card ${m.es_pspt ? 'tipo-pspt' : ''} ${ocupadoOtro ? 'ocupado' : ''}" data-id="${m.id}" draggable="${isDraggable}" style="${!isDraggable ? 'cursor:default;' : ''}">
      <div class="mod-info">
        <div class="mod-titulo">${escapeHtml(m.nombre_modulo || '')}</div>
        <div class="mod-detalle">
          <span>${escapeHtml(m.grado || '')}</span>
          <span class="mod-horas">${m.horas || 0}h</span>
          ${badgeEsp}
        </div>
      </div>
      ${badgeOcupante}${btn}
    </div>`;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function attachDragEvents() {
    document.querySelectorAll('.mod-card[draggable="true"]').forEach(el => {
        el.removeEventListener('dragstart', manejarDragStart);
        el.removeEventListener('dragend', manejarDragEnd);
        el.addEventListener('dragstart', manejarDragStart);
        el.addEventListener('dragend', manejarDragEnd);
    });
}

function asignar(id) {
    if (modulosAsignados.includes(id)) return;
    
    const mod = modulosEstado.find(m => m.id == id);
    if (mod && mod.asignado_a_otro) {
        mostrarToast('Módulo ya asignado a otro profesor', 'error');
        return;
    }
    
    modulosAsignados.push(id);
    actualizarEstadoLocal();
}

function quitar(id) {
    modulosAsignados = modulosAsignados.filter(x => x !== id);
    actualizarEstadoLocal();
}

function actualizarEstadoLocal() {
    modulosEstado = modulosEstado.map(m => ({
        ...m,
        asignado_a_profe: modulosAsignados.includes(m.id),
        asignado_a_otro: (m.profesor_id !== null && m.profesor_id !== profesorActual && m.profesor_id !== 0 && !modulosAsignados.includes(m.id))
    }));
    
    renderListasPorProfesor();
    actualizarResumenHorasLocal();
}

function filtrarModulos() {
    if (profesorActual === 0) {
        renderListasGlobal();
    } else {
        renderListasPorProfesor();
    }
}

async function guardarAsignacion() {
    if (profesorActual === 0) {
        mostrarToast('Selecciona un profesor para guardar.', 'warn');
        return;
    }
    
    const btn = document.getElementById('btnGuardarPrincipal');
    if (btn) btn.disabled = true;
    
    const asignaciones = modulosAsignados.map(id => ({ modulo_id: id }));
    
    try {
        const res = await fetch(`${BASE_URL}/controladores/Controlador_asignarMod.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ profesor_id: profesorActual, asignaciones })
        });
        
        const data = await res.json();
        mostrarToast(data.mensaje, data.supera_20 ? 'warn' : (data.ok ? 'ok' : 'error'));
        
        if (data.ok) {
            await cargarProfesor();
            actualizarResumenHorasLocal();
        }
    } catch(e) {
        console.error('Error:', e);
        mostrarToast('Error de red al guardar', 'error');
    } finally {
        if (btn) btn.disabled = false;
    }
}

function toggleFiltroCategoria(tipo) {
    const btns = document.querySelectorAll('.filter-btn');
    btns.forEach(btn => btn.classList.remove('active'));
    
    if (filtroCategoriaActivo === tipo) {
        filtroCategoriaActivo = null;
    } else {
        filtroCategoriaActivo = tipo;
        const activeBtn = document.querySelector(`.filter-btn[data-filtro="${tipo}"]`);
        if (activeBtn) activeBtn.classList.add('active');
    }
    aplicarFiltroSelect();
}

function limpiarFiltroCategoria() {
    filtroCategoriaActivo = null;
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    aplicarFiltroSelect();
}

function aplicarFiltroSelect() {
    const select = document.getElementById('selectProfesor');
    if (!select) return;
    
    const options = select.querySelectorAll('option');
    options.forEach(opt => {
        if (opt.value === '0') return;
        const cat = (opt.dataset.categoria || '').toUpperCase();
        let mostrar = true;
        if (filtroCategoriaActivo) {
            mostrar = cat.includes(filtroCategoriaActivo);
        }
        opt.style.display = mostrar ? '' : 'none';
    });
    
    const selected = select.options[select.selectedIndex];
    if (selected && selected.style.display === 'none') {
        const primerVisible = [...options].find(o => o.value && o.style.display !== 'none');
        select.value = primerVisible ? primerVisible.value : '0';
        cargarProfesor();
    } else if (selected && selected.value !== '0') {
        cargarProfesor();
    } else {
        cargarProfesor();
    }
}

function mostrarLoading(show) {
    const container = document.querySelector('.asign-grid');
    if (container) {
        if (show) {
            container.classList.add('loading');
        } else {
            container.classList.remove('loading');
        }
    }
}

let toastTimer;
function mostrarToast(msg, tipo = 'ok') {
    const t = document.getElementById('toast');
    if (!t) return;
    
    t.textContent = msg;
    t.className = 'show';
    
    if (tipo === 'warn') {
        t.style.background = '#f7841a';
    } else if (tipo === 'error') {
        t.style.background = '#e05252';
    } else {
        t.style.background = '#1a1a2e';
    }
    
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 3500);
}
</script>
</body>
</html>