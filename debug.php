<?php
// ARCHIVO DE DIAGNÓSTICO - BORRAR DESPUÉS DE USARLO
// Accede a: https://ciudadescolarfp.es/asignaciones/debug.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='font-family:monospace;font-size:13px;padding:20px'>";
echo "<h2>🔍 Diagnóstico del servidor</h2>\n\n";

// 1. PHP version
echo "✅ PHP: " . phpversion() . "\n";

// 2. Extensiones necesarias
$exts = ['pdo', 'pdo_mysql', 'zip', 'xml', 'mbstring', 'gd'];
foreach ($exts as $ext) {
    $ok = extension_loaded($ext);
    echo ($ok ? "✅" : "❌") . " Extensión $ext: " . ($ok ? "OK" : "NO CARGADA") . "\n";
}

// 3. Rutas
echo "\n--- Rutas ---\n";
echo "__DIR__: " . __DIR__ . "\n";
$autoload = __DIR__ . '/Recursos/vendor/autoload.php';
echo "Autoload existe: " . (file_exists($autoload) ? "✅ SÍ" : "❌ NO - $autoload") . "\n";

// 4. Test conexión BD
echo "\n--- Base de datos ---\n";
$configs = [
    'ciudadescolarfp.es',
    'ciudadescolarfp_es',
    'asignaciones',
];
foreach ($configs as $dbname) {
    try {
        $dsn = 'mysql:host=134.0.14.185;dbname=' . $dbname . ';charset=utf8mb4';
        $pdo = new PDO($dsn, 'asignaciones', 'aplicacion$2026dAw', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "✅ Conexión OK con dbname='$dbname'\n";
        break;
    } catch (Exception $e) {
        echo "❌ dbname='$dbname': " . $e->getMessage() . "\n";
    }
}

// 5. .htaccess
echo "\n--- .htaccess ---\n";
echo "mod_rewrite: " . (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? "✅ activo" : "⚠️ no detectable (puede estar activo igualmente)") . "\n";

echo "\n</pre>";
?>
