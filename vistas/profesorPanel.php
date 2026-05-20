<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis módulos — Asignaciones FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/profesor.css">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/darkmode.css">
<script src="/asignaciones/vistas/estilos/darkmode.js"></script>
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
  
    <button class="btn btn-ghost btn-darkmode" onclick="toggleDarkMode()" title="Cambiar a modo oscuro">🌙 Modo oscuro</button>
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
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
      <span>📋 Módulos asignados</span>
      <?php if (!empty($modulos)): ?>
      <input type="text" id="buscadorModulos" placeholder="Buscar módulo..." oninput="filtrarTabla()"
        style="padding:7px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:.85rem;outline:none;width:220px;">
      <?php endif; ?>
    </div>
    <?php if (empty($modulos)): ?>
      <div class="empty-state"><div class="icon">📭</div><p>No tienes módulos asignados todavía.</p></div>
    <?php else: ?>
      <table id="tablaModulos">
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
      <div id="sinResultados" style="display:none;text-align:center;padding:32px;color:var(--muted);">No se encontraron módulos.</div>
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

function filtrarTabla() {
  const busq = document.getElementById('buscadorModulos')?.value.toLowerCase() || '';
  const filas = document.querySelectorAll('#tablaModulos tbody tr');
  let visibles = 0;
  filas.forEach(fila => {
    const texto = fila.textContent.toLowerCase();
    const mostrar = texto.includes(busq);
    fila.style.display = mostrar ? '' : 'none';
    if (mostrar) visibles++;
  });
  const sinRes = document.getElementById('sinResultados');
  if (sinRes) sinRes.style.display = visibles === 0 ? 'block' : 'none';
}

// ── Helpers PDF ───────────────────────────────────────────────────────
// Palabras clave que indican que un módulo va a "Otros cargos"
const OTROS_CARGOS_KEYWORDS = ['tutoria','tutoría','tutor','guardia','coordinacion','coordinación','formacion en centros','fct','empresa'];

function esOtroCargo(nombre) {
  const n = (nombre||'').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
  return OTROS_CARGOS_KEYWORDS.some(k => n.includes(k));
}

function getClave(m) {
  const n = (m.nombre_modulo||'').toUpperCase();
  const c = (m.categoria||'').toUpperCase();
  if (n.includes('PS') || c.includes('PS')) return 'PS';
  if (n.includes('PT') || c.includes('PT')) return 'PT';
  if (c === 'SAI') return 'SAI';
  if (c === 'INF') return 'INF';
  return '-';
}

function seccionHeader(doc, texto, y, W, mX) {
  doc.setFont('helvetica','bold'); doc.setFontSize(11); doc.setTextColor(26,26,46);
  doc.text(texto, mX, y); y += 2;
  doc.setDrawColor(26,26,46); doc.setLineWidth(0.4); doc.line(mX, y, W-mX, y);
  return y + 5;
}

function firmasYNota(doc, W, mX) {
  // Siempre al final de la ÚLTIMA página
  const pH = doc.internal.pageSize.getHeight();
  const yF = pH - 32;
  doc.setDrawColor(180,180,180); doc.setLineWidth(0.3); doc.line(mX, yF, W-mX, yF);
  doc.setFont('helvetica',''); doc.setFontSize(9); doc.setTextColor(0,0,0);
  doc.text('Firma del Jefe/a de Departamento', mX + 45, yF + 6, {align:'center'});
  doc.text('Firma del Profesor/a', W - mX - 40, yF + 6, {align:'center'});
  doc.line(mX + 5,  yF + 16, mX + 85,  yF + 16);
  doc.line(W-mX-85, yF + 16, W-mX-5,   yF + 16);
  doc.setFont('helvetica','italic'); doc.setFontSize(7); doc.setTextColor(120,120,120);
  doc.text('(*) Es indispensable rellenar con las claves recogidas en el documento entregado al Jefe/a de Departamento por Jefatura de Estudios.', mX, yF + 24, {maxWidth: W - mX*2});
}

// ── PDF principal ─────────────────────────────────────────────────────
async function exportarPDF() {
  const btn = document.getElementById('btnExportarPDF');
  btn.classList.add('loading'); btn.textContent = 'Generando PDF…';

  try {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation:'portrait', unit:'mm', format:'a4' });
    const W  = doc.internal.pageSize.getWidth();   // 210mm
    const mX = 14;
    let y = 14;

    // ── 1. CABECERA IES ───────────────────────────────────────────────
    // Bloque izquierdo: nombre centro
    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.setTextColor(26,26,46);
    doc.text('I.E.S. CIUDAD ESCOLAR', mX, y);
    // Código centro alineado a la derecha
    doc.setFont('helvetica','normal'); doc.setFontSize(8); doc.setTextColor(80,80,80);
    doc.text('Código Centro: 28022724', W - mX, y, {align:'right'});
    y += 5;
    doc.setFontSize(7.5);
    doc.text('Ctra. Colmenar Viejo, Km. 12,800 - 28049 Madrid  |  JEFATURA DE ESTUDIOS', mX, y);
    doc.text('T: 91 734 12 44  |  ies.ciudadescolar.madrid@educa.madrid.org', mX, y+4);
    y += 10;
    // Línea separadora
    doc.setDrawColor(26,26,46); doc.setLineWidth(0.6); doc.line(mX, y, W-mX, y);
    y += 7;

    // ── 2. TÍTULO ─────────────────────────────────────────────────────
    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.setTextColor(26,26,46);
    doc.text('ASIGNACIÓN DE MÓDULOS POR PROFESOR', W/2, y, {align:'center'});
    y += 6;
    doc.setFontSize(10); doc.setFont('helvetica','bold');
    doc.text('CURSO 2025-2026', W/2, y, {align:'center'});
    y += 8;

    // ── 3. DATOS DEL PROFESOR ─────────────────────────────────────────
    const fechaHoy = new Date().toLocaleDateString('es-ES');
    const colLabel = 38, colVal = W - mX*2 - colLabel;
    doc.autoTable({
      startY: y,
      body: [
        ['DEPARTAMENTO:', PROFESOR_ESPECIALIDAD || 'TECNOLOGÍA'],
        ['PROFESOR/A:',   PROFESOR_NOMBRE],
        ['FECHA:',        fechaHoy]
      ],
      theme: 'grid',
      styles: { fontSize: 9.5, cellPadding: {top:3,bottom:3,left:4,right:4}, lineColor:[180,180,180], lineWidth:0.3 },
      columnStyles: {
        0: { fontStyle:'bold', fillColor:[240,240,240], cellWidth: colLabel, textColor:[26,26,46] },
        1: { cellWidth: colVal }
      },
      margin: { left: mX, right: mX }
    });
    y = doc.lastAutoTable.finalY + 8;

    // ── 4. Separar módulos vs otros cargos ────────────────────────────
    const modulos    = MODULOS_DATA.filter(m => !esOtroCargo(m.nombre_modulo));
    const otrosCargos = MODULOS_DATA.filter(m =>  esOtroCargo(m.nombre_modulo));

    // ── 5. SECCIÓN DOCENCIA ───────────────────────────────────────────
    y = seccionHeader(doc, 'DOCENCIA', y, W, mX);

    let totalHModulos = 0;
    const bodyDocencia = modulos.map((m, i) => {
      const h = parseInt(m.horas) || 0;
      totalHModulos += h;
      return [
        `FP${String(i+1).padStart(2,'0')}`,
        m.nombre_modulo || '-',
        getClave(m),
        `${h}`
      ];
    });

    // Añadir filas vacías si hay menos de 6 (como la plantilla original)
    const minFilas = Math.max(6, bodyDocencia.length);
    while (bodyDocencia.length < minFilas) bodyDocencia.push(['','','','']);

    doc.autoTable({
      startY: y,
      head: [['CÓDIGO\nCICLO FORMATIVO (*)', 'MÓDULO A IMPARTIR', 'CLAVE\nMÓDULO (*)', 'HORAS\n/ SEM.']],
      body: bodyDocencia,
      theme: 'grid',
      styles: { fontSize: 8.5, cellPadding: {top:3,bottom:3,left:3,right:3}, lineColor:[180,180,180], lineWidth:0.3, valign:'middle' },
      headStyles: { fillColor:[26,26,46], textColor:[255,255,255], fontStyle:'bold', fontSize:8, halign:'center', valign:'middle' },
      columnStyles: {
        0: { cellWidth: 38, halign:'center' },
        1: { cellWidth: W - mX*2 - 38 - 28 - 22 },
        2: { cellWidth: 28, halign:'center' },
        3: { cellWidth: 22, halign:'center' }
      },
      margin: { left: mX, right: mX },
      // Si hay muchos módulos, continúa en siguiente página automáticamente
      rowPageBreak: 'auto',
      pageBreak: 'auto',
      // Repetir cabecera en cada página
      showHead: 'everyPage',
      didDrawPage: (data) => {
        // En páginas nuevas, no redibujar la cabecera del documento
      }
    });
    y = doc.lastAutoTable.finalY + 8;

    // ── 6. SECCIÓN OTROS CARGOS ───────────────────────────────────────
    // Comprueba si cabe en la página actual, si no salto de página
    const pH = doc.internal.pageSize.getHeight();
    const espacioNecesario = 50 + otrosCargos.length * 8;
    if (y + espacioNecesario > pH - 40) {
      doc.addPage();
      y = 20;
    }

    y = seccionHeader(doc, 'OTROS CARGOS O ACTIVIDADES LECTIVAS', y, W, mX);

    let totalHOtros = 0;
    const bodyOtros = otrosCargos.map(m => {
      const h = parseInt(m.horas) || 0;
      totalHOtros += h;
      return [m.nombre_modulo || '-', `${h}`];
    });
    // Mínimo 4 filas vacías (como plantilla)
    const minFilasOtros = Math.max(4, bodyOtros.length);
    while (bodyOtros.length < minFilasOtros) bodyOtros.push(['','']);

    doc.autoTable({
      startY: y,
      body: bodyOtros,
      theme: 'grid',
      styles: { fontSize: 8.5, cellPadding: {top:3,bottom:3,left:3,right:3}, lineColor:[180,180,180], lineWidth:0.3, minCellHeight:8 },
      columnStyles: {
        0: { cellWidth: W - mX*2 - 22 },
        1: { cellWidth: 22, halign:'center' }
      },
      margin: { left: mX, right: mX }
    });
    y = doc.lastAutoTable.finalY + 6;

    // ── 7. TOTAL DE HORAS ─────────────────────────────────────────────
    const totalFinal = totalHModulos + totalHOtros;
    const anchoLabel = W - mX*2 - 30;

    doc.autoTable({
      startY: y,
      body: [['Nº TOTAL DE HORAS LECTIVAS (docencia, cargos y otras actividades)', `${totalFinal}`]],
      theme: 'grid',
      styles: { fontSize: 8.5, cellPadding: {top:4,bottom:4,left:4,right:4}, lineColor:[26,26,46], lineWidth:0.4 },
      bodyStyles: { fontStyle:'bold', fillColor:[240,240,240] },
      columnStyles: {
        0: { cellWidth: anchoLabel },
        1: { cellWidth: 30, halign:'center', textColor:[26,26,46] }
      },
      margin: { left: mX, right: mX }
    });

    // ── 8. NOTA PIE ───────────────────────────────────────────────────
    y = doc.lastAutoTable.finalY + 5;
    doc.setFont('helvetica','italic'); doc.setFontSize(7); doc.setTextColor(100,100,100);
    doc.text('(*) Es indispensable rellenar con las claves recogidas en el documento entregado al Jefe/a de Departamento por Jefatura de Estudios.', mX, y, {maxWidth: W - mX*2});

    // ── 9. FIRMAS (fijadas al pie de la última página) ────────────────
    firmasYNota(doc, W, mX);

    // ── 10. GUARDAR ───────────────────────────────────────────────────
    doc.save(`Asignacion_${PROFESOR_NOMBRE.replace(/\s+/g,'_')}_${fechaHoy.replace(/\//g,'-')}.pdf`);

  } catch(err) {
    console.error(err);
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