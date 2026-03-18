<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Procesar login
if(isset($_POST['login'])){
    $_SESSION['sesion'] = true;
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-50 flex items-center justify-center min-h-screen font-sans">

<!-- Card Login -->
<div class="bg-white p-10 rounded-3xl shadow-xl w-full max-w-md border border-orange-100">
    
    <h1 class="text-3xl md:text-4xl font-extrabold text-orange-600 text-center mb-8">
        Iniciar Sesión
    </h1>

    <form method="post" class="space-y-6">

        <!-- Usuario -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Usuario
            </label>
            <input 
                type="text" 
                name="usuario" 
                placeholder="Introduce tu usuario" 
                required
                class="w-full border border-orange-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition"
            >
        </div>

        <!-- Contraseña (opcional para futuras mejoras) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Contraseña
            </label>
            <input 
                type="password" 
                name="password" 
                placeholder="Introduce tu contraseña" 
                required
                class="w-full border border-orange-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition"
            >
        </div>

        <!-- Botón -->
        <button 
            type="submit" 
            name="login"
            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 hover:shadow-lg"
        >
            Entrar
        </button>

    </form>

    <!-- Footer opcional -->
    <p class="mt-6 text-center text-sm text-gray-500">
        &copy; 2026 IES Ciudad Escolar. KEV.
    </p>

</div>

</body>
</html>