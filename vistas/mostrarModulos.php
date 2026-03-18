<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignación de Módulos</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen font-sans">

<div class="max-w-7xl mx-auto p-6">

    <!-- Header con usuario y cerrar sesión -->
    <header class="flex flex-col md:flex-row justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-2 md:mb-0">
                Asignación de Módulos
            </h1>
            <br>
            <?php if(isset($_SESSION['usuario'])): ?>
                <p class="text-gray-600 text-md">Sesión iniciada como: 
                    <span class="font-semibold text-orange-500"><?= htmlspecialchars($_SESSION['usuario']) ?></span>
                </p>
            <?php endif; ?>
        </div>

        <form action="./core/cerrar_sesion.php" method="post">
            <button type="submit" 
                class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-full shadow-md transition transform hover:-translate-y-0.5 hover:shadow-lg flex items-center space-x-2">
                <span>🔒</span><span>Cerrar sesión</span>
            </button>
        </form>
    </header>

    <!-- Layout de columnas para Excel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Columna Profesores -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-orange-300 flex items-center space-x-2">
                <span>👩‍🏫</span><span>Profesores</span>
            </h2>

            <form action="" method="POST" enctype="multipart/form-data" class="mb-4 space-y-4">
                <input type="file" name="archivo_profesores" accept=".xls,.xlsx" required
                    class="w-full border border-orange-200 rounded-full p-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition">
                <button type="submit" name="subir_profesores"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-full shadow-md transition transform hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center space-x-2">
                    <span>📤</span><span>Subir Profesores</span>
                </button>
            </form>

            <?php if(!empty($datosProfesores)): ?>
            <div class="overflow-x-auto max-h-[300px]">
                <table class="min-w-full border border-gray-200 rounded-lg divide-y divide-gray-200">
                    <thead class="bg-orange-50 sticky top-0">
                        <tr>
                            <?php foreach($datosProfesores[0] as $i => $val): ?>
                                <th class="px-4 py-2 text-left text-sm font-medium text-orange-500">Col <?= $i+1 ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datosProfesores as $fila): ?>
                            <tr class="even:bg-gray-50 hover:bg-orange-50 transition">
                                <?php foreach($fila as $celda): ?>
                                    <td class="border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                        <?= htmlspecialchars($celda) ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- Columna Módulos -->
        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-orange-300 flex items-center space-x-2">
                <span>📚</span><span>Módulos</span>
            </h2>

            <form action="" method="POST" enctype="multipart/form-data" class="mb-4 space-y-4">
                <input type="file" name="archivo_modulos" accept=".xls,.xlsx" required
                    class="w-full border border-orange-200 rounded-full p-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-300 transition">
                <button type="submit" name="subir_modulos"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-full shadow-md transition transform hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center space-x-2">
                    <span>📤</span><span>Subir Módulos</span>
                </button>
            </form>

            <?php if(!empty($datosModulos)): ?>
            <div class="overflow-x-auto max-h-[300px]">
                <table class="min-w-full border border-gray-200 rounded-lg divide-y divide-gray-200">
                    <thead class="bg-orange-50 sticky top-0">
                        <tr>
                            <?php foreach($datosModulos[0] as $i => $val): ?>
                                <th class="px-4 py-2 text-left text-sm font-medium text-orange-500">Col <?= $i+1 ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datosModulos as $fila): ?>
                            <tr class="even:bg-gray-50 hover:bg-orange-50 transition">
                                <?php foreach($fila as $celda): ?>
                                    <td class="border border-gray-200 px-4 py-2 text-sm text-gray-700">
                                        <?= htmlspecialchars($celda) ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

    </div>

</div>
</body>
</html>