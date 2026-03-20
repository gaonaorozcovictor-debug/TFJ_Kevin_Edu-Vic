<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-7xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Panel Administrador</h1>

    <!-- MENSAJE -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="bg-green-500 text-white p-4 mb-6 rounded">
            <?= $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- CERRAR SESIÓN -->
    <form action="./core/cerrar_sesion.php" method="post" class="mb-6">
        <button class="bg-red-500 text-white px-4 py-2 rounded">
            Cerrar sesión
        </button>
    </form>



    <!-- GRID PRINCIPAL -->
    <div class="grid grid-cols-2 gap-6">

        <!-- ================= PROFESORES ================= -->
        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-xl font-bold mb-4">Profesores</h2>

            <!-- SUBIR -->
<?php if(isset($profesores) && count($profesores) > 0): ?>

    <p class="text-red-500 mb-4">
        Ya hay profesores cargados. No puedes subir otro Excel.
    </p>
<?php else: ?>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <input type="file" name="archivo_profesores" required class="mb-3">

        <button type="submit" name="subir_profesores"
            class="bg-orange-500 text-white px-4 py-2 rounded">
            Subir profesores
        </button>
    </form>
<?php endif; ?>

            <!-- TABLA -->
            <?php if(isset($profesores) && count($profesores) > 0): ?>
            <div class="overflow-auto max-h-96">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Categoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($profesores as $prof): ?>
                        <tr class="border-t">
                            <td class="p-2"><?= $prof['id'] ?></td>
                            <td class="p-2"><?= $prof['nombre'] ?></td>
                            <td class="p-2"><?= $prof['categoria'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="text-gray-500">No hay profesores aún.</p>
            <?php endif; ?>

        </div>

        <!-- ================= MÓDULOS ================= -->
        <div class="bg-white p-6 rounded shadow">

            <h2 class="text-xl font-bold mb-4">Módulos</h2>

            <!-- SUBIR (SOLO UNA VEZ) -->
            <?php if(isset($modulos) && count($modulos) > 0): ?>

                <p class="text-red-500 mb-4">
                    Ya hay módulos cargados. No se puede subir otro Excel.
                </p>

            <?php else: ?>

                <form method="POST" enctype="multipart/form-data" class="mb-4">
                    <input type="file" name="archivo_modulos" required class="mb-3">

                    <button type="submit" name="subir_modulos"
                        class="bg-orange-500 text-white px-4 py-2 rounded">
                        Subir módulos
                    </button>
                </form>

            <?php endif; ?>

            <!-- TABLA -->
            <?php if(isset($modulos) && count($modulos) > 0): ?>
            <div class="overflow-auto max-h-96">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID</th>
                            <th class="p-2">Nombre módulo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($modulos as $modulo): ?>
                        <tr class="border-t">
                            <td class="p-2"><?= $modulo['id'] ?></td>
                            <td class="p-2"><?= $modulo['nombre_modulo'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="text-gray-500">No hay módulos aún.</p>
            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>