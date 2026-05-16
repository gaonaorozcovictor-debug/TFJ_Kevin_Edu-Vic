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

<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

<script>
const PROFESOR_ID = <?= json_encode((int)($_SESSION['profesor_id'] ?? 0)) ?>;
const PROFESOR_NOMBRE = <?= json_encode($nombreProfesor) ?>;
const PROFESOR_ESPECIALIDAD = <?= json_encode($especialidadProfesor) ?>;
const TOTAL_HORAS = <?= (int)$totalHoras ?>;
const MODULOS_DATA = <?= json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) ?>;
const STORAGE_KEY = 'horasConfigGlobal';

function cargarLimites() {
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (!stored) return null;
        const cfg = JSON.parse(stored);
        // El admin guarda {min, objetivo, max} directamente — misma clave para todos
        if (typeof cfg.min !== 'undefined' && typeof cfg.objetivo !== 'undefined' && typeof cfg.max !== 'undefined') {
            return cfg;
        }
        return null;
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

// ============================================================
// EXPORTAR PDF — usa la plantilla oficial del IES Ciudad Escolar
// Carga el PDF original y superpone el texto del profesor encima.
// ============================================================
async function exportarPDF() {
    const btn = document.getElementById('btnExportarPDF');
    btn.classList.add('loading');
    btn.textContent = 'Generando PDF…';

    try {
        // 1. Obtener la plantilla oficial desde el servidor
        const resp = await fetch('/asignaciones/?vista=plantillaPDF');
        if (!resp.ok) throw new Error('No se pudo cargar la plantilla PDF');
        const json = await resp.json();
        if (!json.ok) throw new Error(json.mensaje || 'Error al obtener la plantilla');

        // 2. Cargar la plantilla con pdf-lib
        const { PDFDocument, rgb, StandardFonts } = PDFLib;
        const pdfBytes   = Uint8Array.from(atob(json.base64), c => c.charCodeAt(0));
        const pdfDoc     = await PDFDocument.load(pdfBytes);
        const page       = pdfDoc.getPages()[0];

        // Fuentes estándar (Helvetica admite caracteres básicos + acentos con encoding latin)
        const fontBold   = await pdfDoc.embedFont(StandardFonts.HelveticaBold);
        const fontNormal = await pdfDoc.embedFont(StandardFonts.Helvetica);

        const pageHeight = page.getHeight(); // 841.92 pt

        // Helper: convierte coordenadas desde la parte superior (como en CSS/diseño)
        // a coordenadas pdf-lib (y=0 en la parte inferior).
        const top = (yFromTop) => pageHeight - yFromTop;

        // ── Función para escribir texto con truncado automático ──
        const writeText = (text, x, yFromTop, font, size, color = rgb(0, 0, 0), maxWidth = null) => {
            if (!text) return;
            let str = String(text);
            if (maxWidth && font.widthOfTextAtSize(str, size) > maxWidth) {
                // Truncar con elipsis
                while (str.length > 1 && font.widthOfTextAtSize(str + '…', size) > maxWidth) {
                    str = str.slice(0, -1);
                }
                str += '…';
            }
            page.drawText(str, {
                x,
                y: top(yFromTop),
                size,
                font,
                color,
            });
        };

        // ============================================================
        // 3. DATOS DEL PROFESOR
        // Posiciones medidas sobre la plantilla (pdfplumber top-origin)
        // DEPARTAMENTO: label en y≈147.8  → valor empieza a x≈163
        // PROFESOR:     label en y≈172.2  → valor empieza a x≈133
        // FECHA:        label en y≈196.7  → valor empieza a x≈113
        // ============================================================
        const fechaHoy = new Date().toLocaleDateString('es-ES');

        writeText(PROFESOR_ESPECIALIDAD || 'TECNOLOGÍA', 163, 151, fontNormal, 10, rgb(0,0,0), 380);
        writeText(PROFESOR_NOMBRE,                        133, 175, fontNormal, 10, rgb(0,0,0), 410);
        writeText(fechaHoy,                               113, 199, fontNormal, 10, rgb(0,0,0), 200);

        // ============================================================
        // 4. TABLA DOCENCIA
        // La plantilla tiene 6 filas de ~24.5pt de alto cada una.
        // Primera fila empieza en y≈287.1 (top de la fila de encabezado tabla)
        // Filas de datos: tops aproximados a 312.6, 337.5, 362.3, 387.2...
        // Columnas (x desde izquierda, basado en los rects del PDF):
        //   CÓDIGO/CICLO : x=58.8 → 175.3   (texto a x≈63, centrado)
        //   MÓDULO       : x=175.8 → 400.5  (texto a x≈180)
        //   CLAVE        : x=400.9 → 499.4  (texto centrado ~450)
        //   HORAS/SEM    : x=499.9 → 580.5  (texto centrado ~540)
        // ============================================================

        // Filas de la tabla docencia — tops medidos desde arriba (pdfplumber)
        const filasTops = [312.6, 337.5, 362.3, 387.2, 411.7, 436.7];
        // Altura de fila ≈24.5pt; texto centrado verticalmente → +14pt del top
        const ROW_TEXT_OFFSET = 15;

        let horasTotales = 0;

        MODULOS_DATA.forEach((mod, i) => {
            if (i >= filasTops.length) return; // La plantilla solo tiene 6 filas
            const yTop = filasTops[i] + ROW_TEXT_OFFSET;

            const horas = parseInt(mod.horas) || 0;
            horasTotales += horas;

            // Columna 1 — Código del ciclo formativo
            const codigoCiclo = mod.grado || '-';
            writeText(codigoCiclo, 63, yTop, fontNormal, 8, rgb(0,0,0), 108);

            // Columna 2 — Nombre del módulo
            writeText(mod.nombre_modulo || '-', 180, yTop, fontNormal, 8, rgb(0,0,0), 216);

            // Columna 3 — Clave del módulo
            const nombreUpper = (mod.nombre_modulo || '').toUpperCase();
            const catUpper    = (mod.categoria     || '').toUpperCase();
            let clave = mod.clave_modulo || '';
            if (!clave) {
                if (nombreUpper.includes('PS'))      clave = 'PS';
                else if (nombreUpper.includes('PT')) clave = 'PT';
                else if (catUpper === 'SAI')         clave = 'SAI';
                else if (catUpper === 'INF')         clave = 'INF';
            }
            // Centrar en columna 3 (x: 400.9–499.4, ancho ~98.5)
            const claveWidth = clave ? fontNormal.widthOfTextAtSize(clave, 8) : 0;
            const claveX     = 400.9 + (98.5 - claveWidth) / 2;
            writeText(clave, claveX, yTop, fontNormal, 8, rgb(0,0,0));

            // Columna 4 — Horas / sem
            // Centrar en columna 4 (x: 499.9–580.5, ancho ~80.6)
            const horasStr   = String(horas);
            const horasWidth = fontBold.widthOfTextAtSize(horasStr, 9);
            const horasX     = 499.9 + (80.6 - horasWidth) / 2;
            writeText(horasStr, horasX, yTop, fontBold, 9, rgb(0,0,0));
        });

        // ============================================================
        // 5. TOTAL HORAS LECTIVAS
        // El recuadro del total en la plantilla:
        //   Caja izquierda (etiqueta): x=58.8–499.4, y=561.3–585.8 (top-origin)
        //   Caja derecha  (valor)    : x=499.9–580.5, y=561.3–585.8
        // Centro vertical: 573.5 → texto a 573.5 + 3 = 576.5 → pero con offset -14 del top
        // ============================================================
        const totalStr   = String(horasTotales);
        const totalWidth = fontBold.widthOfTextAtSize(totalStr, 11);
        const totalX     = 499.9 + (80.6 - totalWidth) / 2;
        writeText(totalStr, totalX, 576, fontBold, 11, rgb(0,0,0));

        // ============================================================
        // 6. Guardar y descargar
        // ============================================================
        const pdfBytesOut = await pdfDoc.save();
        const blob        = new Blob([pdfBytesOut], { type: 'application/pdf' });
        const url         = URL.createObjectURL(blob);
        const a           = document.createElement('a');
        a.href            = url;
        a.download        = `Asignacion_Modulos_${PROFESOR_NOMBRE.replace(/\s+/g, '_')}_${fechaHoy.replace(/\//g, '-')}.pdf`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

    } catch(err) {
        console.error('Error generando PDF:', err);
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

