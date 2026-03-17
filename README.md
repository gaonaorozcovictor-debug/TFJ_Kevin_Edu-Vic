# TFJ_Kevin_Edu-Vic
Este es nuestro TFG del ciclo de DAW 

INSTALACIÓN Y CONFIGURACIÓN PARA IMPORTAR ARCHIVOS EXCEL EN PHP
1. Requisitos previos

Tener instalado un servidor local como XAMPP

Tener PHP instalado (versión recomendada: PHP 7.4 o superior)

Tener acceso a la terminal (CMD o PowerShell)

2. Instalación de Composer

Para gestionar dependencias en PHP se utiliza Composer.

Pasos:

Acceder a la web oficial:
https://getcomposer.org/

Descargar el instalador para Windows (Composer-Setup.exe)

Ejecutar el instalador

Cuando lo solicite, seleccionar la ruta del ejecutable de PHP:
Ejemplo en XAMPP:
C:\xampp\php\php.exe

Finalizar la instalación

Verificar que Composer está correctamente instalado ejecutando en terminal:

composer -V

3. Activación de extensiones necesarias en PHP

Para poder trabajar con archivos Excel es necesario activar ciertas extensiones de PHP.

Pasos:

Abrir el archivo de configuración php.ini:

C:\xampp\php\php.ini

Buscar las siguientes líneas:

;extension=gd
;extension=zip

Eliminar el punto y coma (;) para activarlas:

extension=gd
extension=zip

Guardar los cambios

Reiniciar Apache desde el panel de control de XAMPP

4. Verificación de extensiones

Ejecutar en terminal:

php -m

Comprobar que aparecen las extensiones:

gd
zip

5. Instalación de la librería PhpSpreadsheet

Se utiliza la librería PhpSpreadsheet para leer archivos Excel.

Pasos:

Acceder a la carpeta del proyecto:

cd C:\xampp\htdocs\nombre_proyecto

Ejecutar el siguiente comando:

composer require phpoffice/phpspreadsheet

Esto generará:

Carpeta vendor/

Archivo composer.json

Archivo autoload.php

6. Uso de la librería en el proyecto

En los archivos PHP donde se vaya a utilizar la librería, añadir:

require 'vendor/autoload.php';
