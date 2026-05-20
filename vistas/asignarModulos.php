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
    --accent: #e35f1f; --accent2: #f7841a;
    --dark: #1a1a2e; --light: #f4f1eb;
    --white: #fff; --muted: #888; --border: #e2dfd8;
    --ok: #0d5c35; --range: #1ea360; --warn: #e8720a; --bad: #d42b2b;
    --purple: #d42b2b; --radius: 12px;
  }
  body { font-family: 'DM Sans', sans-serif; background: var(--light); color: var(--dark); min-height: 100vh; }

  .navbar { background: var(--dark); padding: 0 28px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
  .navbar-brand { font-family: 'DM Serif Display', serif; color: #fff; font-size: 1.1rem; }
  .navbar-brand span { color: var(--accent2); }
  .navbar-nav { display: flex; gap: 8px; align-items: center; }

  .btn { padding: 7px 16px; border-radius: 8px; border: none; cursor: pointer; font-family: inherit; font-size: .85rem; font-weight: 500; transition: all .2s; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
  .btn-primary { background: var(--accent); color: #fff; }
  .btn-primary:hover { background: #c9531a; }
  .btn-primary:disabled { background: #ccc; cursor: not-allowed; }
  .btn-ghost { background: rgba(255,255,255,.09); color: #fff; }
  .btn-ghost:hover { background: rgba(255,255,255,.16); }
  .btn-success { background: var(--range); color: #fff; }
  .btn-success:hover { filter: brightness(1.1); }
  .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--dark); }
  .btn-outline:hover { background: var(--light); }

  .page { max-width: 1600px; margin: 0 auto; padding: 28px 20px; }
  .page-title { font-family: 'DM Serif Display', serif; font-size: 1.8rem; margin-bottom: 20px; }

  .alert { padding: 12px 16px; border-radius: var(--radius); font-size: .875rem; margin-bottom: 16px; border-left: 4px solid; }
  .alert-success { background: #edfaf4; color: #1f7a52; border-color: var(--range); }
  .alert-error   { background: #fdf0f0; color: #a03030; border-color: var(--bad); }
  .alert-warn    { background: #fff8e8; color: #8a5c00; border-color: var(--warn); }

  /* Selector y filtros */
  .selector-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px 24px; margin-bottom: 24px; display: flex; align-items: center; flex-wrap: wrap; gap: 20px; }
  .filter-group { display: flex; gap: 12px; align-items: center; background: var(--light); padding: 6px 16px; border-radius: 40px; }
  .filter-label { font-size: .75rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
  .filter-btn { padding: 4px 12px; border-radius: 24px; border: 1px solid var(--border); background: white; cursor: pointer; font-size: .8rem; transition: all .2s; }
  .filter-btn.active { background: var(--dark); color: white; border-color: var(--dark); }
  .selector-label { font-weight: 600; font-size: .85rem; text-transform: uppercase; color: var(--muted); }
  .select-wrap { position: relative; flex: 1; min-width: 260px; max-width: 440px; }
  .select-wrap::after { content: '▾'; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
  select { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: .9rem; background: var(--white); outline: none; appearance: none; }
  select:focus { border-color: var(--accent); }

  /* Config horas */
  .horas-config-panel { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px 24px; margin-bottom: 24px; display: flex; align-items: center; flex-wrap: wrap; gap: 20px; }
  .horas-config-title { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: var(--muted); letter-spacing: .05em; }
  .horas-fields { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
  .horas-field { display: flex; flex-direction: column; gap: 4px; }
  .horas-field label { font-size: .7rem; font-weight: 600; color: var(--muted); text-transform: uppercase; }
  .horas-field input { width: 80px; padding: 8px 10px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: .9rem; font-weight: 600; text-align: center; }
  .horas-field.minimo input   { border-color: var(--bad); }
  .horas-field.objetivo input { border-color: var(--ok); }
  .horas-field.maximo input   { border-color: var(--bad); }
  .btn-config { padding: 8px 20px; background: var(--dark); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background .2s; }
  .btn-config:hover { background: #2a2a4a; }
  .btn-config.saved { background: var(--range); }

  .horas-badge-large { background: var(--dark); border-radius: 40px; padding: 4px 20px 4px 24px; display: flex; align-items: baseline; gap: 12px; font-weight: 500; color: white; }
  .horas-badge-large span:first-child { font-size: .7rem; opacity: .8; text-transform: uppercase; letter-spacing: .05em; }
  .horas-badge-large strong { font-size: 1.5rem; font-weight: 700; margin: 0 4px; transition: color .3s; }

  /* Columnas */
  .asign-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
  @media (max-width: 900px) { .asign-grid { grid-template-columns: 1fr; } }
  .col-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); display: flex; flex-direction: column; height: calc(100vh - 380px); min-height: 550px; }
  .col-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; font-weight: 600; }
  .search-wrap { padding: 12px 16px; border-bottom: 1px solid var(--border); }
  .search-wrap input { width: 100%; padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 8px; outline: none; }
  .modulos-list { flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px; }

  .mod-card { border-radius: 8px; padding: 12px; display: flex; align-items: center; gap: 12px; border-left: 4px solid; background: #fff8f4; border-color: var(--accent2); font-size: .875rem; transition: all .1s; cursor: grab; }
  .mod-card.dragging { opacity: 0.5; cursor: grabbing; }
  .mod-card.tipo-pspt { background: #f3f0ff; border-color: var(--bad); }
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
  .btn-asignar, .btn-quitar { border: none; border-radius: 6px; padding: 6px 12px; font-weight: 600; cursor: pointer; white-space: nowrap; }
  .btn-asignar { background: var(--accent); color: white; }
  .btn-quitar  { background: #fee2e2; color: var(--bad); }

  /* Resumen con colores */
  .resumen-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); margin-top: 28px; overflow: hidden; }
  .resumen-header { padding: 18px 24px; border-bottom: 1px solid var(--border); font-weight: 600; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
  .leyenda-resumen { display: flex; gap: 14px; flex-wrap: wrap; margin-left: auto; }
  .leyenda-item { display: flex; align-items: center; gap: 5px; font-size: .72rem; font-weight: 500; }
  .leyenda-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
  .prof-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
  .prof-item { padding: 16px 20px; border-right: 1px solid var(--border); border-bottom: 1px solid var(--border); }
  .prof-nombre { font-weight: 600; margin-bottom: 2px; }
  .prof-cat { font-size: .7rem; color: #888; margin-bottom: 8px; }
  .mini-bar-bg { height: 7px; background: #eee; border-radius: 4px; overflow: hidden; margin: 6px 0; }
  .mini-bar-fill { height: 100%; border-radius: 4px; transition: width 0.3s, background 0.3s; }
  .prof-horas-label { font-size: .75rem; color: #666; font-weight: 500; line-height: 1.5; }

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
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') { header('Location: /asignaciones/'); exit(); }
?>

<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <div class="navbar-nav">
    <a href="/asignaciones/?vista=admin"  class="btn btn-ghost">← Panel admin</a>
    <a href="/asignaciones/?vista=logout" class="btn btn-ghost">Cerrar sesión</a>
  </div>
</nav>

<div class="page">
  <div class="page-title">Asignación de módulos</div>
  <div id="alertContainer"></div>

  <!-- Selector de profesor -->
  <div class="selector-card">
    <div class="filter-group">
      <span class="filter-label">Filtrar por:</span>
      <button class="filter-btn" data-filtro="PT" onclick="toggleFiltroCategoria('PT')">Solo PT</button>
      <button class="filter-btn" data-filtro="PS" onclick="toggleFiltroCategoria('PS')">Solo PS</button>
      <button class="filter-btn" onclick="limpiarFiltroCategoria()" style="border-color:var(--bad);color:var(--bad)">✕ Quitar</button>
    </div>
    <span class="selector-label">Profesor:</span>
    <div class="select-wrap">
      <select id="selectProfesor">
        <option value="0">🔍 — Mostrar todos los módulos —</option>
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
      <span>HORAS</span>
      <strong id="horasActuales">0</strong>
      <span>h</span>
    </div>
    <button id="btnGuardar" class="btn btn-success" onclick="guardarAsignacion()">💾 Guardar</button>
    <button id="btnMostrarTodos" class="btn btn-outline" onclick="toggleMostrarTodos()">📋 Todos los módulos</button>
  </div>

  <!-- Config horas globales -->
  <div class="horas-config-panel">
    <span class="horas-config-title">🎯 Configuración global de horas objetivo</span>
    <div class="horas-fields">
      <div class="horas-field minimo">  <label>Mínimo  🟠</label> <input type="number" id="horasMin" min="0" max="40" value="18" step="1"></div>
      <div class="horas-field objetivo"><label>Objetivo ✅</label><input type="number" id="horasObjetivo" min="0" max="40" value="20" step="1"></div>
      <div class="horas-field maximo"> <label>Máximo  🔴</label><input type="number" id="horasMax" min="0" max="40" value="22" step="1"></div>
    </div>
    <button class="btn-config" id="btnGuardarHoras" onclick="guardarConfigGlobal()">Aplicar a todos</button>
  </div>

  <!-- Columnas asignación -->
  <div class="asign-grid">
    <div class="col-card">
      <div class="col-header"><span>📚 Módulos disponibles</span><span id="countDisp">0 módulos</span></div>
      <div class="search-wrap"><input type="text" id="buscador" placeholder="Buscar módulo..." oninput="filtrarModulos()"></div>
      <div class="modulos-list" id="listaDisponibles"></div>
    </div>
    <div class="col-card">
      <div class="col-header"><span>📋 Asignados a <span id="nomProfesor">—</span></span><span id="horasAsignadasLabel">0h</span></div>
      <div class="modulos-list" id="listaAsignados"></div>
    </div>
  </div>

  <div class="save-bar">
    <button class="btn btn-primary" onclick="guardarAsignacion()">💾 Guardar cambios</button>
  </div>

  <!-- Resumen con leyenda de colores -->
  <div class="resumen-card">
    <div class="resumen-header">
      👨‍🏫 Carga horaria por profesor
      <div class="leyenda-resumen">
        <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--ok)"></span><span style="color:var(--ok)">Óptimo</span></div>
        <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--range)"></span><span style="color:var(--range)">En rango</span></div>
        <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--warn)"></span><span style="color:var(--warn)">Límite</span></div>
        <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--bad)"></span><span style="color:var(--bad)">Fuera de rango</span></div>
      </div>
    </div>
    <div class="prof-grid" id="resumenGrid">
      <?php if (isset($profesoresConHoras) && is_array($profesoresConHoras)): ?>
        <?php foreach ($profesoresConHoras as $p): ?>
          <div class="prof-item" data-orden="<?= $p['orden'] ?>">
            <div class="prof-nombre"><?= htmlspecialchars($p['nombre']) ?></div>
            <div class="prof-cat"><?= htmlspecialchars($p['categoria'] ?? '') ?></div>
            <div class="mini-bar-bg"><div class="mini-bar-fill"></div></div>
            <div class="prof-horas-label" id="profLabel_<?= $p['orden'] ?>">--h</div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="toast"></div>

<script>
const TODOS_MODULOS  = <?= isset($modulos) ? json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) : '[]' ?>;
const BASE_URL       = '/asignaciones';
const PROFESORES_MAP = <?= isset($profesoresConHoras) ? json_encode(array_column($profesoresConHoras, 'nombre', 'orden'), JSON_UNESCAPED_UNICODE) : '{}' ?>;
const STORAGE_KEY    = 'horasConfigGlobal';

let modulosEstado = [], profesorActual = 0, categoriaProfesor = '';
let modulosAsignados = [], filtroCategoriaActivo = null, mostrarTodosModulos = false;
let horasCfg = { min:18, objetivo:20, max:22 };

// ── Sistema de colores ────────────────────────────────────────────────
// Verde oscuro = objetivo exacto (óptimo)
// Verde claro  = dentro del rango min–max
// Naranja      = en el borde (min-1 o max+1)
// Rojo         = fuera de rango (muy bajo o muy alto)
function calcularColor(h) {
  if (h === horasCfg.objetivo)                  return '#1a6b45'; // verde oscuro
  if (h >= horasCfg.min && h <= horasCfg.max)   return '#2cb67d'; // verde claro
  if (h === horasCfg.min-1 || h === horasCfg.max+1) return '#f7841a'; // naranja
  return '#e05252'; // rojo
}
function calcularAviso(h) {
  if (h === horasCfg.objetivo)              return '✅ Objetivo exacto';
  if (h > horasCfg.objetivo && h <= horasCfg.max) return `✅ En rango (+${h-horasCfg.objetivo}h)`;
  if (h >= horasCfg.min && h < horasCfg.objetivo) return `⬆ Faltan ${horasCfg.objetivo-h}h`;
  if (h < horasCfg.min)                    return `🔴 Bajo el mínimo (${horasCfg.min}h)`;
  return `⚠️ Supera máximo (+${h-horasCfg.max}h)`;
}

// ── Inicialización ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  cargarConfigStorage();
  modulosEstado = TODOS_MODULOS.map(m => ({...m, asignado_a_profe:false, asignado_a_otro:false}));
  document.getElementById('selectProfesor').addEventListener('change', cargarProfesor);
  cargarProfesor();
  setupDragAndDrop();
});

function cargarConfigStorage() {
  try { const s = localStorage.getItem(STORAGE_KEY); if (s) horasCfg = {...horasCfg,...JSON.parse(s)}; } catch(e){}
  document.getElementById('horasMin').value     = horasCfg.min;
  document.getElementById('horasObjetivo').value = horasCfg.objetivo;
  document.getElementById('horasMax').value     = horasCfg.max;
}

function guardarConfigGlobal() {
  const min = parseInt(document.getElementById('horasMin').value)||18;
  const obj = parseInt(document.getElementById('horasObjetivo').value)||20;
  const max = parseInt(document.getElementById('horasMax').value)||22;
  if (min > obj || obj > max) { mostrarToast('Debe cumplirse: mínimo ≤ objetivo ≤ máximo', 'error'); return; }
  horasCfg = {min, objetivo:obj, max};
  localStorage.setItem(STORAGE_KEY, JSON.stringify(horasCfg));
  actualizarResumen();
  const btn = document.getElementById('btnGuardarHoras');
  btn.textContent = '✅ Aplicado'; btn.classList.add('saved');
  setTimeout(() => { btn.textContent = 'Aplicar a todos'; btn.classList.remove('saved'); }, 1500);
  mostrarToast(`Global: min ${min} / obj ${obj} / max ${max}`, 'ok');
}

// ── Drag & Drop ───────────────────────────────────────────────────────
function setupDragAndDrop() {
  ['listaDisponibles','listaAsignados'].forEach(id => {
    const el = document.getElementById(id);
    if (el) { el.addEventListener('dragover', e => e.preventDefault()); el.addEventListener('drop', manejarDrop); }
  });
}
function manejarDragStart(e) { const c=e.target.closest('.mod-card'); if(c?.dataset.id){e.dataTransfer.setData('text/plain',c.dataset.id);c.classList.add('dragging');} }
function manejarDragEnd(e)   { e.target.closest('.mod-card')?.classList.remove('dragging'); }
function manejarDrop(e) {
  e.preventDefault();
  const id=parseInt(e.dataTransfer.getData('text/plain'));
  if (e.currentTarget.id==='listaAsignados') asignar(id); else quitar(id);
}
function attachDragEvents() {
  document.querySelectorAll('.mod-card[draggable="true"]').forEach(el => {
    el.removeEventListener('dragstart',manejarDragStart); el.removeEventListener('dragend',manejarDragEnd);
    el.addEventListener('dragstart',manejarDragStart); el.addEventListener('dragend',manejarDragEnd);
  });
}

// ── Carga de módulos ──────────────────────────────────────────────────
async function cargarProfesor() {
  const sel = document.getElementById('selectProfesor');
  profesorActual = parseInt(sel.value)||0;
  const nomSpan = document.getElementById('nomProfesor');

  if (profesorActual === 0) {
    if (nomSpan) nomSpan.textContent = '— Vista global —';
    try {
      mostrarLoading(true);
      const data = await fetchJSON(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=0`);
      if (data.ok) { modulosEstado = data.modulos; modulosAsignados = []; renderGlobal(); actualizarResumen(); }
    } finally { mostrarLoading(false); }
    return;
  }

  const opt = sel.options[sel.selectedIndex];
  if (nomSpan) nomSpan.textContent = opt.dataset.nombre||'';
  categoriaProfesor = (opt.dataset.categoria||'').toUpperCase();

  try {
    mostrarLoading(true);
    const data = await fetchJSON(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=${profesorActual}`);
    if (!data.ok) throw new Error(data.mensaje||'Error');
    modulosEstado = data.modulos;
    modulosAsignados = modulosEstado.filter(m=>m.asignado_a_profe).map(m=>parseInt(m.id));
    renderProfesor(); actualizarResumen();
    actualizarBadgeHoras();
  } catch(e) { mostrarToast(e.message,'error'); }
  finally { mostrarLoading(false); }
}

async function fetchJSON(url) {
  const r = await fetch(url); if (!r.ok) throw new Error('HTTP '+r.status);
  return r.json();
}

function actualizarBadgeHoras() {
  const horas = modulosAsignados.reduce((s,id) => { const m=modulosEstado.find(x=>x.id==id); return s+(parseInt(m?.horas)||0); }, 0);
  const el = document.getElementById('horasActuales');
  if (el) { el.textContent = horas; el.style.color = calcularColor(horas); }
}

// ── Renderizado ───────────────────────────────────────────────────────
function renderGlobal() {
  const busq = document.getElementById('buscador')?.value.toLowerCase()||'';
  let lista = modulosEstado;
  if (busq) lista = lista.filter(m=>(m.nombre_modulo||'').toLowerCase().includes(busq)||(m.grado||'').toLowerCase().includes(busq));
  const ld = document.getElementById('listaDisponibles');
  const la = document.getElementById('listaAsignados');
  document.getElementById('countDisp').textContent = lista.length+' módulos';
  ld.innerHTML = lista.length ? lista.map(m=>tarjetaGlobal(m)).join('') : '<div class="empty-list">No hay módulos.</div>';
  la.innerHTML = '<div class="empty-list">Selecciona un profesor.</div>';
  attachDragEvents();
}

function renderProfesor() {
  const busq = document.getElementById('buscador')?.value.toLowerCase()||'';
  let disponibles = modulosEstado.filter(m=>!m.asignado_a_profe);
  if (!mostrarTodosModulos && profesorActual && categoriaProfesor) {
    disponibles = disponibles.filter(m => {
      const c=(m.categoria||'').toUpperCase();
      if (categoriaProfesor==='PT') return c==='SAI';
      if (categoriaProfesor==='PS') return c==='INF';
      return true;
    });
  }
  if (busq) disponibles = disponibles.filter(m=>(m.nombre_modulo||'').toLowerCase().includes(busq)||(m.grado||'').toLowerCase().includes(busq));
  const asignados = modulosEstado.filter(m=>m.asignado_a_profe);
  const horasAcum = asignados.reduce((s,m)=>s+(parseInt(m.horas)||0),0);

  document.getElementById('countDisp').textContent         = disponibles.length+' módulos';
  document.getElementById('horasAsignadasLabel').textContent = horasAcum+'h';
  document.getElementById('listaDisponibles').innerHTML    = disponibles.length ? disponibles.map(m=>tarjetaProfesor(m,'asignar')).join('') : '<div class="empty-list">No hay módulos disponibles.</div>';
  document.getElementById('listaAsignados').innerHTML      = asignados.length   ? asignados.map(m=>tarjetaProfesor(m,'quitar')).join('')  : '<div class="empty-list">Sin módulos asignados.</div>';
  const horasEl = document.getElementById('horasActuales');
  if (horasEl) { horasEl.textContent = horasAcum; horasEl.style.color = calcularColor(horasAcum); }
  attachDragEvents();
}

function tarjetaGlobal(m) {
  const ocupado = m.profesor_id && m.profesor_id!=0 ? (PROFESORES_MAP[m.profesor_id]||null) : null;
  const esp = getEsp(m.categoria);
  return `<div class="mod-card${m.es_pspt?' tipo-pspt':''}" data-id="${m.id}" draggable="false" style="cursor:default;opacity:.8">
    <div class="mod-info"><div class="mod-titulo">${esc(m.nombre_modulo)}</div>
    <div class="mod-detalle"><span>${esc(m.grado)}</span><span class="mod-horas">${m.horas||0}h</span>${esp}</div></div>
    ${ocupado ? `<span class="badge-ocupante">👤 ${esc(ocupado)}</span>` : '<span class="badge-cat">Libre</span>'}
  </div>`;
}

function tarjetaProfesor(m, accion) {
  const otro = m.asignado_a_otro;
  const esp  = getEsp(m.categoria);
  const drag = !otro && accion==='asignar';
  let btn = '';
  if (!otro) btn = accion==='asignar'
    ? `<button class="btn-asignar" onclick="asignar(${m.id})">+ Asignar</button>`
    : `<button class="btn-quitar"  onclick="quitar(${m.id})">✕ Quitar</button>`;
  return `<div class="mod-card${m.es_pspt?' tipo-pspt':''}${otro?' ocupado':''}" data-id="${m.id}" draggable="${drag}" style="${!drag?'cursor:default;':''}">
    <div class="mod-info"><div class="mod-titulo">${esc(m.nombre_modulo)}</div>
    <div class="mod-detalle"><span>${esc(m.grado)}</span><span class="mod-horas">${m.horas||0}h</span>${esp}</div></div>
    ${otro ? `<span class="badge-ocupante">👤 ${esc(PROFESORES_MAP[m.profesor_id]||'Otro')}</span>` : ''}${btn}
  </div>`;
}

function getEsp(cat) {
  const c=(cat||'').toUpperCase();
  if (c==='SAI') return '<span class="badge-especialidad badge-sai">SAI</span>';
  if (c==='INF') return '<span class="badge-especialidad badge-inf">INF</span>';
  return '';
}
function esc(s) { return String(s||'').replace(/[&<>]/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); }

// ── Asignar / Quitar ──────────────────────────────────────────────────
function asignar(id) {
  if (modulosAsignados.includes(id)) return;
  const m = modulosEstado.find(x=>x.id==id);
  if (m?.asignado_a_otro) { mostrarToast('Módulo asignado a otro profesor','error'); return; }
  modulosAsignados.push(id); actualizarEstadoLocal();
}
function quitar(id) { modulosAsignados=modulosAsignados.filter(x=>x!==id); actualizarEstadoLocal(); }
function actualizarEstadoLocal() {
  modulosEstado = modulosEstado.map(m => ({...m,
    asignado_a_profe: modulosAsignados.includes(m.id),
    asignado_a_otro:  m.profesor_id!==null && m.profesor_id!==profesorActual && m.profesor_id!==0 && !modulosAsignados.includes(m.id)
  }));
  renderProfesor(); actualizarResumen();
}

function filtrarModulos() { profesorActual===0 ? renderGlobal() : renderProfesor(); }
function toggleMostrarTodos() {
  mostrarTodosModulos=!mostrarTodosModulos;
  const btn=document.getElementById('btnMostrarTodos');
  btn.style.background = mostrarTodosModulos?'var(--accent2)':'';
  btn.style.color      = mostrarTodosModulos?'white':'';
  if (profesorActual) renderProfesor();
}

// ── Guardar ───────────────────────────────────────────────────────────
async function guardarAsignacion() {
  if (!profesorActual) { mostrarToast('Selecciona un profesor primero','warn'); return; }
  const btn=document.getElementById('btnGuardar');
  if (btn) btn.disabled=true;
  try {
    const res = await fetch(`${BASE_URL}/controladores/Controlador_asignarMod.php`,{
      method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({profesor_id:profesorActual, asignaciones:modulosAsignados.map(id=>({modulo_id:id}))})
    });
    const data = await res.json();
    mostrarToast(data.mensaje, data.supera_20?'warn':(data.ok?'ok':'error'));
    if (data.ok) { await cargarProfesor(); actualizarResumen(); }
  } catch(e) { mostrarToast('Error de red','error'); }
  finally { if (btn) btn.disabled=false; }
}

// ── Resumen coloreado ─────────────────────────────────────────────────
function actualizarResumen() {
  const horasPorProf = {};
  modulosEstado.forEach(m => { if (m.profesor_id) horasPorProf[m.profesor_id]=(horasPorProf[m.profesor_id]||0)+(parseInt(m.horas)||0); });

  document.querySelectorAll('.prof-item[data-orden]').forEach(el => {
    const id = parseInt(el.dataset.orden);
    const h  = horasPorProf[id] || 0;
    const color = calcularColor(h);
    const aviso = calcularAviso(h);
    const pct   = horasCfg.objetivo>0 ? Math.min(100, Math.round(h/horasCfg.objetivo*100)) : 0;

    const fill  = el.querySelector('.mini-bar-fill');
    const label = document.getElementById('profLabel_'+id);
    if (fill)  { fill.style.width=pct+'%'; fill.style.background=color; }
    if (label) { label.innerHTML=`<strong style="color:${color}">${h}h</strong> / ${horasCfg.objetivo}h <span style="color:${color};font-size:.7rem;">${aviso}</span>`; }
  });
}

// ── Filtros categoría ─────────────────────────────────────────────────
function toggleFiltroCategoria(tipo) {
  filtroCategoriaActivo = filtroCategoriaActivo===tipo ? null : tipo;
  document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
  if (filtroCategoriaActivo) document.querySelector(`.filter-btn[data-filtro="${tipo}"]`)?.classList.add('active');
  aplicarFiltroSelect();
}
function limpiarFiltroCategoria() { filtroCategoriaActivo=null; document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active')); aplicarFiltroSelect(); }
function aplicarFiltroSelect() {
  const sel=document.getElementById('selectProfesor');
  [...sel.options].forEach(o => {
    if (o.value==='0') return;
    o.style.display = !filtroCategoriaActivo || (o.dataset.categoria||'').toUpperCase().includes(filtroCategoriaActivo) ? '' : 'none';
  });
  if (sel.options[sel.selectedIndex]?.style.display==='none') {
    const vis=[...sel.options].find(o=>o.value&&o.style.display!=='none');
    sel.value = vis ? vis.value : '0';
  }
  cargarProfesor();
}

function mostrarLoading(show) { document.querySelector('.asign-grid')?.classList.toggle('loading',show); }

let toastTimer;
function mostrarToast(msg,tipo='ok') {
  const t=document.getElementById('toast'); if(!t) return;
  t.textContent=msg; t.className='show';
  t.style.background = tipo==='warn'?'#f7841a':tipo==='error'?'#e05252':'#1a1a2e';
  clearTimeout(toastTimer); toastTimer=setTimeout(()=>t.classList.remove('show'),3500);
}
</script>
</body>
</html>