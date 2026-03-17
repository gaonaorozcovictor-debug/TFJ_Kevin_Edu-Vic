# TFJ_Kevin_Edu-Vic
Este es nuestro TFG del ciclo de DAW 


## 🔌 Activación de extensiones PHP

Para trabajar con archivos Excel es necesario activar ciertas extensiones.

*1. Editar archivo php.ini
C:\xampp\php\php.ini
*2. Activar extensiones

Buscar las siguientes líneas y eliminar el ;:

extension=gd
extension=zip
3. Reiniciar Apache

Desde el panel de control de XAMPP.
🧰 Instalación de Composer

Composer es un gestor de dependencias para PHP.

1. Descargar Composer

Desde la web oficial:
👉 https://getcomposer.org/

2. Instalación

Ejecutar Composer-Setup.exe

Seleccionar la ruta de PHP (ejemplo en XAMPP):

C:\xampp\php\php.exe
3. Verificación
composer -V


✅ Verificación de extensiones
php -m

Debe aparecer:

gd
zip
📦 Instalación de PhpSpreadsheet

La librería utilizada para leer archivos Excel es PhpSpreadsheet.

1. Acceder al proyecto
cd C:\xampp\htdocs\nombre_proyecto
2. Instalar dependencia
composer require phpoffice/phpspreadsheet
3. Archivos generados

vendor/

composer.json

vendor/autoload.php

💻 Uso en el código

Incluir el autoload en los archivos PHP:

require 'vendor/autoload.php'

6. Uso de la librería en el proyecto

En los archivos PHP donde se vaya a utilizar la librería, añadir:

require 'vendor/autoload.php';
