<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Admin — Asignaciones FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/admin.css">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/darkmode.css">
<script src="/asignaciones/vistas/estilos/darkmode.js"></script>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) { header('Location: /asignaciones/'); exit(); }
$totalProf  = count($profesores ?? []);
$totalMod   = count($modulos ?? []);
$sinAsignar = count(array_filter($modulos ?? [], fn($m) => empty($m['profesor_id'])));
?>

<nav class="navbar">
  <div class="navbar-brand">Ciudad Escolar <span>FP</span></div>
  <div class="navbar-actions">
    <span class="nav-badge">👤 <?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></span>
    <a href="/asignaciones/?vista=asignacion" class="btn btn-primary">Asignar módulos →</a>
    
    <button class="btn btn-ghost btn-darkmode" onclick="toggleDarkMode()" title="Cambiar a modo oscuro">🌙 Modo oscuro</button>
    <button class="btn btn-ghost" onclick="document.getElementById('modalPassword').classList.add('open')" title="Cambiar contraseña">🔑 Contraseña</button>
    <a href="/asignaciones/?vista=logout"     class="btn btn-ghost">Cerrar sesión</a>
  </div>
</nav>

<div class="page">
  <div class="page-header">
    <div class="page-title">Panel de administración<small>Gestión de profesores y módulos del centro</small></div>
  </div>

  <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
    <?php unset($_SESSION['mensaje']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <div class="stats">
    <div class="stat-card"><div class="stat-num"><?= $totalProf ?></div><div class="stat-label">Profesores cargados</div></div>
    <div class="stat-card"><div class="stat-num"><?= $totalMod ?></div><div class="stat-label">Módulos totales</div></div>
    <div class="stat-card"><div class="stat-num"><?= $totalMod - $sinAsignar ?></div><div class="stat-label">Módulos asignados</div></div>
    <div class="stat-card"><div class="stat-num"><?= $sinAsignar ?></div><div class="stat-label">Sin asignar</div></div>
  </div>

  <div class="grid-2">

    <!-- Profesores -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">👨‍🏫 Profesores</span>
        <div style="display:flex;align-items:center;gap:10px;">
          <?php if ($totalProf > 0): ?>
            <span class="badge badge-green"><?= $totalProf ?> cargados</span>
            <button type="button" class="btn btn-danger" style="padding:5px 12px;font-size:.8rem;" onclick="abrirModal('profesores')">🗑 Eliminar</button>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <?php if ($totalProf === 0): ?>
          <div class="upload-zone">
            <div class="upload-icon">📂</div>
            <p class="upload-text">Importa la lista de profesores desde Excel.<br>Columnas: <strong>nombre</strong>, <strong>categoría</strong>.</p>
            <form method="POST" enctype="multipart/form-data">
              <input type="file" name="archivo_profesores" accept=".xlsx,.xls" required>
              <button type="submit" name="subir_profesores" class="btn-upload">Importar profesores</button>
            </form>
          </div>
        <?php else: ?>
          <div class="table-wrap">
            <table>
              <thead><tr><th>#</th><th>Nombre</th><th>Categoría</th></tr></thead>
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

    <!-- Módulos -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">📚 Módulos</span>
        <div style="display:flex;align-items:center;gap:10px;">
          <?php if ($totalMod > 0): ?>
            <span class="badge badge-green"><?= $totalMod ?> cargados</span>
            <button type="button" class="btn btn-danger" style="padding:5px 12px;font-size:.8rem;" onclick="abrirModal('modulos')">🗑 Eliminar</button>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <?php if ($totalMod === 0): ?>
          <div class="upload-zone">
            <div class="upload-icon">📂</div>
            <p class="upload-text">Importa el listado de módulos desde Excel.<br>Columnas: <strong>grado, curso, nombre, horas, categoría</strong>.</p>
            <form method="POST" enctype="multipart/form-data">
              <input type="file" name="archivo_modulos" accept=".xlsx,.xls" required>
              <button type="submit" name="subir_modulos" class="btn-upload">Importar módulos</button>
            </form>
          </div>
        <?php else: ?>
          <div class="table-wrap">
            <table>
              <thead><tr><th>Ciclo</th><th>Módulo</th><th>Horas</th><th>Profesor</th></tr></thead>
              <tbody>
                <?php foreach ($modulos as $m): ?>
                <tr>
                  <td><span class="badge badge-gray"><?= htmlspecialchars($m['grado'] ?? '') ?></span></td>
                  <td><?= htmlspecialchars($m['nombre_modulo']) ?></td>
                  <td><?= $m['horas'] ?>h</td>
                  <td><?php if (!empty($m['profesor_nombre'])): ?><span class="badge badge-green"><?= htmlspecialchars($m['profesor_nombre']) ?></span><?php else: ?><span class="badge badge-gray">Sin asignar</span><?php endif; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<!-- Modales -->
<div id="modalProfesores" class="modal-overlay">
  <div class="modal">
    <h3>🗑 Eliminar profesores</h3>
    <p>Se eliminarán todos los profesores. Los módulos quedarán sin asignar pero no se borrarán.<br><br>Esta acción <strong>no se puede deshacer</strong>.</p>
    <div class="modal-actions">
      <button class="btn btn-ghost" style="background:#eee;color:#333;" onclick="cerrarModal('profesores')">Cancelar</button>
      <a href="/asignaciones/controladores/Controlador_eliminar_datos.php?tipo=profesores" class="btn btn-danger">Sí, eliminar</a>
    </div>
  </div>
</div>

<div id="modalModulos" class="modal-overlay">
  <div class="modal">
    <h3>🗑 Eliminar módulos</h3>
    <p>Se eliminarán todos los módulos y sus asignaciones.<br><br>Esta acción <strong>no se puede deshacer</strong>.</p>
    <div class="modal-actions">
      <button class="btn btn-ghost" style="background:#eee;color:#333;" onclick="cerrarModal('modulos')">Cancelar</button>
      <a href="/asignaciones/controladores/Controlador_eliminar_datos.php?tipo=modulos" class="btn btn-danger">Sí, eliminar</a>
    </div>
  </div>
</div>

<!-- Modal cambiar contraseña -->
<div id="modalPassword" class="modal-overlay">
  <div class="modal" style="max-width:460px;">
    <h3>🔑 Cambiar contraseña</h3>
    <p>Introduce tu contraseña actual y la nueva contraseña.</p>

    <?php if (isset($_SESSION['error_pass'])): ?>
      <div style="background:#fdf0f0;color:#a03030;border-left:4px solid #e05252;padding:10px 14px;border-radius:8px;font-size:.85rem;margin-bottom:16px;">
        <?= htmlspecialchars($_SESSION['error_pass']) ?>
      </div>
      <?php unset($_SESSION['error_pass']); ?>
    <?php endif; ?>

    <form method="POST" action="/asignaciones/controladores/Controlador_cambiarPassword.php">
      <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:20px;">
        <div>
          <label style="display:block;font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#777;margin-bottom:5px;">Contraseña actual</label>
          <input type="password" name="password_actual" required autocomplete="current-password"
            style="width:100%;padding:10px 12px;border:1.5px solid #e2dfd8;border-radius:8px;font-family:inherit;font-size:.95rem;outline:none;">
        </div>
        <div>
          <label style="display:block;font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#777;margin-bottom:5px;">Nueva contraseña</label>
          <input type="password" name="password_nueva" required autocomplete="new-password"
            style="width:100%;padding:10px 12px;border:1.5px solid #e2dfd8;border-radius:8px;font-family:inherit;font-size:.95rem;outline:none;">
        </div>
        <div>
          <label style="display:block;font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#777;margin-bottom:5px;">Repetir nueva contraseña</label>
          <input type="password" name="password_repetir" required autocomplete="new-password"
            style="width:100%;padding:10px 12px;border:1.5px solid #e2dfd8;border-radius:8px;font-family:inherit;font-size:.95rem;outline:none;">
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" style="background:#eee;color:#333;" onclick="document.getElementById('modalPassword').classList.remove('open')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar contraseña</button>
      </div>
    </form>
  </div>
</div>

<script>
function abrirModal(tipo) { document.getElementById(tipo === 'profesores' ? 'modalProfesores' : 'modalModulos').classList.add('open'); }
function cerrarModal(tipo) { document.getElementById(tipo === 'profesores' ? 'modalProfesores' : 'modalModulos').classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); }));

// Abrir modal si hubo error en el cambio de contraseña
<?php if (isset($_SESSION['error_pass'])): ?>
document.getElementById('modalPassword').classList.add('open');
<?php endif; ?>
</script>
</body>
</html>