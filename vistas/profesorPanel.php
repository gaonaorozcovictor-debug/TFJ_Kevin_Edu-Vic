<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: index.php?vista=login");
    exit();
}
// Verificar que $modulos existe
if(!isset($modulos)) {
    $modulos = [];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel del Profesor</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<!-- HEADER -->
<div class="bg-white shadow-md px-6 py-4 flex justify-between items-center border-b-4 border-orange-400">

    <div class="text-gray-700 font-semibold">
        Bienvenido, <span class="text-orange-500"><?= $_SESSION['nombre'] ?? 'Profesor' ?></span>
    </div>

    <form action="./core/cerrar_sesion.php" method="post">
        <button type="submit" 
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow transition">
            Cerrar sesión
        </button>
    </form>

</div>

<div class="p-6">

    <h1 class="text-3xl font-bold mb-8 text-gray-800">
        Mis módulos asignados
    </h1>

    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">

        <?php if(empty($modulos)): ?>
            <p class="text-gray-500 text-center py-8">
                No tienes módulos asignados todavía.
            </p>
        <?php else: ?>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left p-3">Ciclo</th>
                            <th class="text-left p-3">Módulo</th>
                            <th class="text-left p-3">Horas</th>
                            <th class="text-left p-3">Categoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalHoras = 0;
                        foreach($modulos as $modulo): 
                            $totalHoras += $modulo['horas'];
                            $esPsPt = (stripos($modulo['nombre_modulo'], 'PS') !== false || 
                                      stripos($modulo['nombre_modulo'], 'PT') !== false);
                        ?>
                        <tr class="border-t hover:bg-gray-50 <?= $esPsPt ? 'bg-purple-50' : '' ?>">
                            <td class="p-3">
                                <span class="text-xs font-semibold text-gray-600 bg-gray-200 px-2 py-0.5 rounded">
                                    <?= htmlspecialchars($modulo['grado']) ?>
                                </span>
                            </td>
                            <td class="p-3 font-medium">
                                <?= htmlspecialchars($modulo['nombre_modulo']) ?>
                                <?php if($esPsPt): ?>
                                    <span class="text-xs ml-2 px-2 py-0.5 bg-purple-200 text-purple-800 rounded-full">PS/PT</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 text-gray-600">
                                <?= $modulo['horas'] ?>h
                            </td>
                            <td class="p-3 text-gray-600">
                                <?= htmlspecialchars($modulo['categoria'] ?? '-') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td class="p-3">Total</td>
                            <td class="p-3"></td>
                            <td class="p-3"><?= $totalHoras ?>h</td>
                            <td class="p-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>