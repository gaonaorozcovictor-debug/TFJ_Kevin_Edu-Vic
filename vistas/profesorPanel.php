<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis módulos — Asignaciones FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --accent: #e35f1f; --accent2: #f7841a;
    --dark: #1a1a2e; --light: #f4f1eb;
    --white: #fff; --muted: #777; --border: #e2dfd8;
    --ok: #0d5c35; --range: #1ea360; --warn: #e8720a; --bad: #d42b2b;
    --radius: 12px;
  }
  body { font-family: 'DM Sans', sans-serif; background: var(--light); color: var(--dark); min-height: 100vh; }

  .navbar { background: var(--dark); padding: 0 32px; height: 60px; display: flex; align-items: center; justify-content: space-between; }
  .navbar-brand { font-family: 'DM Serif Display', serif; color: #fff; font-size: 1.1rem; }
  .navbar-brand span { color: var(--accent2); }
  .btn { padding: 8px 18px; border-radius: 8px; border: none; cursor: pointer; font-family: inherit; font-size: .875rem; font-weight: 500; transition: all .2s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
  .btn-ghost { background: rgba(255,255,255,.08); color: #fff; }
  .btn-ghost:hover { background: rgba(255,255,255,.15); }

  .page { max-width: 900px; margin: 0 auto; padding: 40px 24px; }
  .welcome { margin-bottom: 28px; }
  .welcome h1 { font-family: 'DM Serif Display', serif; font-size: 2rem; }
  .welcome p { color: var(--muted); margin-top: 6px; }

  /* ── Tarjeta de horas con color dinámico ── */
  .horas-card { background: var(--dark); color: #fff; border-radius: var(--radius); padding: 24px 28px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
  .horas-label { font-size: .85rem; color: #aaa; text-transform: uppercase; letter-spacing: .05em; }
  .horas-num   { font-family: 'DM Serif Display', serif; font-size: 3rem; line-height: 1; }
  .horas-desc  { font-size: .875rem; color: #bbb; margin-top: 4px; }
  .horas-bar-wrap { flex: 1; min-width: 200px; max-width: 320px; }
  .horas-bar-bg   { height: 8px; background: rgba(255,255,255,.12); border-radius: 4px; overflow: hidden; }
  .horas-bar-fill { height: 100%; border-radius: 4px; transition: width .6s ease, background .4s ease; }

  /* ── Leyenda de colores ── */
  .leyenda-colores { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; padding: 12px 16px; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); font-size: .78rem; }
  .leyenda-item { display: flex; align-items: center; gap: 6px; font-weight: 500; }
  .leyenda-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

  .card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
  .card-header { padding: 18px 24px; border-bottom: 1px solid var(--border); font-weight: 600; font-size: .95rem; }
  .empty-state { text-align: center; padding: 60px 24px; color: var(--muted); }
  .empty-state .icon { font-size: 2.5rem; margin-bottom: 12px; }

  table { width: 100%; border-collapse: collapse; font-size: .875rem; }
  thead th { background: var(--light); padding: 10px 16px; text-align: left; font-weight: 600; font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); }
  tbody tr { border-top: 1px solid var(--border); transition: background .15s; }
  tbody tr:hover { background: #faf8f5; }
  tbody td { padding: 12px 16px; }
  tfoot td { padding: 12px 16px; background: var(--light); font-weight: 600; }

  .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
  .badge-gray   { background: #f0f0f0; color: #555; }
  .badge-purple { background: #ede9fb; color: #6b3fa0; }

  .actions-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }
  .btn-pdf { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-family: inherit; font-size: .875rem; font-weight: 600; cursor: pointer; transition: background .2s; }
  .btn-pdf:hover { background: #c94e0e; }
  .btn-pdf.loading { opacity: .6; pointer-events: none; }

  .estado-aviso { font-size: .8rem; margin-top: 6px; font-weight: 500; }
</style>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') { header('Location: /asignaciones/'); exit(); }
if (!isset($modulos)) $modulos = [];
$totalHoras = array_sum(array_column($modulos, 'horas'));
$nombreProfesor = $_SESSION['nombre'] ?? 'Profesor';
$especialidadProfesor = $_SESSION['categoria'] ?? '';
?>

<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <a href="/asignaciones/?vista=logout" class="btn btn-ghost">Cerrar sesión</a>
</nav>

<div class="page">
  <div class="welcome">
    <h1>Hola, <?= htmlspecialchars($nombreProfesor) ?></h1>
    <p>Aquí tienes los módulos que tienes asignados este curso.</p>
  </div>

  <?php if (!empty($modulos)): ?>
  <div class="actions-bar">
    <button class="btn-pdf" id="btnExportarPDF" onclick="exportarPDF()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
      </svg>
      Exportar PDF
    </button>
  </div>
  <?php endif; ?>

  <!-- Leyenda de colores -->
  <div class="leyenda-colores">
    <span style="font-weight:600;color:var(--muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;align-self:center;">Estado:</span>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--ok)"></span><span style="color:var(--ok)">Óptimo (objetivo exacto)</span></div>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--range)"></span><span style="color:var(--range)">En rango (min–max)</span></div>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--warn)"></span><span style="color:var(--warn)">Cercano al límite</span></div>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--bad)"></span><span style="color:var(--bad)">Fuera de rango</span></div>
  </div>

  <div class="horas-card">
    <div>
      <div class="horas-label">Horas semanales asignadas</div>
      <div class="horas-num" id="horasNum" style="color:var(--accent2)"><?= $totalHoras ?>h</div>
      <div class="horas-desc"><?= count($modulos) ?> módulo<?= count($modulos) !== 1 ? 's' : '' ?></div>
      <div class="estado-aviso" id="estadoAviso"></div>
    </div>
    <div class="horas-bar-wrap">
      <div style="font-size:.8rem;color:#aaa;margin-bottom:8px" id="horasBarLabel"><?= $totalHoras ?>h asignadas</div>
      <div class="horas-bar-bg"><div class="horas-bar-fill" id="horasBarFill" style="width:0%"></div></div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">📋 Módulos asignados</div>
    <?php if (empty($modulos)): ?>
      <div class="empty-state"><div class="icon">📭</div><p>No tienes módulos asignados todavía.</p></div>
    <?php else: ?>
      <table>
        <thead><tr><th>Ciclo</th><th>Módulo</th><th>Horas</th><th>Categoría</th></tr></thead>
        <tbody>
          <?php foreach ($modulos as $m): ?>
          <tr>
            <td><span class="badge badge-gray"><?= htmlspecialchars($m['grado'] ?? '-') ?></span></td>
            <td><?= htmlspecialchars($m['nombre_modulo']) ?><?php if (stripos($m['nombre_modulo'],'PS')!==false||stripos($m['nombre_modulo'],'PT')!==false): ?><span class="badge badge-purple" style="margin-left:6px">PS/PT</span><?php endif; ?></td>
            <td><strong><?= $m['horas'] ?>h</strong></td>
            <td><?= htmlspecialchars($m['categoria'] ?? '-') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot><tr><td colspan="2">Total</td><td><?= $totalHoras ?>h</td><td></td></tr></tfoot>
      </table>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
const PROFESOR_NOMBRE    = <?= json_encode($nombreProfesor) ?>;
const PROFESOR_ESPECIALIDAD = <?= json_encode($especialidadProfesor) ?>;
const TOTAL_HORAS        = <?= (int)$totalHoras ?>;
const MODULOS_DATA       = <?= json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) ?>;
const STORAGE_KEY        = 'horasConfigGlobal';

// ── Sistema de colores ──────────────────────────────────────────────
// Verde oscuro = óptimo (objetivo exacto)
// Verde claro  = en rango (entre min y max)
// Naranja      = cercano al límite (justo en min, o ligeramente sobre max)
// Rojo         = fuera de rango (muy por debajo o muy por encima)

function getCfg() {
  try {
    const s = localStorage.getItem(STORAGE_KEY);
    if (s) return { min:18, objetivo:20, max:22, ...JSON.parse(s) };
  } catch(e) {}
  return { min:18, objetivo:20, max:22 };
}

function calcularColor(h, cfg) {
  if (h === cfg.objetivo)       return '#1a6b45'; // verde oscuro — óptimo exacto
  if (h >= cfg.min && h <= cfg.max) return '#2cb67d'; // verde claro — en rango
  if (h === cfg.min - 1 || h === cfg.max + 1) return '#f7841a'; // naranja — en el límite
  return '#e05252'; // rojo — fuera de rango
}

function calcularAviso(h, cfg) {
  if (h === cfg.objetivo)            return '✅ Objetivo exacto';
  if (h > cfg.min && h < cfg.objetivo) return `⬆ Faltan ${cfg.objetivo - h}h para el objetivo`;
  if (h >= cfg.min && h < cfg.objetivo) return `⬆ En rango mínimo`;
  if (h > cfg.objetivo && h <= cfg.max) return `✅ En rango (+${h - cfg.objetivo}h)`;
  if (h < cfg.min)                   return `🔴 Por debajo del mínimo (${cfg.min}h)`;
  return `⚠️ Supera el máximo (${cfg.max}h)`;
}

function inicializarUI() {
  const cfg   = getCfg();
  const color = calcularColor(TOTAL_HORAS, cfg);
  const aviso = calcularAviso(TOTAL_HORAS, cfg);
  const pct   = cfg.objetivo > 0 ? Math.min(100, Math.round(TOTAL_HORAS / cfg.objetivo * 100)) : 0;

  const numEl   = document.getElementById('horasNum');
  const fillEl  = document.getElementById('horasBarFill');
  const labelEl = document.getElementById('horasBarLabel');
  const avisoEl = document.getElementById('estadoAviso');

  if (numEl)   { numEl.style.color = color; }
  if (fillEl)  { fillEl.style.width = pct + '%'; fillEl.style.background = color; }
  if (labelEl) { labelEl.innerHTML = `${TOTAL_HORAS}h / ${cfg.objetivo}h objetivo`; }
  if (avisoEl) { avisoEl.innerHTML = `<span style="color:${color}">${aviso}</span>`; }
}

// ── PDF ─────────────────────────────────────────────────────────────
async function exportarPDF() {
  const btn = document.getElementById('btnExportarPDF');
  btn.classList.add('loading'); btn.textContent = 'Generando PDF…';
  try {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation:'portrait', unit:'mm', format:'a4' });
    const W = doc.internal.pageSize.getWidth(), mX = 15;
    let y = 20;

    doc.setFont('helvetica','bold'); doc.setFontSize(18); doc.setTextColor(26,26,46);
    doc.text('ASIGNACIÓN DE MÓDULOS POR PROFESOR', W/2, y, {align:'center'}); y+=8;
    doc.setFontSize(12); doc.setTextColor(100,100,100);
    doc.text('CURSO 2025-2026', W/2, y, {align:'center'}); y+=15;

    const fechaHoy = new Date().toLocaleDateString('es-ES');
    doc.autoTable({ startY:y, body:[['DEPARTAMENTO:','TECNOLOGÍA'],['PROFESOR:',PROFESOR_NOMBRE],['FECHA:',fechaHoy]], theme:'plain',
      styles:{fontSize:10,cellPadding:5,lineColor:[0,0,0],lineWidth:0.2},
      columnStyles:{0:{fontStyle:'bold',textColor:[26,26,46],cellWidth:45,fillColor:[245,245,245]},1:{cellWidth:W-mX*2-45}},
      margin:{left:mX,right:mX} });
    y = doc.lastAutoTable.finalY + 12;

    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.setTextColor(26,26,46);
    doc.text('DOCENCIA', mX, y); y+=3;
    doc.setDrawColor(227,95,31); doc.setLineWidth(0.6); doc.line(mX, y, W-mX, y); y+=8;

    let totalH = 0, c = 1;
    const body = MODULOS_DATA.map(m => {
      const h = parseInt(m.horas)||0; totalH+=h;
      const n = (m.nombre_modulo||'').toUpperCase();
      const clave = n.includes('PS')?'PS':n.includes('PT')?'PT':(m.categoria||'').toUpperCase()==='SAI'?'SAI':(m.categoria||'').toUpperCase()==='INF'?'INF':'-';
      return [`FP${String(c++).padStart(2,'0')}`, m.nombre_modulo||'-', clave, `${h}h`];
    });

    doc.autoTable({ startY:y, head:[['CÓDIGO','MÓDULO A IMPARTIR','CLAVE (*)','H/SEM.']], body,
      theme:'grid', styles:{fontSize:9,cellPadding:4},
      headStyles:{fillColor:[26,26,46],textColor:[255,255,255],fontStyle:'bold'},
      alternateRowStyles:{fillColor:[250,250,250]},
      columnStyles:{0:{cellWidth:25,halign:'center'},1:{cellWidth:80},2:{cellWidth:25,halign:'center'},3:{cellWidth:30,halign:'center'}},
      margin:{left:mX,right:mX} });
    y = doc.lastAutoTable.finalY + 12;

    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.setTextColor(26,26,46);
    doc.text('OTROS CARGOS O ACTIVIDADES LECTIVAS', mX, y); y+=3;
    doc.setDrawColor(227,95,31); doc.line(mX, y, W-mX, y); y+=6;
    for (let i=0;i<4;i++) { doc.setDrawColor(200,200,200); doc.setLineWidth(0.2); doc.line(mX,y+5,W-mX,y+5); y+=8; }
    y+=8;

    doc.setFillColor(245,245,245); doc.rect(mX,y,130,12,'FD');
    doc.setFont('helvetica','bold'); doc.setFontSize(10); doc.setTextColor(26,26,46);
    doc.text('Nº TOTAL DE HORAS LECTIVAS',mX+3,y+8);
    doc.rect(mX+130,y,W-mX*2-130,12,'FD');
    doc.setTextColor(227,95,31); doc.setFontSize(11);
    doc.text(`${totalH} horas`,mX+135,y+8); y+=20;

    const pH=doc.internal.pageSize.getHeight(), yF=pH-28;
    doc.setDrawColor(180,180,180); doc.setLineWidth(0.3); doc.line(mX,yF,W-mX,yF);
    doc.setFont('helvetica',''); doc.setFontSize(9); doc.setTextColor(0,0,0);
    doc.text('Firma del Jefe/a de Departamento',mX+55,yF+6,{align:'center'});
    doc.text('Firma del Profesor/a',W-mX-45,yF+6,{align:'center'});
    doc.line(mX+20,yF+14,mX+90,yF+14); doc.line(W-mX-90,yF+14,W-mX-20,yF+14);
    doc.setFont('helvetica','italic'); doc.setFontSize(7); doc.setTextColor(100,100,100);
    doc.text('(*) Claves recogidas en el documento entregado al Jefe/a de Departamento por Jefatura de Estudios.',mX,yF+22);

    doc.save(`Asignacion_${PROFESOR_NOMBRE.replace(/\s+/g,'_')}_${fechaHoy.replace(/\//g,'-')}.pdf`);
  } catch(err) {
    alert('Error al generar el PDF: ' + err.message);
  } finally {
    btn.classList.remove('loading');
    btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg> Exportar PDF`;
  }
}

document.addEventListener('DOMContentLoaded', inicializarUI);
</script>
</body>
</html>