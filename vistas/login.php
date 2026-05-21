<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso — Asignaciones FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/login.css">
<link rel="stylesheet" href="/asignaciones/vistas/estilos/darkmode.css">
<script src="/asignaciones/vistas/estilos/darkmode.js"></script>
</head>
<body>

<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<div class="page">

  <!-- Panel izquierdo -->
  <div class="hero">
    <div class="hero-logo">Ciudad Escolar<br><span>FP — Asignaciones</span></div>
    <div class="hero-divider"></div>
    <p class="hero-sub">Gestión de módulos formativos y asignación de profesores para la Formación Profesional.</p>
    <div style="margin-top:40px">
      <div class="hero-feature"><span class="dot"></span> Importación desde Excel</div>
      <div class="hero-feature"><span class="dot"></span> Asignación visual por arrastrar</div>
      <div class="hero-feature"><span class="dot"></span> Control de horas por docente</div>
      <div class="hero-feature"><span class="dot"></span> Vista individual del profesor</div>
    </div>
  </div>

  <!-- Formularios -->
  <div class="forms-wrap">
    <div class="forms-inner">

      <h1 class="forms-title">Bienvenido</h1>
      <p class="forms-subtitle">Selecciona tu tipo de acceso para continuar.</p>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
        <?php unset($_SESSION['mensaje']); ?>
      <?php endif; ?>

      <!-- Dark mode -->
      <div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
        <button class="btn-darkmode" onclick="toggleDarkMode()" style="background:transparent;border:1px solid var(--border);border-radius:8px;padding:6px 14px;cursor:pointer;font-size:.85rem;font-family:inherit;" title="Cambiar modo">🌙 Modo oscuro</button>
      </div>

      <!-- Tabs -->
      <div class="tabs">
        <button class="tab-btn active" onclick="cambiarTab('admin', this)">Administrador</button>
        <button class="tab-btn"       onclick="cambiarTab('profesor', this)">Profesor</button>
      </div>

      <!-- Tab Admin -->
      <div id="panel-admin" class="form-panel active">
        <form method="POST" action="/asignaciones/controladores/Controlador_login.php">
          <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="admin" autocomplete="username">
          </div>
          <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••" autocomplete="current-password">
          </div>
          <button type="submit" name="login_admin" class="btn-primary">Entrar como administrador →</button>
        </form>
      </div>

      <!-- Tab Profesor -->
      <div id="panel-profesor" class="form-panel">
        <form method="POST" action="/asignaciones/controladores/Controlador_login.php" id="formProfesor">
          <div class="form-group">
            <label for="profesor_id">Selecciona tu nombre</label>
            <div class="select-wrap">
              <select id="profesor_id" name="profesor_id" onchange="comprobarPasswordProfesor(this.value)">
                <option value="">— Elige un profesor —</option>
                <?php if (!empty($profesores)): ?>
                  <?php foreach ($profesores as $p): ?>
                    <option value="<?= $p['orden'] ?>">
                      <?= htmlspecialchars($p['nombre']) ?> (<?= htmlspecialchars($p['categoria'] ?? '') ?>)
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option disabled>No hay profesores cargados</option>
                <?php endif; ?>
              </select>
            </div>
          </div>

          <!-- Campo contraseña: oculto por defecto, se muestra si el profesor tiene password -->
          <div class="form-group" id="campoPasswordProfesor" style="display:none;">
            <label for="password_profesor">Contraseña</label>
            <input type="password" id="password_profesor" name="password_profesor" placeholder="••••••" autocomplete="current-password">
            <small style="color:var(--muted);font-size:.78rem;margin-top:4px;display:block;">Este profesor tiene contraseña establecida.</small>
          </div>

          <button type="submit" name="login_profesor" class="btn-primary">Entrar como profesor →</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
function cambiarTab(id, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('panel-' + id).classList.add('active');
}

function comprobarPasswordProfesor(profesorId) {
  const campo = document.getElementById('campoPasswordProfesor');
  const input = document.getElementById('password_profesor');

  if (!profesorId) {
    campo.style.display = 'none';
    input.value = '';
    return;
  }

  fetch('/asignaciones/controladores/Controlador_checkPasswordProfesor.php?profesor_id=' + profesorId)
    .then(r => r.json())
    .then(data => {
      if (data.tienePassword) {
        campo.style.display = 'block';
        input.focus();
      } else {
        campo.style.display = 'none';
        input.value = '';
      }
    })
    .catch(() => {
      campo.style.display = 'none';
    });
}
</script>
</body>
</html>
