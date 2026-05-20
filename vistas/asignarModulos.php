<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asignar módulos — FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/asignarModulos.css">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/darkmode.css">
<script src="/asignaciones/vistas/estilos/darkmode.js"></script>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') { header('Location: /asignaciones/'); exit(); }
?>

<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <div class="navbar-nav">
    <div class="cambios-badge" id="cambiosBadge"><span class="cambios-dot"></span> Cambios sin guardar</div>
    <a href="/asignaciones/?vista=admin"  class="btn btn-ghost" id="linkAdmin">← Panel admin</a>
    <button class="btn btn-ghost btn-darkmode" onclick="toggleDarkMode()" title="Cambiar a modo oscuro">🌙 Modo oscuro</button>
    <a href="/asignaciones/?vista=logout" class="btn btn-ghost" id="linkLogout">Cerrar sesión</a>
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
      <div class="horas-field minimo">  <label>Mínimo 🔴</label> <input type="number" id="horasMin" min="0" max="40" value="18" step="1"></div>
      <div class="horas-field objetivo"><label>Objetivo ✅</label><input type="number" id="horasObjetivo" min="0" max="40" value="20" step="1"></div>
      <div class="horas-field maximo"> <label>Máximo 🟣</label><input type="number" id="horasMax" min="0" max="40" value="22" step="1"></div>
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
    <div class="cambios-badge" id="cambiosBadge2"><span class="cambios-dot"></span> Cambios sin guardar</div>
    <button class="btn btn-primary" onclick="pedirConfirmacion()">💾 Guardar cambios</button>
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
<!-- Modal confirmación guardar -->
<div id="modalConfirmar" class="modal-overlay">
  <div class="modal">
    <h3>💾 Confirmar guardado</h3>
    <p id="modalConfirmarTexto">¿Guardar los cambios del profesor seleccionado?</p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="cerrarModalConfirmar()">Cancelar</button>
      <button class="btn btn-success" onclick="confirmarGuardar()">Sí, guardar</button>
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
let hayCambios = false;

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
  document.getElementById('selectProfesor').addEventListener('change', () => {
    if (hayCambios && !confirm('Tienes cambios sin guardar. ¿Cambiar de profesor igualmente?')) {
      document.getElementById('selectProfesor').value = profesorActual;
      return;
    }
    cargarProfesor();
  });
  // Interceptar navegación con cambios sin guardar
  ['linkAdmin','linkLogout'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', e => {
      if (hayCambios && !confirm('Tienes cambios sin guardar. ¿Salir igualmente?')) e.preventDefault();
    });
  });
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
  hayCambios = true;
  document.getElementById('cambiosBadge')?.classList.add('visible');
  document.getElementById('cambiosBadge2')?.classList.add('visible');
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

// ── Confirmación guardar ──────────────────────────────────────────────
function pedirConfirmacion() {
  if (!profesorActual) { mostrarToast('Selecciona un profesor primero','warn'); return; }
  const nom = document.getElementById('nomProfesor')?.textContent || 'este profesor';
  const h   = document.getElementById('horasActuales')?.textContent || '0';
  document.getElementById('modalConfirmarTexto').textContent = `¿Guardar los módulos de ${nom}? (${h}h asignadas)`;
  document.getElementById('modalConfirmar').classList.add('open');
}
function cerrarModalConfirmar() { document.getElementById('modalConfirmar').classList.remove('open'); }
function confirmarGuardar() { cerrarModalConfirmar(); guardarAsignacion(); }

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
    if (data.ok) {
      hayCambios = false;
      document.getElementById('cambiosBadge')?.classList.remove('visible');
      document.getElementById('cambiosBadge2')?.classList.remove('visible');
      await cargarProfesor(); actualizarResumen();
    }
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