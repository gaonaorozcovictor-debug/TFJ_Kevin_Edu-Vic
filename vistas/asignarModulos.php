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

  /* Layout */
  .page { max-width: 1400px; margin: 0 auto; padding: 28px 20px; }

  .page-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.8rem;
    margin-bottom: 20px;
  }

  /* Alertas */
  .alert {
    padding: 12px 16px; border-radius: var(--radius);
    font-size: .875rem; margin-bottom: 16px; border-left: 4px solid;
  }
  .alert-success { background: #edfaf4; color: #1f7a52; border-color: var(--success); }
  .alert-error   { background: #fdf0f0; color: #a03030; border-color: var(--danger); }
  .alert-warn    { background: #fff8e8; color: #8a5c00; border-color: var(--accent2); }

  /* Selector de profesor */
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

  .selector-label {
    font-weight: 600;
    font-size: .85rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--muted);
    white-space: nowrap;
  }

  .select-wrap { position: relative; flex: 1; min-width: 260px; max-width: 440px; }
  .select-wrap::after {
    content: '▾'; position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%); color: var(--muted); pointer-events: none;
  }
  select {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-family: inherit; font-size: .9rem; color: var(--dark);
    background: var(--white); outline: none; appearance: none;
    transition: border-color .2s;
  }
  select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(227,95,31,.1); }

  .horas-badge {
    background: #fff3e8; border: 1px solid #fde0b8;
    border-radius: 8px; padding: 8px 16px; font-size: .875rem;
    color: #c9531a; white-space: nowrap;
  }
  .horas-badge strong { font-size: 1.1rem; }

  /* Columnas de asignación */
  .asign-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media (max-width: 900px) { .asign-grid { grid-template-columns: 1fr; } }

  .col-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    display: flex;
    flex-direction: column;
    min-height: 500px;
  }

  .col-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
  }

  .col-title { font-weight: 600; font-size: .95rem; }

  /* Search */
  .search-wrap { padding: 12px 16px; border-bottom: 1px solid var(--border); }
  .search-wrap input {
    width: 100%; padding: 8px 12px;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-family: inherit; font-size: .875rem; outline: none;
    transition: border-color .2s;
  }
  .search-wrap input:focus { border-color: var(--accent); }

  /* Module list */
  .modulos-list {
    flex: 1; overflow-y: auto; padding: 12px;
    display: flex; flex-direction: column; gap: 6px;
  }

  /* Individual module card */
  .mod-card {
    border-radius: 8px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 3px solid;
    font-size: .875rem;
    transition: transform .15s, box-shadow .15s;
    position: relative;
  }

  .mod-card.tipo-normal  { background: #fff8f4; border-color: var(--accent2); }
  .mod-card.tipo-pspt    { background: #f3f0ff; border-color: var(--purple); }
  .mod-card.ocupado      { background: #fafafa; border-color: #ddd; opacity: .75; }

  .mod-ciclo {
    display: inline-block; padding: 2px 7px;
    border-radius: 12px; font-size: .7rem; font-weight: 600;
    background: rgba(0,0,0,.07); color: #555; white-space: nowrap;
    flex-shrink: 0;
  }

  .mod-nombre { flex: 1; font-weight: 500; }
  .mod-horas  { font-size: .8rem; color: var(--muted); white-space: nowrap; }

  .mod-tag {
    font-size: .65rem; font-weight: 700; padding: 2px 6px;
    border-radius: 10px; flex-shrink: 0;
    background: #ede9fb; color: var(--purple);
  }

  .mod-ocupado-badge {
    font-size: .65rem; padding: 2px 7px; border-radius: 10px;
    background: #fde8e8; color: var(--danger); font-weight: 600;
    white-space: nowrap;
  }

  .btn-asignar, .btn-quitar {
    border: none; border-radius: 6px; padding: 5px 10px;
    cursor: pointer; font-family: inherit; font-size: .78rem;
    font-weight: 600; transition: all .15s; flex-shrink: 0;
  }
  .btn-asignar { background: var(--accent); color: #fff; }
  .btn-asignar:hover { background: #c9531a; }
  .btn-asignar:disabled { background: #ddd; color: #999; cursor: not-allowed; }
  .btn-quitar { background: #fee2e2; color: var(--danger); }
  .btn-quitar:hover { background: #fca5a5; }

  .empty-list {
    text-align: center; padding: 48px 24px; color: var(--muted); font-size: .875rem;
  }

  /* Toast */
  #toast {
    position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%);
    background: var(--dark); color: #fff; padding: 12px 24px;
    border-radius: 24px; font-size: .875rem; font-weight: 500;
    box-shadow: 0 8px 24px rgba(0,0,0,.25);
    opacity: 0; transition: opacity .3s; pointer-events: none;
    z-index: 999;
  }
  #toast.show { opacity: 1; }
  #toast.warn  { background: #92400e; }
  #toast.error { background: var(--danger); }

  /* Botón guardar */
  .save-bar {
    padding: 16px 0;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 16px;
  }

  .save-info { font-size: .875rem; color: var(--muted); }

  /* Listado resumen profesores */
  .resumen-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-top: 28px;
    overflow: hidden;
  }

  .resumen-header { padding: 18px 24px; border-bottom: 1px solid var(--border); font-weight: 600; }

  .prof-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0; }

  .prof-item {
    padding: 14px 20px;
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    font-size: .875rem;
  }

  .prof-nombre { font-weight: 500; margin-bottom: 4px; }
  .prof-cat    { font-size: .75rem; color: var(--muted); margin-bottom: 8px; }

  .mini-bar-bg  { height: 5px; background: #eee; border-radius: 3px; overflow: hidden; }
  .mini-bar-fill { height: 100%; border-radius: 3px; }

  .prof-horas-label { font-size: .75rem; color: var(--muted); margin-top: 4px; }
</style>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: /asignaciones/');
    exit();
}

$primerProfesor = !empty($profesoresConHoras) ? $profesoresConHoras[0] : null;
?>

<!-- Navbar -->
<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <div class="navbar-nav">
    <a href="/asignaciones/?vista=admin"  class="btn btn-ghost">← Panel admin</a>
    <a href="/asignaciones/?vista=logout" class="btn btn-ghost">Cerrar sesión</a>
  </div>
</nav>

<div class="page">
  <div class="page-title">Asignación de módulos</div>

  <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
    <?php unset($_SESSION['mensaje']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <!-- Selector de profesor -->
  <div class="selector-card">
    <span class="selector-label">Profesor:</span>
    <div class="select-wrap">
      <select id="selectProfesor">
        <option value="">— Selecciona un profesor —</option>
        <?php foreach ($profesoresConHoras as $p): ?>
          <option value="<?= $p['orden'] ?>"
                  data-horas="<?= $p['total_horas'] ?>"
                  data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                  <?= ($primerProfesor && $primerProfesor['orden'] == $p['orden']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['nombre']) ?> (<?= htmlspecialchars($p['categoria'] ?? '') ?>) · <?= $p['total_horas'] ?>h
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="horas-badge">
      Horas: <strong id="horasActuales"><?= $primerProfesor ? $primerProfesor['total_horas'] : 0 ?></strong>h
    </div>
    <button id="btnGuardar" class="btn btn-success" onclick="guardarAsignacion()">💾 Guardar</button>
  </div>

  <!-- Columnas -->
  <div class="asign-grid">

    <!-- Disponibles -->
    <div class="col-card">
      <div class="col-header">
        <span class="col-title">📚 Módulos disponibles</span>
        <span id="countDisp" style="font-size:.8rem;color:var(--muted)"></span>
      </div>
      <div class="search-wrap">
        <input type="text" id="buscador" placeholder="Buscar módulo..." oninput="filtrarModulos()">
      </div>
      <div class="modulos-list" id="listaDisponibles"></div>
    </div>

    <!-- Asignados al profesor -->
    <div class="col-card">
      <div class="col-header">
        <span class="col-title">📋 Asignados a <span id="nomProfesor">—</span></span>
        <span id="horasAcum" style="font-size:.8rem;color:var(--accent);font-weight:600">0h</span>
      </div>
      <div class="modulos-list" id="listaAsignados">
        <div class="empty-list">Selecciona un profesor para ver sus módulos.</div>
      </div>
    </div>

  </div>

  <div class="save-bar">
    <span class="save-info" id="saveInfo"></span>
    <button class="btn btn-success" onclick="guardarAsignacion()">💾 Guardar asignación</button>
  </div>

  <!-- Resumen profesores -->
  <div class="resumen-card">
    <div class="resumen-header">👨‍🏫 Resumen de carga horaria por profesor</div>
    <div class="prof-grid">
      <?php foreach ($profesoresConHoras as $p):
        $pct = min(100, round($p['total_horas'] / 20 * 100));
        $color = $p['total_horas'] > 20 ? '#e05252' : ($p['total_horas'] >= 16 ? '#f7841a' : '#2cb67d');
      ?>
      <div class="prof-item">
        <div class="prof-nombre"><?= htmlspecialchars($p['nombre']) ?></div>
        <div class="prof-cat"><?= htmlspecialchars($p['categoria'] ?? '') ?></div>
        <div class="mini-bar-bg">
          <div class="mini-bar-fill" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
        </div>
        <div class="prof-horas-label"><?= $p['total_horas'] ?>h / 20h</div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- Toast -->
<div id="toast"></div>

<script>
// ── Datos desde PHP ───────────────────────────────────────────────────────
const TODOS_MODULOS = <?= json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) ?>;
const BASE_URL      = '/asignaciones';

// ── Estado ────────────────────────────────────────────────────────────────
let profesorActual   = null;
let modulosAsignados = []; 

// ── Init ──────────────────────────────────────────────────────────────────
document.getElementById('selectProfesor').addEventListener('change', cargarProfesor);

// Configurar eventos de Drag & Drop en los contenedores
const listaDisp = document.getElementById('listaDisponibles');
const listaAsig = document.getElementById('listaAsignados');

[listaDisp, listaAsig].forEach(zona => {
    zona.addEventListener('dragover', e => e.preventDefault()); // Permitir soltar
    zona.addEventListener('drop', manejarDrop);
});

const primerOpt = document.getElementById('selectProfesor').value;
if (primerOpt) cargarProfesor();

// ── Lógica de Drag & Drop ──────────────────────────────────────────────────
function manejarDragStart(e) {
    // Guardamos el ID del módulo que se está arrastrando
    e.dataTransfer.setData('text/plain', e.target.dataset.id);
    e.target.classList.add('dragging');
}

function manejarDragEnd(e) {
    e.target.classList.remove('dragging');
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

// ── Cargar módulos de un profesor ─────────────────────────────────────────
async function cargarProfesor() {
  const sel = document.getElementById('selectProfesor');
  profesorActual = parseInt(sel.value) || null;

  if (!profesorActual) {
    document.getElementById('listaDisponibles').innerHTML = '<div class="empty-list">Selecciona un profesor.</div>';
    document.getElementById('listaAsignados').innerHTML   = '<div class="empty-list">Selecciona un profesor.</div>';
    document.getElementById('nomProfesor').textContent    = '—';
    document.getElementById('horasAcum').textContent      = '0h';
    return;
  }

  const opt = sel.options[sel.selectedIndex];
  document.getElementById('nomProfesor').textContent = opt.dataset.nombre || '';
  document.getElementById('horasActuales').textContent = opt.dataset.horas || '0';

  try {
    const res  = await fetch(`${BASE_URL}/controladores/Controlador_obtenerModulosProfesor.php?profesor_id=${profesorActual}`);
    const data = await res.json();
    if (!data.ok) { mostrarToast(data.mensaje, 'error'); return; }

    modulosAsignados = data.modulos
      .filter(m => m.asignado_a_profe)
      .map(m => parseInt(m.id));

    renderListas(data.modulos);
  } catch (e) {
    mostrarToast('Error al cargar módulos', 'error');
  }
}

// ── Renderizar ambas listas ───────────────────────────────────────────────
function renderListas(modulos) {
  const busq = document.getElementById('buscador').value.toLowerCase();
  
  // CAMBIO: En disponibles mostramos lo que NO esté ocupado por otros.
  // Esto incluye lo que está libre Y lo que ya tiene este profesor.
  const disponibles = modulos.filter(m => !m.asignado_a_otro);
  const asignados   = modulos.filter(m => m.asignado_a_profe);

  const dispFiltrados = disponibles.filter(m =>
    !busq || m.nombre_modulo.toLowerCase().includes(busq) || (m.grado || '').toLowerCase().includes(busq)
  );

  document.getElementById('countDisp').textContent = dispFiltrados.length + ' módulos';

  document.getElementById('listaDisponibles').innerHTML =
    dispFiltrados.length
      ? dispFiltrados.map(m => tarjeta(m, 'asignar')).join('')
      : '<div class="empty-list">No hay módulos disponibles.</div>';

  document.getElementById('listaAsignados').innerHTML =
    asignados.length
      ? asignados.map(m => tarjeta(m, 'quitar')).join('')
      : '<div class="empty-list">Este profesor no tiene módulos aún.</div>';

  // --- Lógica de horas ---
  const horasAcum = asignados.reduce((s, m) => s + parseInt(m.horas || 0), 0);
  const overload  = horasAcum > 20;
  document.getElementById('horasAcum').textContent  = horasAcum + 'h' + (overload ? ' ⚠️' : '');
  document.getElementById('horasAcum').style.color  = overload ? '#e05252' : 'var(--accent)';
  document.getElementById('saveInfo').textContent   = `${asignados.length} módulos · ${horasAcum}h`;

  // Re-vincular eventos
  document.querySelectorAll('.mod-card').forEach(card => {
      card.addEventListener('dragstart', manejarDragStart);
      card.addEventListener('dragend', manejarDragEnd);
  });
}

function tarjeta(m, accion) {
  // Detectar si el módulo ya está en la lista de la derecha (asignado al profe actual)
  const yaAsignado = m.asignado_a_profe && accion === 'asignar';
  
  let tipo = m.es_pspt ? 'tipo-pspt' : (m.asignado_a_otro ? 'ocupado' : 'tipo-normal');
  
  // Si ya está asignado, forzamos un estilo visual de "deshabilitado"
  const estiloGris = yaAsignado ? 'opacity: 0.5; filter: grayscale(1); pointer-events: none;' : '';
  
  const psptTag = m.es_pspt ? '<span class="mod-tag">PS/PT</span>' : '';
  const ocupBadge = m.asignado_a_otro
    ? `<span class="mod-ocupado-badge">Ocupado</span>`
    : (yaAsignado ? `<span class="mod-tag" style="background: #6c757d;">Seleccionado</span>` : '');
  
  // No permitimos drag si es de otro O si ya está asignado en esta vista
  const isDraggable = !m.asignado_a_otro && !yaAsignado;
  const btnDisabled = (m.asignado_a_otro || yaAsignado) ? 'disabled' : '';
  
  const btn = accion === 'asignar'
    ? `<button class="btn-asignar" onclick="asignar(${m.id})" ${btnDisabled}>${yaAsignado ? 'Añadido' : 'Asignar →'}</button>`
    : `<button class="btn-quitar"  onclick="quitar(${m.id})">✕ Quitar</button>`;

  return `
    <div class="mod-card ${tipo}" 
         id="mod-${m.id}" 
         data-id="${m.id}" 
         draggable="${isDraggable}" 
         style="cursor: ${isDraggable ? 'grab' : 'not-allowed'}; ${estiloGris}">
      <span class="mod-ciclo">${esc(m.grado || '—')}</span>
      <span class="mod-nombre">${esc(m.nombre_modulo)}</span>
      <span class="mod-horas">${m.horas}h</span>
      ${psptTag}${ocupBadge}
      ${btn}
    </div>`;
}

function esc(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ── Asignar / Quitar (local) ───────────────────
function asignar(id) {
  const mod = TODOS_MODULOS.find(m => parseInt(m.id) === id);
  // Evitar asignar si ya está en la lista o si pertenece a otro profesor
  if (modulosAsignados.includes(id) || (mod && mod.profesor_id && parseInt(mod.profesor_id) !== profesorActual)) return;
  
  modulosAsignados.push(id);
  _recargarConEstado();
}

function quitar(id) {
  modulosAsignados = modulosAsignados.filter(x => x !== id);
  _recargarConEstado();
}

function _recargarConEstado() {
  const modulos = TODOS_MODULOS.map(m => ({
    ...m,
    asignado_a_profe: modulosAsignados.includes(parseInt(m.id)),
    asignado_a_otro : m.profesor_id !== null && parseInt(m.profesor_id) !== profesorActual
                      && !modulosAsignados.includes(parseInt(m.id)),
    es_pspt         : /PS|PT/i.test(m.nombre_modulo),
  }));
  renderListas(modulos);
}

function filtrarModulos() { _recargarConEstado(); }

// ── Guardar en BD ─────────────────────────────────────────────────────────
async function guardarAsignacion() {
  if (!profesorActual) { mostrarToast('Selecciona un profesor primero.', 'warn'); return; }

  const btn = document.getElementById('btnGuardar');
  btn.disabled = true;

  const asignaciones = modulosAsignados.map(id => ({ modulo_id: id }));

  try {
    const res  = await fetch(`${BASE_URL}/controladores/Controlador_asignarMod.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ profesor_id: profesorActual, asignaciones }),
    });
    const data = await res.json();

    mostrarToast(data.mensaje, data.supera_20 ? 'warn' : (data.ok ? 'ok' : 'error'));

    if (data.ok) {
      const opt = document.querySelector(`#selectProfesor option[value="${profesorActual}"]`);
      if (opt) {
        opt.dataset.horas = data.horas_totales;
        document.getElementById('horasActuales').textContent = data.horas_totales;
      }
    }
  } catch (e) {
    mostrarToast('Error de red al guardar.', 'error');
  } finally {
    btn.disabled = false;
  }
}

// ── Toast ─────────────────────────────────────────────────────────────────
let toastTimer;
function mostrarToast(msg, tipo = 'ok') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className   = 'show ' + (tipo === 'warn' ? 'warn' : tipo === 'error' ? 'error' : '');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 3500);
}
</script>
</body>
</html>
