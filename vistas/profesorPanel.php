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

  .limites-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 24px;
    margin-bottom: 24px;
    display: none;
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
  .limite-bar-bg   { height: 10px; background: #f0f0f0; border-radius: 6px; overflow: visible; margin-top: 4px; }
  .limite-bar-fill { height: 100%; border-radius: 6px; transition: width .6s ease; }
  .limite-aviso    { font-size: .78rem; margin-top: 8px; font-weight: 500; }

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
    transition: background .2s;
  }
  .btn-pdf:hover { background: #c94e0e; }
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

  <?php if (!empty($modulos)): ?>
  <div class="actions-bar">
    <button class="btn-pdf" id="btnExportarPDF" onclick="exportarPDF()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="12" y1="18" x2="12" y2="12"/>
        <line x1="9" y1="15" x2="15" y2="15"/>
      </svg>
      Exportar PDF
    </button>
  </div>
  <?php endif; ?>

  <div class="horas-card">
    <div>
      <div class="horas-label">Horas semanales asignadas</div>
      <div class="horas-num" id="horasNumDisplay"><?= $totalHoras ?>h</div>
      <div class="horas-desc"><?= count($modulos) ?> módulo<?= count($modulos) !== 1 ? 's' : '' ?></div>
    </div>
    <div class="horas-bar-wrap">
      <div style="font-size:.8rem;color:#aaa;margin-bottom:8px" id="horasBarLabel"><?= $totalHoras ?>h asignadas</div>
      <div class="horas-bar-bg"><div class="horas-bar-fill" id="horasBarFill" style="width:<?= min(100, ($totalHoras/20)*100) ?>%;background:#f7841a"></div></div>
    </div>
  </div>

  <div class="limites-card" id="limitesCard">
    <div class="limites-title">🎯 Límites de horas establecidos por el administrador</div>
    <div class="limites-row">
      <div class="limite-item"><span class="limite-valor limite-min" id="limValMin">—</span><span class="limite-label">Mínimo</span></div>
      <span class="limite-sep">·</span>
      <div class="limite-item"><span class="limite-valor limite-obj" id="limValObj">—</span><span class="limite-label">Objetivo</span></div>
      <span class="limite-sep">·</span>
      <div class="limite-item"><span class="limite-valor limite-max" id="limValMax">—</span><span class="limite-label">Máximo</span></div>
      <div class="limite-bar-wrap"><div class="limite-bar-bg"><div class="limite-bar-fill" id="limBarFill" style="width:0%"></div></div><div class="limite-aviso" id="limAviso"></div></div>
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
            <td><?= htmlspecialchars($m['nombre_modulo']) ?><?php if (stripos($m['nombre_modulo'], 'PS') !== false || stripos($m['nombre_modulo'], 'PT') !== false): ?><span class="badge badge-purple" style="margin-left:6px">PS/PT</span><?php endif; ?></td>
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
const PROFESOR_ID = <?= json_encode((int)($_SESSION['profesor_id'] ?? 0)) ?>;
const PROFESOR_NOMBRE = <?= json_encode($nombreProfesor) ?>;
const PROFESOR_ESPECIALIDAD = <?= json_encode($especialidadProfesor) ?>;
const TOTAL_HORAS = <?= (int)$totalHoras ?>;
const MODULOS_DATA = <?= json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) ?>;
const STORAGE_KEY = 'horasConfigProfesor';

function cargarLimites() {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (!stored) return null;
        const cfg = JSON.parse(stored);
        return cfg[PROFESOR_ID] || null;
    } catch(e) { return null; }
}

function calcularColor(horas, cfg) {
    if (horas < cfg.min) return '#e05252';
    if (horas < cfg.objetivo) return '#f7841a';
    if (horas <= cfg.max) return '#2cb67d';
    return '#7c3aed';
}

function calcularAviso(horas, cfg) {
    if (horas < cfg.min) return `🔴 Por debajo del mínimo (${cfg.min}h)`;
    if (horas < cfg.objetivo) return `⚠️ Faltan ${cfg.objetivo - horas}h para el objetivo`;
    if (horas === cfg.objetivo) return `✅ Objetivo cumplido exactamente`;
    if (horas <= cfg.max) return `✅ En rango (+${horas - cfg.objetivo}h sobre el objetivo)`;
    return `⚠️ Supera el máximo en +${horas - cfg.max}h`;
}

function inicializarUI() {
    const cfg = cargarLimites();
    const barFill = document.getElementById('horasBarFill');
    const barLabel = document.getElementById('horasBarLabel');

    if (cfg) {
        const card = document.getElementById('limitesCard');
        card.style.display = 'block';
        document.getElementById('limValMin').textContent = cfg.min + 'h';
        document.getElementById('limValObj').textContent = cfg.objetivo + 'h';
        document.getElementById('limValMax').textContent = cfg.max + 'h';
        const color = calcularColor(TOTAL_HORAS, cfg);
        const aviso = calcularAviso(TOTAL_HORAS, cfg);
        const pctBar = cfg.max > 0 ? Math.min(100, Math.round(TOTAL_HORAS / cfg.max * 100)) : 0;
        const limBar = document.getElementById('limBarFill');
        limBar.style.width = pctBar + '%';
        limBar.style.background = color;
        document.getElementById('limAviso').innerHTML = `<span style="color:${color}">${aviso}</span>`;
        if (barFill) {
            const pctMain = cfg.objetivo > 0 ? Math.min(100, Math.round(TOTAL_HORAS / cfg.objetivo * 100)) : 0;
            barFill.style.width = pctMain + '%';
            barFill.style.background = color;
        }
        if (barLabel) barLabel.innerHTML = `${TOTAL_HORAS}h / ${cfg.objetivo}h objetivo &nbsp;<span style="color:${color};font-size:.75rem;">${aviso}</span>`;
    }
}

// EXPORTAR PDF CON PLANTILLA OFICIAL CORRECTA
async function exportarPDF() {
    const btn = document.getElementById('btnExportarPDF');
    btn.classList.add('loading');
    btn.textContent = 'Generando PDF…';

    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        
        const pageWidth = doc.internal.pageSize.getWidth();
        const marginX = 15;
        let y = 20;
        
        const COLOR_NARANJA = [227, 95, 31];
        const COLOR_OSCURO = [26, 26, 46];
        const COLOR_GRIS = [100, 100, 100];
        
        // ==================== TÍTULO ====================
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(18);
        doc.setTextColor(...COLOR_OSCURO);
        doc.text('ASIGNACIÓN DE MÓDULOS POR PROFESOR', pageWidth / 2, y, { align: 'center' });
        y += 8;
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor(...COLOR_GRIS);
        doc.text('CURSO 2025-2026', pageWidth / 2, y, { align: 'center' });
        y += 15;
        
        // ==================== DATOS DEL PROFESOR ====================
        const fechaHoy = new Date().toLocaleDateString('es-ES');
        
        doc.autoTable({
            startY: y,
            body: [
                ['DEPARTAMENTO:', 'TECNOLOGÍA'],
                ['PROFESOR:', PROFESOR_NOMBRE],
                ['FECHA:', fechaHoy]
            ],
            theme: 'plain',
            styles: { fontSize: 10, cellPadding: 5, lineColor: [0, 0, 0], lineWidth: 0.2 },
            columnStyles: {
                0: { fontStyle: 'bold', textColor: COLOR_OSCURO, cellWidth: 45, fillColor: [245, 245, 245] },
                1: { cellWidth: pageWidth - marginX * 2 - 45 }
            },
            margin: { left: marginX, right: marginX },
            tableWidth: 'auto'
        });
        
        y = doc.lastAutoTable.finalY + 12;
        
        // ==================== SECCIÓN DOCENCIA ====================
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(13);
        doc.setTextColor(...COLOR_OSCURO);
        doc.text('DOCENCIA', marginX, y);
        y += 3;
        doc.setDrawColor(...COLOR_NARANJA);
        doc.setLineWidth(0.6);
        doc.line(marginX, y, pageWidth - marginX, y);
        y += 8;
        
        // ==================== TABLA DE MÓDULOS ====================
        const tableBody = [];
        let horasTotales = 0;
        let contador = 1;
        
        for (const mod of MODULOS_DATA) {
            const horas = parseInt(mod.horas) || 0;
            horasTotales += horas;
            
            let clave = '-';
            const nombreUpper = (mod.nombre_modulo || '').toUpperCase();
            if (nombreUpper.includes('PS')) clave = 'PS';
            else if (nombreUpper.includes('PT')) clave = 'PT';
            else if ((mod.categoria || '').toUpperCase() === 'SAI') clave = 'SAI';
            else if ((mod.categoria || '').toUpperCase() === 'INF') clave = 'INF';
            
            tableBody.push([
                `FP${String(contador).padStart(2, '0')}`,
                mod.nombre_modulo || '-',
                clave,
                `${horas}h`
            ]);
            contador++;
        }
        
        doc.autoTable({
            startY: y,
            head: [['CÓDIGO', 'MÓDULO A IMPARTIR', 'CLAVE MÓDULO (*)', 'HORAS / SEM.']],
            body: tableBody,
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 4, valign: 'middle' },
            headStyles: { fillColor: COLOR_OSCURO, textColor: [255, 255, 255], fontStyle: 'bold', fontSize: 9 },
            alternateRowStyles: { fillColor: [250, 250, 250] },
            columnStyles: {
                0: { cellWidth: 25, halign: 'center' },
                1: { cellWidth: 80 },
                2: { cellWidth: 30, halign: 'center' },
                3: { cellWidth: 30, halign: 'center' }
            },
            margin: { left: marginX, right: marginX }
        });
        
        y = doc.lastAutoTable.finalY + 12;
        
        // ==================== OTROS CARGOS ====================
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(13);
        doc.setTextColor(...COLOR_OSCURO);
        doc.text('OTROS CARGOS O ACTIVIDADES LECTIVAS', marginX, y);
        y += 3;
        doc.setDrawColor(...COLOR_NARANJA);
        doc.line(marginX, y, pageWidth - marginX, y);
        y += 6;
        
        // Líneas para otros cargos
        for (let i = 0; i < 4; i++) {
            doc.setDrawColor(200, 200, 200);
            doc.setLineWidth(0.2);
            doc.line(marginX, y + 5, pageWidth - marginX, y + 5);
            y += 8;
        }
        y += 8;
        
        // ==================== TOTAL HORAS ====================
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.setTextColor(...COLOR_OSCURO);
        doc.setFillColor(245, 245, 245);
        
        const totalY = y;
        doc.rect(marginX, totalY, 130, 12, 'FD');
        doc.text('Nº TOTAL DE HORAS LECTIVAS (docencia, cargos y otras actividades)', marginX + 3, totalY + 8);
        
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(11);
        doc.setTextColor(...COLOR_NARANJA);
        doc.rect(marginX + 130, totalY, pageWidth - marginX * 2 - 130, 12, 'FD');
        doc.text(`${horasTotales} horas`, marginX + 135, totalY + 8);
        y = totalY + 16;
        
        // ==================== FIRMAS ====================
        const pageHeight = doc.internal.pageSize.getHeight();
        let yFooter = pageHeight - 28;
        
        doc.setDrawColor(180, 180, 180);
        doc.setLineWidth(0.3);
        doc.line(marginX, yFooter, pageWidth - marginX, yFooter);
        yFooter += 6;
        
        doc.setFont('helvetica', '');
        doc.setFontSize(9);
        doc.setTextColor(0, 0, 0);
        doc.text('Firma del Jefe/a de Departamento', marginX + 55, yFooter, { align: 'center' });
        doc.text('Firma del Profesor/a', pageWidth - marginX - 45, yFooter, { align: 'center' });
        yFooter += 8;
        
        doc.line(marginX + 20, yFooter, marginX + 90, yFooter);
        doc.line(pageWidth - marginX - 90, yFooter, pageWidth - marginX - 20, yFooter);
        yFooter += 8;
        
        doc.setFont('helvetica', 'italic');
        doc.setFontSize(7);
        doc.setTextColor(...COLOR_GRIS);
        doc.text('(*) Es indispensable rellenar con las claves recogidas en el documento entregado al Jefe/a de Departamento por Jefatura de Estudios.', marginX, yFooter);
        
        // ==================== GUARDAR ====================
        const nombreArchivo = `Asignacion_Modulos_${PROFESOR_NOMBRE.replace(/\s+/g, '_')}_${fechaHoy.replace(/\//g, '-')}.pdf`;
        doc.save(nombreArchivo);
        
    } catch(err) {
        console.error('Error:', err);
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

