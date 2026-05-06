<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso — Asignaciones FP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --accent:  #e35f1f;
    --accent2: #f7841a;
    --dark:    #1a1a2e;
    --mid:     #2e2e4a;
    --light:   #f4f1eb;
    --white:   #ffffff;
    --muted:   #9391a4;
    --border:  #e2dfd8;
    --success: #2cb67d;
    --danger:  #e05252;
    --radius:  12px;
  }

  html, body {
    height: 100%;
    background: var(--light);
    font-family: 'DM Sans', sans-serif;
    color: var(--dark);
  }

  .page {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
  }

  /* ── Panel izquierdo decorativo ─────────────────────────── */
  .hero {
    background: var(--dark);
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 64px 56px;
    position: relative;
    overflow: hidden;
  }

  .hero::before {
    content: '';
    position: absolute;
    width: 480px; height: 480px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(227,95,31,.35) 0%, transparent 70%);
    top: -120px; left: -120px;
    pointer-events: none;
  }

  .hero::after {
    content: '';
    position: absolute;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(247,132,26,.20) 0%, transparent 70%);
    bottom: -60px; right: -60px;
    pointer-events: none;
  }

  .hero-logo {
    font-family: 'DM Serif Display', serif;
    font-size: 2.4rem;
    color: var(--white);
    line-height: 1.15;
    position: relative;
    z-index: 1;
  }

  .hero-logo span { color: var(--accent2); }

  .hero-sub {
    margin-top: 16px;
    color: var(--muted);
    font-size: .95rem;
    line-height: 1.6;
    max-width: 340px;
    position: relative;
    z-index: 1;
  }

  .hero-divider {
    width: 48px; height: 3px;
    background: var(--accent);
    border-radius: 2px;
    margin: 28px 0;
    position: relative;
    z-index: 1;
  }

  .hero-feature {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #b0aec0;
    font-size: .875rem;
    margin-bottom: 14px;
    position: relative;
    z-index: 1;
  }

  .hero-feature .dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--accent2);
    flex-shrink: 0;
  }

  /* ── Panel derecho (formularios) ────────────────────────── */
  .forms-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 32px;
    background: var(--white);
  }

  .forms-inner {
    width: 100%;
    max-width: 420px;
  }

  .forms-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.75rem;
    color: var(--dark);
    margin-bottom: 6px;
  }

  .forms-subtitle {
    color: var(--muted);
    font-size: .9rem;
    margin-bottom: 32px;
  }

  /* Alerta */
  .alert {
    padding: 12px 16px;
    border-radius: var(--radius);
    font-size: .875rem;
    margin-bottom: 20px;
    border-left: 4px solid;
  }

  .alert-error   { background: #fdf0f0; color: var(--danger);  border-color: var(--danger); }
  .alert-success { background: #edfaf4; color: var(--success); border-color: var(--success); }

  /* Tabs */
  .tabs {
    display: flex;
    background: var(--light);
    border-radius: var(--radius);
    padding: 4px;
    margin-bottom: 28px;
  }

  .tab-btn {
    flex: 1;
    padding: 9px;
    border: none;
    background: transparent;
    border-radius: 9px;
    cursor: pointer;
    font-family: inherit;
    font-size: .875rem;
    font-weight: 500;
    color: var(--muted);
    transition: all .2s;
  }

  .tab-btn.active {
    background: var(--white);
    color: var(--dark);
    box-shadow: 0 1px 4px rgba(0,0,0,.1);
  }

  /* Formularios */
  .form-panel { display: none; }
  .form-panel.active { display: block; }

  .form-group { margin-bottom: 16px; }

  label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--muted);
    margin-bottom: 6px;
  }

  input[type="text"],
  input[type="password"],
  select {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    font-family: inherit;
    font-size: .95rem;
    color: var(--dark);
    background: var(--white);
    outline: none;
    transition: border-color .2s;
    appearance: none;
  }

  input:focus, select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(227,95,31,.12);
  }

  .btn-primary {
    display: block;
    width: 100%;
    padding: 13px;
    background: var(--accent);
    color: var(--white);
    border: none;
    border-radius: var(--radius);
    font-family: inherit;
    font-size: .95rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s, transform .1s;
    margin-top: 20px;
  }

  .btn-primary:hover   { background: #c9531a; }
  .btn-primary:active  { transform: scale(.98); }

  .select-wrap { position: relative; }
  .select-wrap::after {
    content: '▾';
    position: absolute;
    right: 14px; top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    pointer-events: none;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .page { grid-template-columns: 1fr; }
    .hero { display: none; }
    .forms-wrap { padding: 32px 20px; }
  }
</style>
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
        <form method="POST" action="/asignaciones/controladores/Controlador_login.php">
          <div class="form-group">
            <label for="profesor_id">Selecciona tu nombre</label>
            <div class="select-wrap">
              <select id="profesor_id" name="profesor_id">
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
</script>
</body>
</html>
