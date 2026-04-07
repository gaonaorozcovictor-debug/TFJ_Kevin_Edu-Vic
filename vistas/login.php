<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border-t-4 border-orange-500">

    <h1 class="text-2xl font-bold text-center mb-6 text-gray-700">
        Acceso al sistema
    </h1>

    <!-- ERROR -->
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-orange-100 text-orange-700 p-3 rounded mb-4 text-center">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- ADMIN -->
    <form method="POST" action="controladores/controlador_login.php" class="mb-6">

        <h2 class="text-lg font-semibold mb-3 text-gray-600">Administrador</h2>

        <input type="text" name="usuario" placeholder="Usuario"
            class="w-full mb-3 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400">

        <input type="password" name="password" placeholder="Contraseña"
            class="w-full mb-4 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400">

        <button type="submit" name="login_admin"
            class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600 transition">
            Entrar como admin
        </button>

    </form>

    <hr class="my-6">

    <!-- PROFESOR -->
    <form method="POST" action="controladores/controlador_login.php">

        <h2 class="text-lg font-semibold mb-3 text-gray-600">Profesor</h2>

        <select name="profesor_id"
            class="w-full mb-4 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-orange-400">
            
            <option value="">-- Selecciona un profesor --</option>

            <?php if(!empty($profesores)): ?>
                <?php foreach($profesores as $profesor): ?>
                    <option value="<?= $profesor['orden'] ?>">
                        <?= htmlspecialchars($profesor['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled>No hay profesores cargados</option>
            <?php endif; ?>

        </select>

        <button type="submit" name="login_profesor"
            class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600 transition">
            Entrar como profesor
        </button>

    </form>

</div>

</body>
</html>