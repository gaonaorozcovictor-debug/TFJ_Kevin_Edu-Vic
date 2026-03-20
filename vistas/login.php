<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . '/../modelos/Modelo_usuarios.php';

$modelo = new Modelo_usuarios();
$error = null;

// Procesar login
if(isset($_POST['login'])){

    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $rolSeleccionado = $_POST['rol'] ?? '';

    $user = $modelo->obtenerPorUsuario($usuario);

    if(!$user){
        $error = "Usuario no encontrado";
    }
    else {

        if($user['rol'] !== $rolSeleccionado){
            $error = "El rol seleccionado no coincide";
        }
        else {

            if($user['rol'] === 'admin'){

                if($user['password'] === $password){

                    $_SESSION['usuario'] = $user['usuario'];
                    $_SESSION['rol'] = $user['rol'];

                    header("Location: ./index.php");
                    exit();

                } else {
                    $error = "Contraseña incorrecta";
                }
            }

            else if($user['rol'] === 'profesor'){

                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['profesor_id'] = $user['profesor_id'] ?? null;

                header("Location: ./index.php");
                exit();
            }
        }
    }
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

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-gray-200">

    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">
        Iniciar sesión
    </h2>

    <p class="text-sm text-gray-500 text-center mb-6">
        Usuario: admin <br>
        Contraseña: 1234
    </p>

    <!-- ERROR -->
    <?php if($error): ?>
        <div class="bg-red-100 text-red-600 p-3 mb-4 rounded text-sm text-center">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <!-- USUARIO -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Usuario
            </label>
            <input type="text" name="usuario" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>

        <!-- PASSWORD -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña
            </label>
            <input type="password" name="password" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>

        <!-- ROL -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Rol
            </label>
            <select name="rol" required
                class="w-full px-4 py-2 border rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-orange-400">
                <option value="">Selecciona un rol</option>
                <option value="admin">Admin</option>
                <option value="profesor">Profesor</option>
            </select>
        </div>

        <!-- BOTÓN -->
        <button type="submit" name="login"
            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition">
            Entrar
        </button>

    </form>

</div>

</body>
</html>