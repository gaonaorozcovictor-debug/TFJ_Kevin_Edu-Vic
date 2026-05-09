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
    --accent:   #e35f1f;
    --accent2:  #f7841a;
    --dark:     #1a1a2e;
    --light:    #f4f1eb;
    --white:    #ffffff;
    --muted:    #777;
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
    padding: 0 32px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .navbar-brand { font-family: 'DM Serif Display', serif; color: #fff; font-size: 1.1rem; }
  .navbar-brand span { color: #f7841a; }

  .btn {
    padding: 8px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-family: inherit;
    font-size: .875rem;
    font-weight: 500;
    transition: all .2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-ghost { background: rgba(255,255,255,.08); color: #fff; }
  .btn-ghost:hover { background: rgba(255,255,255,.15); }

  .page { max-width: 900px; margin: 0 auto; padding: 40px 24px; }

  .welcome { margin-bottom: 28px; }
  .welcome h1 { font-family: 'DM Serif Display', serif; font-size: 2rem; color: var(--dark); }
  .welcome p  { color: var(--muted); margin-top: 6px; }

  /* ── Horas card ── */
  .horas-card {
    background: var(--dark);
    color: #fff;
    border-radius: var(--radius);
    padding: 24px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 16px;
  }
  .horas-label { font-size: .85rem; color: #aaa; text-transform: uppercase; letter-spacing: .05em; }
  .horas-num   { font-family: 'DM Serif Display', serif; font-size: 3rem; color: #f7841a; line-height: 1; }
  .horas-desc  { font-size: .875rem; color: #bbb; margin-top: 4px; }
  .horas-bar-wrap { flex: 1; min-width: 200px; max-width: 320px; }
  .horas-bar-bg   { height: 8px; background: rgba(255,255,255,.12); border-radius: 4px; overflow: hidden; }
  .horas-bar-fill { height: 100%; border-radius: 4px; transition: width .6s ease; }

  /* ── Límites card ── */
  .limites-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 24px;
    margin-bottom: 24px;
    display: none; /* se muestra via JS si hay config */
  }
  .limites-title {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted);
    margin-bottom: 14px;
  }
  .limites-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
  }
  .limite-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    flex: 1;
    min-width: 80px;
  }
  .limite-valor {
    font-family: 'DM Serif Display', serif;
    font-size: 1.8rem;
    line-height: 1;
    font-weight: 700;
  }
  .limite-label {
    font-size: .7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--muted);
  }
  .limite-sep { color: var(--border); font-size: 1.5rem; flex: 0; }
  .limite-min  { color: var(--danger); }
  .limite-obj  { color: var(--success); }
  .limite-max  { color: var(--purple); }

  .limite-bar-wrap { flex: 2; min-width: 160px; }
  .limite-bar-bg   { height: 10px; background: #f0f0f0; border-radius: 6px; position: relative; overflow: visible; margin-top: 4px; }
  .limite-bar-fill { height: 100%; border-radius: 6px; transition: width .6s ease, background .3s; position: relative; }
  .limite-aviso    { font-size: .78rem; margin-top: 8px; font-weight: 500; }

  /* ── Tabla ── */
  .card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
  .card-header { padding: 18px 24px; border-bottom: 1px solid var(--border); font-weight: 600; font-size: .95rem; }

  .empty-state { text-align: center; padding: 60px 24px; color: var(--muted); }
  .empty-state .icon { font-size: 2.5rem; margin-bottom: 12px; }

  table { width: 100%; border-collapse: collapse; font-size: .875rem; }
  thead th {
    background: var(--light);
    padding: 10px 16px;
    text-align: left;
    font-weight: 600;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--muted);
  }
  tbody tr { border-top: 1px solid var(--border); transition: background .15s; }
  tbody tr:hover { background: #faf8f5; }
  tbody td { padding: 12px 16px; }
  tfoot td { padding: 12px 16px; background: var(--light); font-weight: 600; }

  .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
  .badge-gray   { background: #f0f0f0; color: #555; }
  .badge-purple { background: #ede9fb; color: #6b3fa0; }

  /* ── Botón exportar PDF ── */
  .actions-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }

  .btn-pdf {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-family: inherit;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s, transform .1s;
    text-decoration: none;
  }
  .btn-pdf:hover  { background: #c94e0e; }
  .btn-pdf:active { transform: scale(.97); }
  .btn-pdf svg    { flex-shrink: 0; }
  .btn-pdf.loading { opacity: .6; pointer-events: none; }
</style>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header('Location: /asignaciones/');
    exit();
}
if (!isset($modulos)) $modulos = [];

$totalHoras    = array_sum(array_column($modulos, 'horas'));
$nombreProfesor = $_SESSION['nombre'] ?? 'Profesor';

// Obtener especialidad del profesor (si existe en sesión o modelo)
$especialidadProfesor = $_SESSION['categoria'] ?? ($_SESSION['especialidad'] ?? '');
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

  <!-- Botón exportar PDF -->
  <?php if (!empty($modulos)): ?>
  <div class="actions-bar">
    <button class="btn-pdf" id="btnExportarPDF" onclick="exportarPDF()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="12" y1="18" x2="12" y2="12"/>
        <line x1="9" y1="15" x2="15" y2="15"/>
      </svg>
      Exportar PDF
    </button>
  </div>
  <?php endif; ?>

  <!-- Resumen horas -->
  <div class="horas-card">
    <div>
      <div class="horas-label">Horas semanales asignadas</div>
      <div class="horas-num" id="horasNumDisplay"><?= $totalHoras ?>h</div>
      <div class="horas-desc"><?= count($modulos) ?> módulo<?= count($modulos) !== 1 ? 's' : '' ?></div>
    </div>
    <div class="horas-bar-wrap">
      <div style="font-size:.8rem;color:#aaa;margin-bottom:8px" id="horasBarLabel">
        <?= $totalHoras ?>h asignadas
      </div>
      <div class="horas-bar-bg">
        <div class="horas-bar-fill" id="horasBarFill" style="width:0%;background:#f7841a"></div>
      </div>
    </div>
  </div>

  <!-- Límites asignados por el admin (se muestra solo si existen en localStorage) -->
  <div class="limites-card" id="limitesCard">
    <div class="limites-title">🎯 Límites de horas establecidos por el administrador</div>
    <div class="limites-row">
      <div class="limite-item">
        <span class="limite-valor limite-min" id="limValMin">—</span>
        <span class="limite-label">Mínimo</span>
      </div>
      <span class="limite-sep">·</span>
      <div class="limite-item">
        <span class="limite-valor limite-obj" id="limValObj">—</span>
        <span class="limite-label">Objetivo</span>
      </div>
      <span class="limite-sep">·</span>
      <div class="limite-item">
        <span class="limite-valor limite-max" id="limValMax">—</span>
        <span class="limite-label">Máximo</span>
      </div>
      <div class="limite-bar-wrap">
        <div class="limite-bar-bg">
          <div class="limite-bar-fill" id="limBarFill" style="width:0%"></div>
        </div>
        <div class="limite-aviso" id="limAviso"></div>
      </div>
    </div>
  </div>

  <!-- Tabla módulos -->
  <div class="card">
    <div class="card-header">📋 Módulos asignados</div>

    <?php if (empty($modulos)): ?>
      <div class="empty-state">
        <div class="icon">📭</div>
        <p>No tienes módulos asignados todavía.</p>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Ciclo</th>
            <th>Módulo</th>
            <th>Horas</th>
            <th>Categoría</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($modulos as $m):
            $esPsPt = stripos($m['nombre_modulo'], 'PS') !== false || stripos($m['nombre_modulo'], 'PT') !== false;
          ?>
          <tr>
            <td><span class="badge badge-gray"><?= htmlspecialchars($m['grado'] ?? '-') ?></span></td>
            <td>
              <?= htmlspecialchars($m['nombre_modulo']) ?>
              <?php if ($esPsPt): ?><span class="badge badge-purple" style="margin-left:6px">PS/PT</span><?php endif; ?>
            </td>
            <td><strong><?= $m['horas'] ?>h</strong></td>
            <td><?= htmlspecialchars($m['categoria'] ?? '-') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2">Total</td>
            <td><?= $totalHoras ?>h</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    <?php endif; ?>
  </div>

</div>

<!-- jsPDF desde CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
// ── Datos PHP → JS ────────────────────────────────────────────────────────────
const PROFESOR_ID    = <?= json_encode((int)($_SESSION['profesor_id'] ?? 0)) ?>;
const PROFESOR_NOMBRE = <?= json_encode($nombreProfesor) ?>;
const PROFESOR_ESPECIALIDAD = <?= json_encode($especialidadProfesor) ?>;
const TOTAL_HORAS    = <?= (int)$totalHoras ?>;
const MODULOS_DATA   = <?= json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) ?>;
const STORAGE_KEY    = 'horasConfigProfesor';

// ── Cargar límites del admin desde localStorage ────────────────────────────
function cargarLimites() {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (!stored) return null;
        const cfg = JSON.parse(stored);
        return cfg[PROFESOR_ID] || null;
    } catch(e) {
        return null;
    }
}

function calcularColor(horas, cfg) {
    if (horas < cfg.min)      return '#e05252';
    if (horas < cfg.objetivo) return '#f7841a';
    if (horas <= cfg.max)     return '#2cb67d';
    return '#7c3aed';
}

function calcularAviso(horas, cfg) {
    if (horas < cfg.min)      return `🔴 Por debajo del mínimo (${cfg.min}h)`;
    if (horas < cfg.objetivo) return `⚠️ Faltan ${cfg.objetivo - horas}h para el objetivo`;
    if (horas === cfg.objetivo) return `✅ Objetivo cumplido exactamente`;
    if (horas <= cfg.max)     return `✅ En rango (+${horas - cfg.objetivo}h sobre el objetivo)`;
    return `⚠️ Supera el máximo en +${horas - cfg.max}h`;
}

function inicializarUI() {
    const cfg = cargarLimites();
    const barFill = document.getElementById('horasBarFill');
    const barLabel = document.getElementById('horasBarLabel');

    if (cfg) {
        // Mostrar tarjeta de límites
        const card = document.getElementById('limitesCard');
        card.style.display = 'block';

        document.getElementById('limValMin').textContent = cfg.min + 'h';
        document.getElementById('limValObj').textContent = cfg.objetivo + 'h';
        document.getElementById('limValMax').textContent = cfg.max + 'h';

        const color  = calcularColor(TOTAL_HORAS, cfg);
        const aviso  = calcularAviso(TOTAL_HORAS, cfg);
        const pctBar = cfg.max > 0 ? Math.min(100, Math.round(TOTAL_HORAS / cfg.max * 100)) : 0;

        const limBar = document.getElementById('limBarFill');
        limBar.style.width      = pctBar + '%';
        limBar.style.background = color;

        document.getElementById('limAviso').innerHTML = `<span style="color:${color}">${aviso}</span>`;

        // Barra principal también con color contextual
        if (barFill) {
            const pctMain = cfg.objetivo > 0 ? Math.min(100, Math.round(TOTAL_HORAS / cfg.objetivo * 100)) : 0;
            barFill.style.width      = pctMain + '%';
            barFill.style.background = color;
        }
        if (barLabel) {
            barLabel.innerHTML = `${TOTAL_HORAS}h / ${cfg.objetivo}h objetivo &nbsp;<span style="color:${color};font-size:.75rem;">${aviso}</span>`;
        }
    } else {
        // Sin config: barra simple con 20h como referencia
        const pct = Math.min(100, Math.round(TOTAL_HORAS / 20 * 100));
        if (barFill) {
            barFill.style.width      = pct + '%';
            barFill.style.background = TOTAL_HORAS > 20 ? '#e05252' : '#f7841a';
        }
        if (barLabel) {
            barLabel.textContent = `${TOTAL_HORAS}h asignadas`;
        }
    }
}

// ── Exportar PDF con jsPDF ─────────────────────────────────────────────────
async function exportarPDF() {
    const btn = document.getElementById('btnExportarPDF');
    btn.classList.add('loading');
    btn.textContent = 'Generando PDF…';

    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

        const W = 210;  // ancho A4
        const MARGIN = 20;
        const ACCENT  = [227, 95, 31];   // #e35f1f
        const DARK    = [26, 26, 46];    // #1a1a2e
        const LIGHT   = [244, 241, 235]; // #f4f1eb
        const MUTED   = [119, 119, 119];
        const SUCCESS = [44, 182, 125];
        const DANGER  = [224, 82, 82];
        const PURPLE  = [124, 58, 237];

        // ── Cabecera ──────────────────────────────────────────────────────
        // Fondo oscuro superior
        doc.setFillColor(...DARK);
        doc.rect(0, 0, W, 28, 'F');

        // Logo / título
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(16);
        doc.setTextColor(255, 255, 255);
        doc.text('Ciudad Escolar', MARGIN, 13);

        doc.setTextColor(...ACCENT);
        doc.text(' FP', MARGIN + doc.getTextWidth('Ciudad Escolar'), 13);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.setTextColor(180, 180, 180);
        doc.text('Asignación de Módulos — Documento oficial', MARGIN, 20);

        // Fecha generación (derecha)
        const fechaHoy = new Date().toLocaleDateString('es-ES', { day:'2-digit', month:'2-digit', year:'numeric' });
        doc.setFontSize(8);
        doc.setTextColor(150, 150, 150);
        doc.text(`Generado: ${fechaHoy}`, W - MARGIN, 20, { align: 'right' });

        // ── Datos del profesor ────────────────────────────────────────────
        let y = 38;

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(20);
        doc.setTextColor(...DARK);
        doc.text('Asignación de Módulos', MARGIN, y);
        y += 9;

        // Línea separadora accent
        doc.setDrawColor(...ACCENT);
        doc.setLineWidth(0.8);
        doc.line(MARGIN, y, W - MARGIN, y);
        y += 8;

        // Ficha datos
        const fichaData = [
            ['Profesor', PROFESOR_NOMBRE],
            ['Fecha',    fechaHoy],
        ];
        if (PROFESOR_ESPECIALIDAD) fichaData.push(['Especialidad', PROFESOR_ESPECIALIDAD]);

        fichaData.forEach(([etiqueta, valor]) => {
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(8);
            doc.setTextColor(...MUTED);
            doc.text(etiqueta.toUpperCase(), MARGIN, y);

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(11);
            doc.setTextColor(...DARK);
            doc.text(valor || '—', MARGIN, y + 5);
            y += 13;
        });

        // ── Límites (si existen) ──────────────────────────────────────────
        const cfg = cargarLimites();
        if (cfg) {
            y += 2;
            doc.setFillColor(...LIGHT);
            doc.roundedRect(MARGIN, y, W - MARGIN * 2, 22, 3, 3, 'F');

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(7);
            doc.setTextColor(...MUTED);
            doc.text('LÍMITES DE HORAS ESTABLECIDOS POR EL ADMINISTRADOR', MARGIN + 4, y + 6);

            // Tres valores
            const colW = (W - MARGIN * 2) / 3;

            const limItems = [
                { label: 'MÍNIMO',   val: cfg.min + 'h',      color: DANGER },
                { label: 'OBJETIVO', val: cfg.objetivo + 'h', color: SUCCESS },
                { label: 'MÁXIMO',   val: cfg.max + 'h',      color: PURPLE },
            ];
            limItems.forEach((item, i) => {
                const cx = MARGIN + colW * i + colW / 2;
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(14);
                doc.setTextColor(...item.color);
                doc.text(item.val, cx, y + 15, { align: 'center' });
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(7);
                doc.setTextColor(...MUTED);
                doc.text(item.label, cx, y + 20, { align: 'center' });
            });
            y += 28;
        }

        // ── Total horas asignadas ─────────────────────────────────────────
        y += 4;
        doc.setFillColor(...DARK);
        doc.roundedRect(MARGIN, y, W - MARGIN * 2, 18, 3, 3, 'F');

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(8);
        doc.setTextColor(170, 170, 170);
        doc.text('HORAS SEMANALES ASIGNADAS', MARGIN + 6, y + 7);

        doc.setFontSize(18);
        doc.setTextColor(...ACCENT);
        doc.text(`${TOTAL_HORAS}h`, MARGIN + 6, y + 15);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(9);
        doc.setTextColor(180, 180, 180);
        doc.text(`${MODULOS_DATA.length} módulo${MODULOS_DATA.length !== 1 ? 's' : ''}`, W - MARGIN - 6, y + 15, { align: 'right' });

        y += 26;

        // ── Tabla de módulos ──────────────────────────────────────────────
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.setTextColor(...DARK);
        doc.text('Módulos asignados', MARGIN, y);
        y += 4;

        const tableBody = MODULOS_DATA.map(m => [
            m.grado || '-',
            m.nombre_modulo || '-',
            (m.horas || 0) + 'h',
            m.categoria || '-'
        ]);

        // Fila de total
        tableBody.push(['', 'TOTAL', `${TOTAL_HORAS}h`, '']);

        doc.autoTable({
            startY: y,
            head: [['Ciclo', 'Módulo', 'Horas', 'Categoría']],
            body: tableBody,
            margin: { left: MARGIN, right: MARGIN },
            styles: {
                font: 'helvetica',
                fontSize: 9,
                cellPadding: 4,
                textColor: [...DARK],
                lineColor: [226, 223, 216],
                lineWidth: 0.3,
            },
            headStyles: {
                fillColor: [...DARK],
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                fontSize: 8,
            },
            alternateRowStyles: { fillColor: [250, 248, 245] },
            // Última fila (total) en negrita
            didParseCell: function(data) {
                if (data.row.index === tableBody.length - 1) {
                    data.cell.styles.fontStyle = 'bold';
                    data.cell.styles.fillColor = [...LIGHT];
                }
            },
            columnStyles: {
                0: { cellWidth: 28 },
                2: { cellWidth: 18, halign: 'center' },
                3: { cellWidth: 28, halign: 'center' },
            },
        });

        // ── Pie de página ────────────────────────────────────────────────
        const pageH = doc.internal.pageSize.getHeight();
        doc.setDrawColor(...LIGHT);
        doc.setLineWidth(0.5);
        doc.line(MARGIN, pageH - 14, W - MARGIN, pageH - 14);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(7.5);
        doc.setTextColor(...MUTED);
        doc.text(`Profesor: ${PROFESOR_NOMBRE}`, MARGIN, pageH - 8);
        doc.text('Ciudad Escolar FP', W - MARGIN, pageH - 8, { align: 'right' });

        // ── Guardar ────────────────────────────────────────────────────────
        const nombreArchivo = `Modulos_${PROFESOR_NOMBRE.replace(/\s+/g, '_')}_${fechaHoy.replace(/\//g, '-')}.pdf`;
        doc.save(nombreArchivo);

    } catch(err) {
        console.error('Error al generar PDF:', err);
        alert('Error al generar el PDF. Por favor, inténtalo de nuevo.');
    } finally {
        btn.classList.remove('loading');
        btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg> Exportar PDF`;
    }
}

// ── Arrancar ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', inicializarUI);
</script>

</body>
</html>
