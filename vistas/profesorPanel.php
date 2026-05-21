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
    <button class="btn btn-ghost" onclick="abrirModalPassword()" title="Establecer o cambiar contraseña" style="display:flex;align-items:center;gap:6px;">
      🔑 <?php
        require_once __DIR__ . '/../modelos/Modelo_usuarios.php';
        $__modeloU = new Modelo_usuarios();
        echo $__modeloU->profesorTienePassword((int)$_SESSION['profesor_id']) ? 'Cambiar contraseña' : 'Añadir contraseña';
      ?>
    </button>
    <a href="/asignaciones/?vista=logout" class="btn btn-ghost">Cerrar sesión</a>
</nav>

<!-- ── Modal contraseña profesor ─────────────────────────────────────────── -->
<div id="modalPassword" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;">
  <div style="background:var(--surface,#fff);border-radius:16px;padding:36px 32px;width:100%;max-width:420px;box-shadow:0 8px 40px rgba(0,0,0,.18);position:relative;">
    <button onclick="cerrarModalPassword()" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:1.4rem;cursor:pointer;color:var(--muted);" title="Cerrar">✕</button>
    <h2 style="margin:0 0 6px;font-size:1.2rem;">
      <?php echo $__modeloU->profesorTienePassword((int)$_SESSION['profesor_id']) ? '🔑 Cambiar contraseña' : '🔑 Añadir contraseña'; ?>
    </h2>
    <p style="color:var(--muted);font-size:.88rem;margin:0 0 24px;">
      <?php if ($__modeloU->profesorTienePassword((int)$_SESSION['profesor_id'])): ?>
        Introduce tu contraseña actual y después la nueva.
      <?php else: ?>
        Establece una contraseña para proteger tu acceso. A partir de ahora se te pedirá al iniciar sesión.
      <?php endif; ?>
    </p>

    <?php if (isset($_SESSION['error_pass'])): ?>
      <div style="background:#fee;border:1px solid #fcc;border-radius:8px;padding:10px 14px;margin-bottom:16px;color:#c00;font-size:.88rem;">
        <?= htmlspecialchars($_SESSION['error_pass']) ?></div>
      <?php unset($_SESSION['error_pass']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje'])): ?>
      <div style="background:#efffef;border:1px solid #bde;border-radius:8px;padding:10px 14px;margin-bottom:16px;color:#1a6b45;font-size:.88rem;">
        <?= htmlspecialchars($_SESSION['mensaje']) ?></div>
      <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <form method="POST" action="/asignaciones/controladores/Controlador_setPasswordProfesor.php">

      <?php if ($__modeloU->profesorTienePassword((int)$_SESSION['profesor_id'])): ?>
      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:6px;">Contraseña actual</label>
        <input type="password" name="password_actual" placeholder="••••••" required
          style="width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:.9rem;box-sizing:border-box;">
      </div>
      <?php endif; ?>

      <div style="margin-bottom:16px;">
        <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:6px;">Nueva contraseña</label>
        <input type="password" name="password_nueva" id="pwNueva" placeholder="Mínimo 4 caracteres" required
          style="width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:.9rem;box-sizing:border-box;">
      </div>

      <div style="margin-bottom:24px;">
        <label style="display:block;font-size:.85rem;font-weight:600;margin-bottom:6px;">Repetir contraseña</label>
        <input type="password" name="password_repetir" id="pwRepetir" placeholder="Repite la nueva contraseña" required
          style="width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:.9rem;box-sizing:border-box;"
          oninput="validarCoincidencia()">
        <small id="pwMatch" style="font-size:.78rem;margin-top:4px;display:block;"></small>
      </div>

      <button type="submit"
        style="width:100%;padding:11px;background:var(--accent,#6c63ff);color:#fff;border:none;border-radius:10px;font-family:inherit;font-size:.95rem;font-weight:600;cursor:pointer;">
        Guardar contraseña
      </button>
    </form>
  </div>
</div>

<script>
function abrirModalPassword() {
  document.getElementById('modalPassword').style.display = 'flex';
}
function cerrarModalPassword() {
  document.getElementById('modalPassword').style.display = 'none';
}
// Cerrar al hacer clic fuera del cuadro
document.getElementById('modalPassword').addEventListener('click', function(e) {
  if (e.target === this) cerrarModalPassword();
});
function validarCoincidencia() {
  const a = document.getElementById('pwNueva').value;
  const b = document.getElementById('pwRepetir').value;
  const el = document.getElementById('pwMatch');
  if (!b) { el.textContent = ''; return; }
  if (a === b) { el.textContent = '✅ Coinciden'; el.style.color = '#1a6b45'; }
  else         { el.textContent = '❌ No coinciden'; el.style.color = '#c00'; }
}
<?php if (isset($_SESSION['_open_modal_password'])): unset($_SESSION['_open_modal_password']); ?>
document.addEventListener('DOMContentLoaded', () => abrirModalPassword());
<?php endif; ?>
</script>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
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

// ── Helpers ───────────────────────────────────────────────────────────
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

// ── PDF principal — rellena la plantilla_oficial.pdf original ─────────
async function exportarPDF() {
  const btn = document.getElementById('btnExportarPDF');
  btn.classList.add('loading'); btn.textContent = 'Generando PDF…';

  try {
    const { PDFDocument, rgb, StandardFonts } = PDFLib;

    // ── 1. Cargar la plantilla original del servidor ──────────────────
    const plantillaUrl = '/asignaciones/Recursos/plantilla_oficial.pdf';
    const plantillaBytes = await fetch(plantillaUrl).then(r => {
      if (!r.ok) throw new Error(`No se pudo cargar la plantilla: ${r.status} ${r.statusText}`);
      return r.arrayBuffer();
    });

    const pdfDoc = await PDFDocument.load(plantillaBytes);
    const page   = pdfDoc.getPages()[0];
    const { width: W, height: H } = page.getSize(); // 595.2 x 841.92

    const fontNormal = await pdfDoc.embedFont(StandardFonts.Helvetica);
    const fontBold   = await pdfDoc.embedFont(StandardFonts.HelveticaBold);

    // ── Helpers ───────────────────────────────────────────────────────
    const PADDING = 4;

    // Trunca el texto para que no supere el ancho máximo de la celda
    function truncar(txt, font, size, maxW) {
      txt = String(txt || '');
      while (txt.length > 1 && font.widthOfTextAtSize(txt, size) > maxW) {
        txt = txt.slice(0, -2) + '…';
      }
      return txt;
    }

    // Dibuja texto alineado a la izquierda dentro de una celda
    function drawLeft(txt, x0, x1, yTop, size, font) {
      font = font || fontNormal;
      const maxW = (x1 - x0) - PADDING * 2;
      txt = truncar(txt, font, size, maxW);
      page.drawText(txt, { x: x0 + PADDING, y: H - yTop, size, font, color: rgb(0,0,0) });
    }

    // Dibuja texto centrado dentro de una celda
    function drawCenter(txt, x0, x1, yTop, size, font) {
      font = font || fontNormal;
      const maxW = (x1 - x0) - PADDING * 2;
      txt = truncar(txt, font, size, maxW);
      const tw = font.widthOfTextAtSize(txt, size);
      const cx = (x0 + x1) / 2;
      page.drawText(txt, { x: cx - tw / 2, y: H - yTop, size, font, color: rgb(0,0,0) });
    }

    // ── 2. Separar módulos / otros cargos ────────────────────────────
    const modulos     = MODULOS_DATA.filter(m => !esOtroCargo(m.nombre_modulo));
    const otrosCargos = MODULOS_DATA.filter(m =>  esOtroCargo(m.nombre_modulo));
    const fechaHoy    = new Date().toLocaleDateString('es-ES');

    // ── 3. Cabecera ───────────────────────────────────────────────────
    // Coordenadas medidas con pdfplumber sobre la plantilla real
    drawLeft(PROFESOR_ESPECIALIDAD || 'TECNOLOGÍA', 157, 540, 152, 9.5);
    drawLeft(PROFESOR_NOMBRE,                        127, 540, 176, 9.5);
    drawLeft(fechaHoy,                               112, 540, 200, 9.5);

    // ── 4. Tabla DOCENCIA ─────────────────────────────────────────────
    // Bordes exactos de columnas medidos con pdfplumber:
    //   Código:  x0=58.59   x1=175.51
    //   Módulo:  x0=175.51  x1=400.70
    //   Clave:   x0=400.70  x1=499.70
    //   Horas:   x0=499.70  x1=580.70
    // Baseline Y de cada fila (centro celda + ajuste baseline fuente):
    const COD_X0 = 58.59,  COD_X1 = 175.51;
    const MOD_X0 = 175.51, MOD_X1 = 400.70;
    const CLA_X0 = 400.70, CLA_X1 = 499.70;
    const HOR_X0 = 499.70, HOR_X1 = 580.70;
    const FS = 8.5;

    const ROWS_DOC_Y = [296.4, 321.4, 346.3, 371.2, 396.0, 421.0];

    modulos.slice(0, 6).forEach((m, i) => {
      const y     = ROWS_DOC_Y[i];
      const cod   = String(m.codigo_ciclo || m.categoria || '');
      const nom   = String(m.nombre_modulo || '');
      const clave = getClave(m);
      const horas = String(parseInt(m.horas) || 0);

      drawCenter(cod,   COD_X0, COD_X1, y, FS);
      drawLeft  (nom,   MOD_X0, MOD_X1, y, FS);
      drawCenter(clave, CLA_X0, CLA_X1, y, FS);
      drawCenter(horas, HOR_X0, HOR_X1, y, FS);
    });

    // ── 5. Tabla OTROS CARGOS ─────────────────────────────────────────
    // Bordes exactos:
    //   Descripción: x0=58.59  x1=499.70
    //   Horas:       x0=499.70 x1=580.70  (misma col que docencia)
    const OTR_X0 = 58.59, OTR_X1 = 499.70;
    const ROWS_OTR_Y = [495.3, 520.1, 545.1];

    otrosCargos.slice(0, 3).forEach((m, i) => {
      const y     = ROWS_OTR_Y[i];
      const desc  = String(m.nombre_modulo || '');
      const horas = String(parseInt(m.horas) || 0);

      drawLeft  (desc,  OTR_X0, OTR_X1, y, FS);
      drawCenter(horas, HOR_X0, HOR_X1, y, FS);
    });

    // ── 6. Total de horas ─────────────────────────────────────────────
    // Celda total: x0=499.70 x1=580.70, baseline y=588.9
    const totalFinal = MODULOS_DATA.reduce((s, m) => s + (parseInt(m.horas) || 0), 0);
    drawCenter(String(totalFinal), HOR_X0, HOR_X1, 588.9, 9.5, fontBold);

    // ── 7. Guardar y descargar ────────────────────────────────────────
    const pdfBytes = await pdfDoc.save();
    const blob     = new Blob([pdfBytes], { type: 'application/pdf' });
    const url      = URL.createObjectURL(blob);
    const a        = document.createElement('a');
    a.href         = url;
    a.download     = `Asignacion_${PROFESOR_NOMBRE.replace(/\s+/g,'_')}_${fechaHoy.replace(/\//g,'-')}.pdf`;
    a.click();
    setTimeout(() => URL.revokeObjectURL(url), 5000);

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