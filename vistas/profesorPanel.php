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
    --accent:  #e35f1f;
    --dark:    #1a1a2e;
    --light:   #f4f1eb;
    --white:   #ffffff;
    --muted:   #777;
    --border:  #e2dfd8;
    --radius:  12px;
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

  .navbar-brand {
    font-family: 'DM Serif Display', serif;
    color: #fff;
    font-size: 1.1rem;
  }
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
  }
  .btn-ghost { background: rgba(255,255,255,.08); color: #fff; }
  .btn-ghost:hover { background: rgba(255,255,255,.15); }

  .page { max-width: 900px; margin: 0 auto; padding: 40px 24px; }

  .welcome {
    margin-bottom: 32px;
  }

  .welcome h1 {
    font-family: 'DM Serif Display', serif;
    font-size: 2rem;
    color: var(--dark);
  }

  .welcome p {
    color: var(--muted);
    margin-top: 6px;
  }

  /* Resumen de horas */
  .horas-card {
    background: var(--dark);
    color: #fff;
    border-radius: var(--radius);
    padding: 24px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
  }

  .horas-label { font-size: .85rem; color: #aaa; text-transform: uppercase; letter-spacing: .05em; }
  .horas-num   { font-family: 'DM Serif Display', serif; font-size: 3rem; color: #f7841a; line-height: 1; }
  .horas-desc  { font-size: .875rem; color: #bbb; margin-top: 4px; }

  .horas-bar-wrap { flex: 1; min-width: 200px; max-width: 320px; }
  .horas-bar-bg   { height: 8px; background: rgba(255,255,255,.12); border-radius: 4px; overflow: hidden; }
  .horas-bar-fill { height: 100%; background: #f7841a; border-radius: 4px; transition: width .6s ease; }

  /* Tabla */
  .card {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
  }

  .card-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    font-weight: 600;
    font-size: .95rem;
  }

  .empty-state {
    text-align: center;
    padding: 60px 24px;
    color: var(--muted);
  }

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

  .badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 600;
  }
  .badge-gray   { background: #f0f0f0; color: #555; }
  .badge-purple { background: #ede9fb; color: #6b3fa0; }

  /* ── Botón exportar PDF ── */
  .actions-bar {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
  }

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

  /* ── Estilos de impresión / exportación PDF ── */
  @media print {
    @page {
      size: A4;
      margin: 18mm 15mm 20mm 15mm;
    }

    body { background: #fff !important; }

    .navbar,
    .actions-bar,
    .btn,
    .btn-ghost { display: none !important; }

    .page {
      max-width: 100% !important;
      padding: 0 !important;
      margin: 0 !important;
    }

    /* Cabecera de impresión */
    .print-header {
      display: block !important;
      text-align: center;
      border-bottom: 2px solid #1a1a2e;
      padding-bottom: 12px;
      margin-bottom: 22px;
    }
    .print-header .ph-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #1a1a2e;
    }
    .print-header .ph-sub {
      font-size: .82rem;
      color: #555;
      margin-top: 3px;
    }

    .welcome h1   { font-size: 1.4rem; }
    .welcome p    { font-size: .85rem; }

    .horas-card {
      background: #1a1a2e !important;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
      page-break-inside: avoid;
    }

    .card {
      border: 1px solid #ccc !important;
      page-break-inside: avoid;
    }

    table { font-size: .82rem; }

    .badge-gray   { background: #eee !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .badge-purple { background: #ede9fb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

    /* Pie de impresión */
    .print-footer {
      display: block !important;
      text-align: center;
      margin-top: 28px;
      font-size: .75rem;
      color: #888;
      border-top: 1px solid #ddd;
      padding-top: 10px;
    }
  }

  /* Ocultos en pantalla, visibles al imprimir */
  .print-header,
  .print-footer { display: none; }
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

$totalHoras = array_sum(array_column($modulos, 'horas'));
$maxHoras = 20;
$porcentaje = min(100, round($totalHoras / $maxHoras * 100));
?>

<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <a href="/asignaciones/?vista=logout" class="btn btn-ghost">Cerrar sesión</a>
</nav>

<div class="page">

  <!-- Cabecera visible sólo al imprimir -->
  <div class="print-header">
    <div class="ph-title">Ciudad Escolar FP &mdash; Asignación de Módulos</div>
    <div class="ph-sub">Curso académico &middot; Documento generado el <?= date('d/m/Y \a \l\a\s H:i') ?></div>
  </div>

  <div class="welcome">
    <h1>Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Profesor') ?></h1>
    <p>Aquí tienes los módulos que tienes asignados este curso.</p>
  </div>

  <!-- Botón exportar PDF -->
  <?php if (!empty($modulos)): ?>
  <div class="actions-bar">
    <button class="btn-pdf" onclick="exportarPDF()">
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
      <div class="horas-num"><?= $totalHoras ?>h</div>
      <div class="horas-desc"><?= count($modulos) ?> módulo<?= count($modulos) !== 1 ? 's' : '' ?></div>
    </div>
    <div class="horas-bar-wrap">
      <div style="font-size:.8rem;color:#aaa;margin-bottom:8px">
        <?= $totalHoras ?> / <?= $maxHoras ?> horas (<?= $porcentaje ?>%)
        <?php if ($totalHoras > $maxHoras): ?><span style="color:#f97316"> ⚠️ Supera el máximo</span><?php endif; ?>
      </div>
      <div class="horas-bar-bg">
        <div class="horas-bar-fill" style="width:<?= $porcentaje ?>%;background:<?= $totalHoras > $maxHoras ? '#e05252' : '#f7841a' ?>"></div>
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

  <!-- Pie visible sólo al imprimir -->
  <div class="print-footer">
    Profesor: <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?> &nbsp;&middot;&nbsp;
    Total de horas asignadas: <?= $totalHoras ?>h &nbsp;&middot;&nbsp;
    <?= count($modulos) ?> módulo<?= count($modulos) !== 1 ? 's' : '' ?>
  </div>

</div>

<script>
function exportarPDF() {
  const nombreProfesor = <?= json_encode($_SESSION['nombre'] ?? 'Profesor') ?>;
  const fecha = new Date().toLocaleDateString('es-ES').replace(/\//g, '-');
  document.title = 'Modulos_' + nombreProfesor.replace(/\s+/g, '_') + '_' + fecha;
  window.print();
  setTimeout(() => { document.title = 'Mis módulos — Asignaciones FP'; }, 1000);
}
</script>

</body>
</html>
