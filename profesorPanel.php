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
<!-- darkmode.js: aplica el tema guardado en localStorage antes de pintar la página -->
<script src="/asignaciones/vistas/estilos/darkmode.js"></script>
</head>
<body>

<?php
// Arranca la sesión si no está iniciada ya
if (session_status() === PHP_SESSION_NONE) session_start();
// Solo los usuarios con rol 'profesor' pueden ver esta página
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') { header('Location: /asignaciones/'); exit(); }
// Si el modelo no pasó módulos (ej. primer acceso), se usa un array vacío
if (!isset($modulos)) $modulos = [];
// Calcula el total de horas sumando la columna 'horas' de todos los módulos asignados
$totalHoras = array_sum(array_column($modulos, 'horas'));
$nombreProfesor     = $_SESSION['nombre']    ?? 'Profesor';
$especialidadProfesor = $_SESSION['categoria'] ?? '';
?>

<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <!-- Botón que llama a toggleDarkMode() de darkmode.js -->
  <button class="btn btn-ghost btn-darkmode" onclick="toggleDarkMode()" title="Cambiar a modo oscuro">🌙 Modo oscuro</button>

  <?php
    // Carga el modelo de usuarios para comprobar si este profesor ya tiene contraseña
    require_once __DIR__ . '/../modelos/Modelo_usuarios.php';
    $__modeloU = new Modelo_usuarios();
  ?>
  <!-- El texto del botón cambia según si el profesor ya tiene contraseña o no -->
  <button class="btn btn-ghost" onclick="abrirModalPassword()" title="Establecer o cambiar contraseña" style="display:flex;align-items:center;gap:6px;">
    🔑 <?php echo $__modeloU->profesorTienePassword((int)$_SESSION['profesor_id']) ? 'Cambiar contraseña' : 'Añadir contraseña'; ?>
  </button>
  <a href="/asignaciones/?vista=logout" class="btn btn-ghost">Cerrar sesión</a>
</nav>

<!-- ── Modal de contraseña del profesor ──────────────────────────────────
     Permite al profesor establecer o cambiar su contraseña de acceso.
     Si ya tiene contraseña, pide la actual antes de aceptar la nueva. -->
<div id="modalPassword" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;">
  <div style="background:var(--surface,#fff);border-radius:16px;padding:36px 32px;width:100%;max-width:420px;box-shadow:0 8px 40px rgba(0,0,0,.18);position:relative;">
    <button onclick="cerrarModalPassword()" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:1.4rem;cursor:pointer;color:var(--muted);" title="Cerrar">✕</button>
    <h2 style="margin:0 0 6px;font-size:1.2rem;">
      <!-- Título dinámico según si ya tiene contraseña -->
      <?php echo $__modeloU->profesorTienePassword((int)$_SESSION['profesor_id']) ? '🔑 Cambiar contraseña' : '🔑 Añadir contraseña'; ?>
    </h2>
    <p style="color:var(--muted);font-size:.88rem;margin:0 0 24px;">
      <?php if ($__modeloU->profesorTienePassword((int)$_SESSION['profesor_id'])): ?>
        Introduce tu contraseña actual y después la nueva.
      <?php else: ?>
        Establece una contraseña para proteger tu acceso. A partir de ahora se te pedirá al iniciar sesión.
      <?php endif; ?>
    </p>

    <!-- Mensajes de error o éxito del controlador de contraseña, pasados por sesión -->
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
      <!-- Campo "contraseña actual" solo se muestra si el profesor ya tiene una establecida -->
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
        <!-- oninput llama a validarCoincidencia() para dar feedback en tiempo real -->
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
// Muestra el modal de contraseña
function abrirModalPassword() {
  document.getElementById('modalPassword').style.display = 'flex';
}
// Oculta el modal de contraseña
function cerrarModalPassword() {
  document.getElementById('modalPassword').style.display = 'none';
}
// Cierra el modal al hacer clic en el fondo oscuro fuera del cuadro
document.getElementById('modalPassword').addEventListener('click', function(e) {
  if (e.target === this) cerrarModalPassword();
});
// Comprueba en tiempo real si los dos campos de contraseña coinciden y muestra feedback visual
function validarCoincidencia() {
  const a  = document.getElementById('pwNueva').value;
  const b  = document.getElementById('pwRepetir').value;
  const el = document.getElementById('pwMatch');
  if (!b) { el.textContent = ''; return; }
  if (a === b) { el.textContent = '✅ Coinciden';    el.style.color = '#1a6b45'; }
  else         { el.textContent = '❌ No coinciden'; el.style.color = '#c00'; }
}
// Si el controlador dejó la bandera de sesión, abre el modal automáticamente al cargar
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
    <!-- Al hacer clic llama a exportarPDF() que genera el PDF con pdf-lib en el navegador -->
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

  <!-- Leyenda que explica el significado de cada color en el indicador de horas -->
  <div class="leyenda-colores">
    <span style="font-weight:600;color:var(--muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;align-self:center;">Estado:</span>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--ok)"></span><span style="color:var(--ok)">Óptimo (objetivo exacto)</span></div>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--range)"></span><span style="color:var(--range)">En rango (min–max)</span></div>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--warn)"></span><span style="color:var(--warn)">Cercano al límite</span></div>
    <div class="leyenda-item"><span class="leyenda-dot" style="background:var(--bad)"></span><span style="color:var(--bad)">Fuera de rango</span></div>
  </div>

  <!-- Tarjeta de horas: número total en color dinámico + barra de progreso hacia el objetivo -->
  <div class="horas-card">
    <div>
      <div class="horas-label">Horas semanales asignadas</div>
      <!-- PHP imprime el total inicial; JS lo colorea según los umbrales de localStorage -->
      <div class="horas-num" id="horasNum" style="color:var(--accent2)"><?= $totalHoras ?>h</div>
      <div class="horas-desc"><?= count($modulos) ?> módulo<?= count($modulos) !== 1 ? 's' : '' ?></div>
      <!-- Texto de aviso (ej. "✅ Objetivo exacto") inyectado por JS al inicializar -->
      <div class="estado-aviso" id="estadoAviso"></div>
    </div>
    <div class="horas-bar-wrap">
      <div style="font-size:.8rem;color:#aaa;margin-bottom:8px" id="horasBarLabel"><?= $totalHoras ?>h asignadas</div>
      <!-- Barra de progreso: el ancho y color los fija JS según el porcentaje respecto al objetivo -->
      <div class="horas-bar-bg"><div class="horas-bar-fill" id="horasBarFill" style="width:0%"></div></div>
    </div>
  </div>

  <div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">
      <span>📋 Módulos asignados</span>
      <?php if (!empty($modulos)): ?>
      <!-- Buscador en tiempo real: filtra las filas de la tabla sin recargar la página -->
      <input type="text" id="buscadorModulos" placeholder="Buscar módulo..." oninput="filtrarTabla()"
        style="padding:7px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:.85rem;outline:none;width:220px;">
      <?php endif; ?>
    </div>
    <?php if (empty($modulos)): ?>
      <div class="empty-state"><div class="icon">📭</div><p>No tienes módulos asignados todavía.</p></div>
    <?php else: ?>
      <!-- PHP genera la tabla con los módulos; JS solo la filtra, no la modifica -->
      <table id="tablaModulos">
        <thead><tr><th>Ciclo</th><th>Módulo</th><th>Horas</th><th>Categoría</th></tr></thead>
        <tbody>
          <?php foreach ($modulos as $m): ?>
          <tr>
            <td><span class="badge badge-gray"><?= htmlspecialchars($m['grado'] ?? '-') ?></span></td>
            <td><?= htmlspecialchars($m['nombre_modulo']) ?>
              <?php if (stripos($m['nombre_modulo'],'PS')!==false||stripos($m['nombre_modulo'],'PT')!==false): ?>
                <span class="badge badge-purple" style="margin-left:6px">PS/PT</span>
              <?php endif; ?>
            </td>
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

<!-- pdf-lib: librería JS que permite abrir un PDF existente y escribir encima sin modificar el original -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
<script>
// PHP inyecta los datos del profesor como constantes JS para que el generador de PDF los use
const PROFESOR_NOMBRE       = <?= json_encode($nombreProfesor) ?>;
const PROFESOR_ESPECIALIDAD = <?= json_encode($especialidadProfesor) ?>;
const TOTAL_HORAS           = <?= (int)$totalHoras ?>;
const MODULOS_DATA          = <?= json_encode(array_values($modulos), JSON_UNESCAPED_UNICODE) ?>;
const STORAGE_KEY           = 'horasConfigGlobal'; // Misma clave que usa asignarModulos.php

// ── Sistema de colores ──────────────────────────────────────────────
// Lee los umbrales guardados por el administrador en localStorage
// Si no existen, usa los valores por defecto (18 / 20 / 22)
function getCfg() {
  try {
    const s = localStorage.getItem(STORAGE_KEY);
    if (s) return { min:18, objetivo:20, max:22, ...JSON.parse(s) };
  } catch(e) {}
  return { min:18, objetivo:20, max:22 };
}

// Devuelve el color CSS según las horas y los umbrales configurados
function calcularColor(h, cfg) {
  if (h === cfg.objetivo)                       return '#1a6b45'; // verde oscuro — objetivo exacto
  if (h >= cfg.min && h <= cfg.max)             return '#2cb67d'; // verde claro  — en rango
  if (h === cfg.min - 1 || h === cfg.max + 1)   return '#f7841a'; // naranja       — en el límite
  return '#e05252'; // rojo — fuera de rango
}

// Devuelve el texto de aviso que se muestra bajo el número de horas
function calcularAviso(h, cfg) {
  if (h === cfg.objetivo)                         return '✅ Objetivo exacto';
  if (h > cfg.min && h < cfg.objetivo)            return `⬆ Faltan ${cfg.objetivo - h}h para el objetivo`;
  if (h >= cfg.min && h < cfg.objetivo)           return `⬆ En rango mínimo`;
  if (h > cfg.objetivo && h <= cfg.max)           return `✅ En rango (+${h - cfg.objetivo}h)`;
  if (h < cfg.min)                                return `🔴 Por debajo del mínimo (${cfg.min}h)`;
  return `⚠️ Supera el máximo (${cfg.max}h)`;
}

// Colorea el número de horas, rellena la barra de progreso y muestra el texto de aviso
function inicializarUI() {
  const cfg   = getCfg();
  const color = calcularColor(TOTAL_HORAS, cfg);
  const aviso = calcularAviso(TOTAL_HORAS, cfg);
  // Porcentaje respecto al objetivo, máximo 100%
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

// Filtra las filas de la tabla en tiempo real según el texto del buscador
// Muestra un mensaje "Sin resultados" si no hay filas visibles
function filtrarTabla() {
  const busq   = document.getElementById('buscadorModulos')?.value.toLowerCase() || '';
  const filas  = document.querySelectorAll('#tablaModulos tbody tr');
  let visibles = 0;
  filas.forEach(fila => {
    const mostrar = fila.textContent.toLowerCase().includes(busq);
    fila.style.display = mostrar ? '' : 'none';
    if (mostrar) visibles++;
  });
  const sinRes = document.getElementById('sinResultados');
  if (sinRes) sinRes.style.display = visibles === 0 ? 'block' : 'none';
}

// ── Helpers del generador de PDF ───────────────────────────────────────
// Palabras clave que indican que un módulo pertenece a "Otros cargos" en la plantilla
const OTROS_CARGOS_KEYWORDS = ['tutoria','tutoría','tutor','guardia','coordinacion','coordinación','formacion en centros','fct','empresa'];

// Devuelve true si el nombre del módulo corresponde a un "Otro cargo" (tutoría, guardia, etc.)
function esOtroCargo(nombre) {
  const n = (nombre||'').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
  return OTROS_CARGOS_KEYWORDS.some(k => n.includes(k));
}

// Determina la clave de especialidad (PS / PT / SAI / INF) de un módulo
// para rellenar la columna "Clave" de la tabla DOCENCIA en el PDF
function getClave(m) {
  const n = (m.nombre_modulo||'').toUpperCase();
  const c = (m.categoria||'').toUpperCase();
  if (n.includes('PS') || c.includes('PS')) return 'PS';
  if (n.includes('PT') || c.includes('PT')) return 'PT';
  if (c === 'SAI') return 'SAI';
  if (c === 'INF') return 'INF';
  return '-';
}

// ── Generación del PDF sobre la plantilla oficial ──────────────────────
// 1. Descarga la plantilla PDF del servidor (no la modifica, solo la carga en memoria).
// 2. Escribe los datos del profesor encima con pdf-lib.
// 3. Descarga el archivo resultante en el navegador del usuario.
async function exportarPDF() {
  const btn = document.getElementById('btnExportarPDF');
  btn.classList.add('loading'); btn.textContent = 'Generando PDF…';

  try {
    const { PDFDocument, rgb, StandardFonts } = PDFLib;

    // ── 1. Descarga la plantilla oficial del servidor ──────────────────
    // La plantilla permanece intacta en el servidor; aquí solo se lee su contenido
    const plantillaUrl   = '/asignaciones/Recursos/plantilla_oficial.pdf';
    const plantillaBytes = await fetch(plantillaUrl).then(r => {
      if (!r.ok) throw new Error(`No se pudo cargar la plantilla: ${r.status} ${r.statusText}`);
      return r.arrayBuffer();
    });

    const pdfDoc = await PDFDocument.load(plantillaBytes);
    const page   = pdfDoc.getPages()[0];
    const { width: W, height: H } = page.getSize(); // 595.2 x 841.92 puntos (A4)

    const fontNormal = await pdfDoc.embedFont(StandardFonts.Helvetica);
    const fontBold   = await pdfDoc.embedFont(StandardFonts.HelveticaBold);

    const PADDING = 4; // Margen interior de cada celda (puntos)

    // Trunca el texto para que no salga de los bordes de la celda
    function truncar(txt, font, size, maxW) {
      txt = String(txt || '');
      while (txt.length > 1 && font.widthOfTextAtSize(txt, size) > maxW) {
        txt = txt.slice(0, -2) + '…';
      }
      return txt;
    }
    // Dibuja texto alineado a la izquierda dentro de [x0, x1] a la altura yTop
    function drawLeft(txt, x0, x1, yTop, size, font) {
      font = font || fontNormal;
      const maxW = (x1 - x0) - PADDING * 2;
      txt = truncar(txt, font, size, maxW);
      page.drawText(txt, { x: x0 + PADDING, y: H - yTop, size, font, color: rgb(0,0,0) });
    }
    // Dibuja texto centrado dentro de [x0, x1] a la altura yTop
    function drawCenter(txt, x0, x1, yTop, size, font) {
      font = font || fontNormal;
      const maxW = (x1 - x0) - PADDING * 2;
      txt = truncar(txt, font, size, maxW);
      const tw = font.widthOfTextAtSize(txt, size);
      const cx = (x0 + x1) / 2;
      page.drawText(txt, { x: cx - tw / 2, y: H - yTop, size, font, color: rgb(0,0,0) });
    }

    // ── 2. Separa módulos docentes de otros cargos (tutoría, guardia…) ──
    const modulos     = MODULOS_DATA.filter(m => !esOtroCargo(m.nombre_modulo));
    const otrosCargos = MODULOS_DATA.filter(m =>  esOtroCargo(m.nombre_modulo));
    const fechaHoy    = new Date().toLocaleDateString('es-ES');

    // ── 3. Rellena la cabecera de la plantilla (especialidad, nombre, fecha) ──
    // Coordenadas medidas con pdfplumber sobre la plantilla oficial
    drawLeft(PROFESOR_ESPECIALIDAD || 'TECNOLOGÍA', 157, 540, 152, 9.5);
    drawLeft(PROFESOR_NOMBRE,                        127, 540, 176, 9.5);
    drawLeft(fechaHoy,                               112, 540, 200, 9.5);

    // ── 4. Rellena la tabla DOCENCIA (máximo 6 filas) ────────────────────
    // Coordenadas x exactas de cada columna (medidas con pdfplumber)
    const COD_X0 = 58.59,  COD_X1 = 175.51; // Columna "Código ciclo"
    const MOD_X0 = 175.51, MOD_X1 = 400.70; // Columna "Módulo"
    const CLA_X0 = 400.70, CLA_X1 = 499.70; // Columna "Clave especialidad"
    const HOR_X0 = 499.70, HOR_X1 = 580.70; // Columna "Horas"
    const FS = 8.5; // Tamaño de fuente para las filas de la tabla

    // Y de la línea base de cada fila de la tabla DOCENCIA
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

    // ── 5. Rellena la tabla OTROS CARGOS (máximo 3 filas) ────────────────
    const OTR_X0 = 58.59, OTR_X1 = 499.70; // Columna "Descripción"
    const ROWS_OTR_Y = [495.3, 520.1, 545.1];

    otrosCargos.slice(0, 3).forEach((m, i) => {
      const y     = ROWS_OTR_Y[i];
      const desc  = String(m.nombre_modulo || '');
      const horas = String(parseInt(m.horas) || 0);
      drawLeft  (desc,  OTR_X0, OTR_X1, y, FS);
      drawCenter(horas, HOR_X0, HOR_X1, y, FS);
    });

    // ── 6. Rellena la celda TOTAL con el sumatorio de todas las horas ────
    const totalFinal = MODULOS_DATA.reduce((s, m) => s + (parseInt(m.horas) || 0), 0);
    drawCenter(String(totalFinal), HOR_X0, HOR_X1, 588.9, 9.5, fontBold);

    // ── 7. Serializa el PDF y lo descarga en el navegador ────────────────
    const pdfBytes = await pdfDoc.save();
    const blob     = new Blob([pdfBytes], { type: 'application/pdf' });
    const url      = URL.createObjectURL(blob);
    const a        = document.createElement('a');
    a.href         = url;
    a.download     = `Asignacion_${PROFESOR_NOMBRE.replace(/\s+/g,'_')}_${fechaHoy.replace(/\//g,'-')}.pdf`;
    a.click();
    // Libera la URL temporal del blob tras 5 segundos
    setTimeout(() => URL.revokeObjectURL(url), 5000);

  } catch(err) {
    console.error(err);
    alert('Error al generar el PDF: ' + err.message);
  } finally {
    // Restaura el botón al estado original independientemente de si hubo error o no
    btn.classList.remove('loading');
    btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg> Exportar PDF`;
  }
}

// Inicializa los colores y la barra de progreso al cargar la página
document.addEventListener('DOMContentLoaded', inicializarUI);
</script>
</body>
</html>
