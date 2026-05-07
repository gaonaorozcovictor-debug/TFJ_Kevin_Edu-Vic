<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asignar módulos — FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
  /* --- ESTILOS (Mantén los tuyos, pero actualizamos algunos) --- */
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

  /* Navbar */
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
  .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--dark); }
  .btn-outline:hover { background: var(--light); border-color: var(--accent); }

  /* Layout */
  .page { max-width: 1400px; margin: 0 auto; padding: 28px 20px; }
  .page-title { font-family: 'DM Serif Display', serif; font-size: 1.8rem; margin-bottom: 20px; }

  /* Alertas */
  .alert { padding: 12px 16px; border-radius: var(--radius); font-size: .875rem; margin-bottom: 16px; border-left: 4px solid; }
  .alert-success { background: #edfaf4; color: #1f7a52; border-color: var(--success); }
  .alert-error   { background: #fdf0f0; color: #a03030; border-color: var(--danger); }
  .alert-warn    { background: #fff8e8; color: #8a5c00; border-color: var(--accent2); }

  /* Selector de profesor - MEJORADO */
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
  
  /* BADGE HORAS GRANDE */
  .horas-badge-large {
    background: #1a1a2e; color: white; border-radius: 40px; padding: 4px 16px 4px 24px;
    display: flex; align-items: baseline; gap: 12px; font-weight: 500;
  }
  .horas-badge-large span:first-child { font-size: .7rem; opacity: .7; text-transform: uppercase; }
  .horas-badge-large strong { font-size: 1.5rem; font-weight: 700; color: var(--accent2); margin: 0 4px; }

  /* Columnas de asignación */
  .asign-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media (max-width: 900px) { .asign-grid { grid-template-columns: 1fr; } }
  .col-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); display: flex; flex-direction: column; height: calc(100vh - 260px); }
  .col-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; font-weight: 600; }
  .search-wrap { padding: 12px 16px; border-bottom: 1px solid var(--border); }
  .search-wrap input { width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px; outline: none; }
  .modulos-list { flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px; }
  
  /* Tarjetas de módulo */
  .mod-card { border-radius: 8px; padding: 12px; display: flex; align-items: center; gap: 12px; border-left: 4px solid; background: #fff8f4; border-color: var(--accent2); font-size: .875rem; transition: all .1s; cursor: grab; }
  .mod-card.dragging { opacity: 0.5; cursor: grabbing; }
  .mod-card.tipo-pspt { background: #f3f0ff; border-color: var(--purple); }
  .mod-card.ocupado { background: #fafafa; border-color: #ddd; opacity: 0.7; cursor: not-allowed; }
  .mod-info { flex: 1; }
  .mod-titulo { font-weight: 600; margin-bottom: 4px; }
  .mod-detalle { display: flex; gap: 12px; font-size: .7rem; color: #666; }
  .mod-horas { font-weight: 700; color: var(--accent); white-space: nowrap; }
  .badge-cat { font-size: .65rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; background: #e2e3e8; }
  .badge-ocupante { background: #5b21b6; color: white; padding: 2px 8px; border-radius: 12px; font-size: .7rem; white-space: nowrap; }
  .btn-asignar, .btn-quitar { border: none; border-radius: 6px; padding: 6px 12px; font-weight: 600; cursor: pointer; }
  .btn-asignar { background: var(--accent); color: white; }
  .btn-quitar { background: #fee2e2; color: var(--danger); }

  /* Resumen inferior MEJORADO */
  .resumen-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); margin-top: 28px; overflow: hidden; }
  .resumen-header { padding: 18px 24px; border-bottom: 1px solid var(--border); font-weight: 600; }
  .prof-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0; }
  .prof-item { padding: 16px 20px; border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); }
  .prof-nombre { font-weight: 600; margin-bottom: 8px; }
  .mini-bar-bg { height: 6px; background: #eee; border-radius: 4px; overflow: hidden; margin: 8px 0; }
  .mini-bar-fill { height: 100%; border-radius: 4px; transition: width 0.3s; }
  .prof-horas-label { font-size: .75rem; color: #666; margin-top: 6px; font-weight: 500; }

  .save-bar { padding: 20px 0; display: flex; justify-content: flex-end; gap: 16px; }
  #toast { position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%); background: var(--dark); color: white; padding: 12px 24px; border-radius: 40px; opacity: 0; transition: opacity .3s; z-index: 1000; pointer-events: none; }
  #toast.show { opacity: 1; }
</style>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: /asignaciones/');
    exit();
}
// Aseguramos que $profesoresConHoras esté definido desde index.php
if (!isset($profesoresConHoras)) $profesoresConHoras = [];
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

  <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
    <?php unset($_SESSION['mensaje']); ?>
  <?php endif; ?>

  <!-- SELECTOR MEJORADO -->
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
        <?php foreach ($profesoresConHoras as $p): ?>
          <option value="<?= $p['orden'] ?>"
                  data-horas="<?= $p['total_horas'] ?>"
                  data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                  data-categoria="<?= htmlspecialchars(strtoupper($p['categoria'] ?? '')) ?>">
            <?= htmlspecialchars($p['nombre']) ?> (<?= htmlspecialchars($p['categoria'] ?? '') ?>) · <?= $p['total_horas'] ?>h
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- BADGE HORAS GRANDE -->
    <div class="horas-badge-large">
      <span>Horas asignadas</span>
      <strong id="horasActuales">0</strong>
      <span>h</span>
    </div>
    
    <button id="btnGuardar" class="btn btn-success" onclick="guardarAsignacion()">💾 Guardar</button>
  </div>

  <!-- COLUMNAS -->
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
        <span id="horasAcum" style="font-weight:700;">0h</span>
      </div>
      <div class="modulos-list" id="listaAsignados"></div>
    </div>
  </div>

  <div class="save-bar">
    <button class="btn btn-primary" onclick="guardarAsignacion()">💾 Guardar cambios</button>
  </div>

  <!-- RESUMEN INFERIOR (COLORES CORREGIDOS) -->
  <div class="resumen-card">
    <div class="resumen-header">👨‍🏫 Resumen de carga horaria por profesor</div>
    <div class="prof-grid" id="resumenGrid">
      <?php foreach ($profesoresConHoras as $p): 
        $horas = $p['total_horas'];
        // LÓGICA DE COLORES CORREGIDA
        if ($horas > 20) $color = '#e05252';      // Rojo: Supera el límite
        elseif ($horas >= 17) $color = '#f7841a'; // Naranja: Cerca del límite (17-20)
        else $color = '#2cb67d';                   // Verde: Normal (≤16)
        $pct = min(100, round($horas / 20 * 100));
      ?>
      <div class="prof-item" data-orden="<?= $p['orden'] ?>">
        <div class="prof-nombre"><?= htmlspecialchars($p['nombre']) ?></div>
        <div class="prof-cat" style="font-size:.7rem;color:#888;"><?= htmlspecialchars($p['categoria'] ?? '') ?></div>
        <div class="mini-bar-bg"><div class="mini-bar-fill" style="width:<?= $pct ?>%;background:<?= $color ?>"></div></div>
        <div class="prof-horas-label"><?= $horas ?>h / 20h</div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<div id="toast"></div>

<script>
// --- VARIABLES GLOBALES ---
const TODOS_MODULOS = <?= json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) ?>;
const BASE_URL = '/asignaciones';
const PROFESORES_MAP = <?= json_encode(array_column($profesoresConHoras, 'nombre', 'orden'), JSON_UNESCAPED_UNICODE) ?>;

let modulosEstado = TODOS_MODULOS.map(m => ({...m}));
let profesorActual = 0; // 0 = "Ver todos"
let categoriaProfesor = '';
let modulosAsignados = [];
let filtroCategoriaActivo = null; // 'PT', 'PS', o null

// --- INICIALIZACIÓN ---
document.getElementById('selectProfesor').addEventListener('change', cargarProfesor);
cargarProfesor(); // Cargar con "Ver todos" (profesor_id = 0)

// --- DRAG & DROP ---
const listaDisp = document.getElementById('listaDisponibles');
const listaAsig = document.getElementById('listaAsignados');
[listaDisp, listaAsig].forEach(zona => {
    zona.addEventListener('dragover', e => e.preventDefault());
    zona.addEventListener('drop', manejarDrop);
});

function manejarDragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.closest('.mod-card').dataset.id);
    e.target.closest('.mod-card').classList.add('dragging');
}
function manejarDragEnd(e) { e.target.closest('.mod-card')?.classList.remove('dragging'); }
function manejarDrop(e) {
    e.preventDefault();
    const id = parseInt(e.dataTransfer.getData('text/plain'));
    const zonaDestino = e.currentTarget.id;
    if (zonaDestino === 'listaAsignados') asignar(id);
    else if (zonaDestino === 'listaDisponibles') quitar(id);
}

// --- CARGAR PROFESOR (SOPORTA ID=0) ---
async function cargarProfesor() {
    const sel = document.getElementById('selectProfesor');
    profesorActual = parseInt(sel.value) || 0;

    if (profesorActual === 0) {
        document.getElementById('nomProfesor').textContent = '— Vista global —';
        document.getElementById('horasActuales').innerHTML = '0';
        try {
            const res = await fetch(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=0`);
            const data = await res.json();
            if (data.ok) {
                modulosEstado = data.modulos.map(m => ({...m}));
                modulosAsignados = [];
                renderListasGlobal();
            }
        } catch(e) { mostrarToast('Error al cargar vista global', 'error'); }
        return;
    }

    const opt = sel.options[sel.selectedIndex];
    document.getElementById('nomProfesor').innerHTML = opt.dataset.nombre || '';
    document.getElementById('horasActuales').innerHTML = opt.dataset.horas || '0';
    categoriaProfesor = (opt.dataset.categoria || '').toUpperCase();

    try {
        const res = await fetch(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=${profesorActual}`);
        const data = await res.json();
        if (!data.ok) throw new Error(data.mensaje);
        modulosEstado = data.modulos.map(m => ({...m}));
        modulosAsignados = modulosEstado.filter(m => m.asignado_a_profe).map(m => parseInt(m.id));
        renderListasPorProfesor();
    } catch(e) { mostrarToast(e.message, 'error'); }
}

// --- RENDER VISTA GLOBAL (Profesor ID = 0) ---
function renderListasGlobal() {
    const busq = document.getElementById('buscador').value.toLowerCase();
    let disponibles = modulosEstado.filter(m => !m.asignado_a_profe); // En vista global, nadie tiene "asignado_a_profe"
    if (busq) disponibles = disponibles.filter(m => m.nombre_modulo.toLowerCase().includes(busq) || (m.grado || '').toLowerCase().includes(busq));
    
    document.getElementById('countDisp').innerHTML = disponibles.length + ' módulos';
    document.getElementById('listaDisponibles').innerHTML = disponibles.length ? disponibles.map(m => tarjetaGlobal(m)).join('') : '<div class="empty-list">No hay módulos.</div>';
    document.getElementById('listaAsignados').innerHTML = '<div class="empty-list">Selecciona un profesor para asignar módulos.</div>';
    attachDragEvents();
}

// --- RENDER POR PROFESOR (con filtro de especialidad + botón "mostrar todos") ---
let mostrarTodosModulos = false; // Nuevo estado para anular el filtro de especialidad

function renderListasPorProfesor() {
    const busq = document.getElementById('buscador').value.toLowerCase();
    
    // Aplicar lógica de compatibilidad por categoría (SAI para PT, INF para PS) SOLO si NO estamos en modo "mostrar todos"
    let disponibles = modulosEstado.filter(m => !m.asignado_a_profe);
    if (!mostrarTodosModulos && profesorActual !== 0 && categoriaProfesor) {
        disponibles = disponibles.filter(m => {
            const catMod = (m.categoria || '').toUpperCase();
            if (categoriaProfesor.includes('PT')) return catMod === 'SAI';
            if (categoriaProfesor.includes('PS')) return catMod === 'INF';
            return true;
        });
    }
    
    if (busq) disponibles = disponibles.filter(m => m.nombre_modulo.toLowerCase().includes(busq) || (m.grado || '').toLowerCase().includes(busq));
    
    const asignados = modulosEstado.filter(m => m.asignado_a_profe);
    const horasAcum = asignados.reduce((s, m) => s + parseInt(m.horas || 0), 0);
    const overload = horasAcum > 20;
    
    document.getElementById('countDisp').innerHTML = disponibles.length + ' módulos';
    document.getElementById('listaDisponibles').innerHTML = disponibles.length ? disponibles.map(m => tarjetaProfesor(m, 'asignar')).join('') : '<div class="empty-list">No hay módulos disponibles.</div>';
    document.getElementById('listaAsignados').innerHTML = asignados.length ? asignados.map(m => tarjetaProfesor(m, 'quitar')).join('') : '<div class="empty-list">Sin módulos asignados.</div>';
    document.getElementById('horasAcum').innerHTML = horasAcum + 'h' + (overload ? ' ⚠️' : '');
    document.getElementById('horasAcum').style.color = overload ? '#e05252' : 'var(--accent)';
    
    attachDragEvents();
}

// Botón para mostrar TODOS los módulos (desactivar filtro de especialidad)
function toggleMostrarTodos() {
    mostrarTodosModulos = !mostrarTodosModulos;
    renderListasPorProfesor();
    const btn = document.getElementById('btnMostrarTodos');
    if (btn) btn.style.opacity = mostrarTodosModulos ? '0.6' : '1';
}

// --- TARJETAS (GLOBAL Y POR PROFESOR) ---
function tarjetaGlobal(m) {
    const ocupadoPor = m.profesor_id ? PROFESORES_MAP[m.profesor_id] : null;
    return `
    <div class="mod-card ${m.es_pspt ? 'tipo-pspt' : ''}" data-id="${m.id}" draggable="false" style="cursor:default; opacity:0.8">
      <div class="mod-info"><div class="mod-titulo">${esc(m.nombre_modulo)}</div>
      <div class="mod-detalle"><span>${esc(m.grado || '')}</span><span class="mod-horas">${m.horas}h</span></div></div>
      ${ocupadoPor ? `<span class="badge-ocupante">👤 ${esc(ocupadoPor)}</span>` : '<span class="badge-cat">Libre</span>'}
    </div>`;
}

function tarjetaProfesor(m, accion) {
    const yaAsignado = m.asignado_a_profe;
    const ocupadoOtro = m.asignado_a_otro;
    const isDraggable = !ocupadoOtro && !yaAsignado && accion === 'asignar';
    let btn = '';
    if (!ocupadoOtro) {
        btn = accion === 'asignar' 
            ? `<button class="btn-asignar" onclick="asignar(${m.id})" ${yaAsignado ? 'disabled' : ''}>${yaAsignado ? '✓ Asignado' : '+ Asignar'}</button>`
            : `<button class="btn-quitar" onclick="quitar(${m.id})">✕ Quitar</button>`;
    }
    const badgeOcupante = ocupadoOtro ? `<span class="badge-ocupante">👤 ${esc(PROFESORES_MAP[m.profesor_id] || 'Otro')}</span>` : '';
    return `
    <div class="mod-card ${m.es_pspt ? 'tipo-pspt' : ''} ${ocupadoOtro ? 'ocupado' : ''}" data-id="${m.id}" draggable="${isDraggable}" style="${!isDraggable ? 'cursor:default;' : ''}">
      <div class="mod-info"><div class="mod-titulo">${esc(m.nombre_modulo)}</div>
      <div class="mod-detalle"><span>${esc(m.grado || '')}</span><span class="mod-horas">${m.horas}h</span></div></div>
      ${badgeOcupante}${btn}
    </div>`;
}

function esc(s) { return String(s || '').replace(/[&<>]/g, function(m){if(m==='&') return '&amp;'; if(m==='<') return '&lt;'; if(m==='>') return '&gt;'; return m;}); }

function attachDragEvents() {
    document.querySelectorAll('.mod-card[draggable="true"]').forEach(el => {
        el.addEventListener('dragstart', manejarDragStart);
        el.addEventListener('dragend', manejarDragEnd);
    });
}

// --- ACCIONES ASIGNAR/QUITAR ---
function asignar(id) {
    if (modulosAsignados.includes(id)) return;
    const mod = modulosEstado.find(m => m.id == id);
    if (mod && mod.asignado_a_otro) { mostrarToast('Módulo ya asignado a otro profesor', 'error'); return; }
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
        asignado_a_otro: (m.profesor_id !== null && m.profesor_id !== profesorActual && !modulosAsignados.includes(m.id))
    }));
    renderListasPorProfesor();
}

function filtrarModulos() { if (profesorActual === 0) renderListasGlobal(); else renderListasPorProfesor(); }

// --- GUARDAR ---
async function guardarAsignacion() {
    if (profesorActual === 0) { mostrarToast('Selecciona un profesor para guardar.', 'warn'); return; }
    const btn = document.getElementById('btnGuardar');
    btn.disabled = true;
    const asignaciones = modulosAsignados.map(id => ({ modulo_id: id }));
    try {
        const res = await fetch(`${BASE_URL}/controladores/Controlador_asignarMod.php`, {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ profesor_id: profesorActual, asignaciones })
        });
        const data = await res.json();
        mostrarToast(data.mensaje, data.supera_20 ? 'warn' : (data.ok ? 'ok' : 'error'));
        if (data.ok) {
            await cargarProfesor(); // Recargar estado completo
            actualizarResumenHoras();
        }
    } catch(e) { mostrarToast('Error de red', 'error'); }
    finally { btn.disabled = false; }
}

// --- ACTUALIZAR RESUMEN INFERIOR (COLORES EN TIEMPO REAL) ---
async function actualizarResumenHoras() {
    try {
        const res = await fetch(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=0`);
        const data = await res.json();
        if (!data.ok) return;
        const horasPorProf = {};
        data.modulos.forEach(m => { if (m.profesor_id) horasPorProf[m.profesor_id] = (horasPorProf[m.profesor_id] || 0) + parseInt(m.horas || 0); });
        document.querySelectorAll('.prof-item[data-orden]').forEach(el => {
            const orden = parseInt(el.dataset.orden);
            const horas = horasPorProf[orden] || 0;
            const pct = Math.min(100, Math.round(horas / 20 * 100));
            let color = '#2cb67d';
            if (horas > 20) color = '#e05252';
            else if (horas >= 17) color = '#f7841a';
            const fill = el.querySelector('.mini-bar-fill');
            const label = el.querySelector('.prof-horas-label');
            if (fill) { fill.style.width = pct + '%'; fill.style.background = color; }
            if (label) label.textContent = `${horas}h / 20h`;
            // Actualizar también el total en el desplegable
            const option = document.querySelector(`#selectProfesor option[value="${orden}"]`);
            if (option) {
                option.dataset.horas = horas;
                option.textContent = option.textContent.replace(/·\s*\d+h/, `· ${horas}h`);
            }
        });
        const sel = document.getElementById('selectProfesor');
        if (sel.value != '0') {
            const selectedOption = sel.options[sel.selectedIndex];
            if (selectedOption) document.getElementById('horasActuales').innerHTML = selectedOption.dataset.horas || '0';
        }
    } catch(_) {}
}

// --- FILTROS POR CATEGORÍA (PT/PS) EN EL SELECTOR ---
function toggleFiltroCategoria(tipo) {
    const btns = document.querySelectorAll('.filter-btn');
    btns.forEach(btn => btn.classList.remove('active'));
    if (filtroCategoriaActivo === tipo) {
        filtroCategoriaActivo = null;
    } else {
        filtroCategoriaActivo = tipo;
        document.querySelector(`.filter-btn[data-filtro="${tipo}"]`).classList.add('active');
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
    const options = select.querySelectorAll('option');
    options.forEach(opt => {
        if (opt.value === '0') return;
        const cat = (opt.dataset.categoria || '').toUpperCase();
        let mostrar = true;
        if (filtroCategoriaActivo) mostrar = cat.includes(filtroCategoriaActivo);
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

// --- TOAST ---
let toastTimer;
function mostrarToast(msg, tipo = 'ok') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'show ' + (tipo === 'warn' ? 'alert-warn' : tipo === 'error' ? 'alert-error' : 'alert-success');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 3500);
}
</script>
</body>
</html>