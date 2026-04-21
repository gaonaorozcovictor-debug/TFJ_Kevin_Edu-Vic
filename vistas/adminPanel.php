<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Admin — Asignaciones FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --accent:  #e35f1f;
    --accent2: #f7841a;
    --dark:    #1a1a2e;
    --light:   #f4f1eb;
    --white:   #ffffff;
    --muted:   #777;
    --border:  #e2dfd8;
    --success: #2cb67d;
    --danger:  #e05252;
    --radius:  12px;
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--light);
    color: var(--dark);
    min-height: 100vh;
  }

  /* ── Navbar ─────────────────────────────────────────────── */
  .navbar {
    background: var(--dark);
    padding: 0 32px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
  }

  .navbar-brand {
    font-family: 'DM Serif Display', serif;
    color: var(--white);
    font-size: 1.1rem;
  }
  .navbar-brand span { color: var(--accent2); }

  .navbar-actions { display: flex; gap: 10px; align-items: center; }

  .nav-badge {
    font-size: .8rem;
    color: #aaa;
    padding: 0 12px;
  }

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

  .btn-primary { background: var(--accent); color: #fff; }
  .btn-primary:hover { background: #c9531a; }

  .btn-ghost { background: rgba(255,255,255,.08); color: #fff; }
  .btn-ghost:hover { background: rgba(255,255,255,.15); }

  .btn-danger { background: var(--danger); color: #fff; }
  .btn-danger:hover { background: #c94040; }

  /* ── Page layout ─────────────────────────────────────────── */
  .page { max-width: 1280px; margin: 0 auto; padding: 32px 24px; }

  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
  }

  .page-title {
    font-family: 'DM Serif Display', serif;
    font-size: 2rem;
    color: var(--dark);
  }

  .page-title small {
    display: block;
    font-family: 'DM Sans', sans-serif;
    font-size: .85rem;
    font-weight: 400;
    color: var(--muted);
    margin-top: 4px;
  }

  /* ── Alertas ─────────────────────────────────────────────── */
  .alert {
    padding: 12px 16px;
    border-radius: var(--radius);
    font-size: .875rem;
    margin-bottom: 20px;
    border-left: 4px solid;
  }
  .alert-success { background: #edfaf4; color: #1f7a52; border-color: var(--success); }
  .alert-error   { background: #fdf0f0; color: #a03030; border-color: var(--danger); }

  /* ── Stats row ───────────────────────────────────────────── */
  .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px; }

  .stat-card {
    background: var(--white);
    border-radius: var(--radius);
    padding: 20px;
    border: 1px solid var(--border);
  }

  .stat-num {
    font-family: 'DM Serif Display', serif;
    font-size: 2.2rem;
    color: var(--accent);
    line-height: 1;
  }

  .stat-label { font-size: .8rem; color: var(--muted); margin-top: 4px; text-transform: uppercase; letter-spacing: .05em; }

  /* ── Cards ───────────────────────────────────────────────── */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

  @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }

  .card {
    background: var(--white);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
  }

  .card-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .card-title {
    font-weight: 600;
    font-size: 1rem;
  }

  .card-body { padding: 20px 24px; }

  /* Upload zone */
  .upload-zone {
    border: 2px dashed var(--border);
    border-radius: var(--radius);
    padding: 28px;
    text-align: center;
    transition: border-color .2s, background .2s;
    cursor: pointer;
  }
  .upload-zone:hover { border-color: var(--accent); background: #fff8f4; }

  .upload-icon { font-size: 2rem; margin-bottom: 8px; }
  .upload-text { font-size: .9rem; color: var(--muted); margin-bottom: 16px; }

  input[type="file"] {
    display: block;
    margin: 0 auto 12px;
    font-family: inherit;
    font-size: .85rem;
  }

  .btn-upload {
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 9px 22px;
    cursor: pointer;
    font-family: inherit;
    font-size: .875rem;
    font-weight: 500;
    transition: background .2s;
  }
  .btn-upload:hover { background: #c9531a; }

  /* Tabla */
  .table-wrap { overflow-x: auto; max-height: 360px; overflow-y: auto; }

  table { width: 100%; border-collapse: collapse; font-size: .875rem; }
  thead th {
    background: var(--light);
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--muted);
    position: sticky;
    top: 0;
  }
  tbody tr { border-top: 1px solid var(--border); transition: background .15s; }
  tbody tr:hover { background: #faf8f5; }
  tbody td { padding: 9px 12px; }

  .badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 600;
  }
  .badge-green  { background: #edfaf4; color: #1f7a52; }
  .badge-orange { background: #fff3e8; color: #c9531a; }
  .badge-gray   { background: #f0f0f0; color: #666; }

  /* Modal */
  .modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 200;
  }
  .modal-overlay.open { display: flex; }

  .modal {
    background: var(--white);
    border-radius: var(--radius);
    padding: 32px;
    max-width: 440px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
  }
  .modal h3 { font-size: 1.2rem; color: var(--danger); margin-bottom: 12px; }
  .modal p  { color: var(--muted); font-size: .9rem; line-height: 1.6; margin-bottom: 24px; }
  .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
</style>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /asignaciones/');
    exit();
}
$totalProf = count($profesores ?? []);
$totalMod  = count($modulos ?? []);
$sinAsignar = count(array_filter($modulos ?? [], fn($m) => empty($m['profesor_id'])));
?>

<!-- Navbar -->
<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <div class="navbar-actions">
    <span class="nav-badge">👤 <?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></span>
    <a href="/asignaciones/?vista=asignacion" class="btn btn-primary">Asignar módulos →</a>
    <a href="/asignaciones/?vista=logout"     class="btn btn-ghost">Cerrar sesión</a>
  </div>
</nav>

<div class="page">

  <!-- Cabecera -->
  <div class="page-header">
    <div class="page-title">
      Panel de administración
      <small>Gestión de profesores y módulos del centro</small>
    </div>
    <button id="btnEliminar" class="btn btn-danger">🗑 Limpiar todos los datos</button>
  </div>

  <!-- Alertas -->
  <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
    <?php unset($_SESSION['mensaje']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <!-- Stats -->
  <div class="stats">
    <div class="stat-card">
      <div class="stat-num"><?= $totalProf ?></div>
      <div class="stat-label">Profesores cargados</div>
    </div>
    <div class="stat-card">
      <div class="stat-num"><?= $totalMod ?></div>
      <div class="stat-label">Módulos totales</div>
    </div>
    <div class="stat-card">
      <div class="stat-num"><?= $totalMod - $sinAsignar ?></div>
      <div class="stat-label">Módulos asignados</div>
    </div>
    <div class="stat-card">
      <div class="stat-num"><?= $sinAsignar ?></div>
      <div class="stat-label">Sin asignar</div>
    </div>
  </div>

  <!-- Grid de cards -->
  <div class="grid-2">

    <!-- ── Profesores ─────────────────────────────── -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">👨‍🏫 Profesores</span>
        <?php if ($totalProf > 0): ?>
          <span class="badge badge-green"><?= $totalProf ?> cargados</span>
        <?php endif; ?>
      </div>
      <div class="card-body">

        <?php if ($totalProf === 0): ?>
          <div class="upload-zone">
            <div class="upload-icon">📂</div>
            <p class="upload-text">Importa la lista de profesores desde un archivo Excel.<br>
            Columnas esperadas: <strong>nombre</strong>, <strong>categoría</strong>.</p>
            <form method="POST" enctype="multipart/form-data">
              <input type="file" name="archivo_profesores" accept=".xlsx,.xls" required>
              <button type="submit" name="subir_profesores" class="btn-upload">Importar profesores</button>
            </form>
          </div>
        <?php else: ?>
          <div class="table-wrap">
            <table>
              <thead>
                <tr><th>#</th><th>Nombre</th><th>Categoría</th></tr>
              </thead>
              <tbody>
                <?php foreach ($profesores as $p): ?>
                <tr>
                  <td><?= $p['orden'] ?></td>
                  <td><?= htmlspecialchars($p['nombre']) ?></td>
                  <td><span class="badge badge-orange"><?= htmlspecialchars($p['categoria'] ?? '-') ?></span></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>
    </div>

    <!-- ── Módulos ────────────────────────────────── -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">📚 Módulos</span>
        <?php if ($totalMod > 0): ?>
          <span class="badge badge-green"><?= $totalMod ?> cargados</span>
        <?php endif; ?>
      </div>
      <div class="card-body">

        <?php if ($totalMod === 0): ?>
          <div class="upload-zone">
            <div class="upload-icon">📂</div>
            <p class="upload-text">Importa el listado de módulos desde Excel.<br>
            Columnas: <strong>grado, curso, nombre, horas, categoría</strong>.</p>
            <form method="POST" enctype="multipart/form-data">
              <input type="file" name="archivo_modulos" accept=".xlsx,.xls" required>
              <button type="submit" name="subir_modulos" class="btn-upload">Importar módulos</button>
            </form>
          </div>
        <?php else: ?>
          <div class="table-wrap">
            <table>
              <thead>
                <tr><th>Ciclo</th><th>Módulo</th><th>Horas</th><th>Profesor</th></tr>
              </thead>
              <tbody>
                <?php foreach ($modulos as $m): ?>
                <tr>
                  <td><span class="badge badge-gray"><?= htmlspecialchars($m['grado'] ?? '') ?></span></td>
                  <td><?= htmlspecialchars($m['nombre_modulo']) ?></td>
                  <td><?= $m['horas'] ?>h</td>
                  <td>
                    <?php if (!empty($m['profesor_nombre'])): ?>
                      <span class="badge badge-green"><?= htmlspecialchars($m['profesor_nombre']) ?></span>
                    <?php else: ?>
                      <span class="badge badge-gray">Sin asignar</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>
    </div>

  </div><!-- /grid-2 -->
</div><!-- /page -->

<!-- Modal confirmación eliminar -->
<div id="modalEliminar" class="modal-overlay">
  <div class="modal">
    <h3>⚠️ ¿Qué deseas eliminar?</h3>
    <p>Selecciona la opción que quieres vaciar. Esta acción no se puede deshacer.</p>
    
    <div class="modal-actions" style="flex-direction: column; gap: 10px;">
    <a href="/asignaciones/controladores/Controlador_eliminar_datos.php?tipo=profesores" 
        class="btn btn-danger" 
        onclick="return confirm('¿Borrar todos los profesores?')">
        Borrar solo Profesores
      </a>

      <a href="/asignaciones/controladores/Controlador_eliminar_datos.php?tipo=modulos" 
        class="btn btn-danger" 
        onclick="return confirm('¿Borrar todos los módulos?')">
        Borrar solo Módulos
      </a>

      <button class="btn btn-ghost" 
              style="background:#eee; color:#333; width: 100%; margin-top: 5px;" 
              onclick="cerrarModal()">
              Cancelar
      </button>
    </div>
  </div>
</div>

<script>
  const modal = document.getElementById('modalEliminar');
  
  // Abrir modal al hacer clic en el botón de la cabecera
  document.getElementById('btnEliminar').addEventListener('click', () => {
    modal.classList.add('open');
  });

  function cerrarModal() { 
    modal.classList.remove('open'); 
  }

  // Cerrar al hacer clic fuera del recuadro blanco
  modal.addEventListener('click', e => { 
    if (e.target === modal) cerrarModal(); 
  });
</script>
</body>
</html>
