# TFJ_Kevin_Edu-Vic

## RUTA DEL PROYECTO
C:\xampp\htdocs\TFG\

<<<<<<< HEAD
📋 Resumen del Progreso del Proyecto
✅ FUNCIONALIDADES IMPLEMENTADAS HOY
1. Orden de módulos por ciclo

Los módulos ahora se muestran ordenados por grado (DAW, DAM, ASIR) y curso

Mejora la organización visual en todas las vistas

2. Destacado de módulos PS/PT

Los módulos que contienen "PS" o "PT" en el nombre se muestran con fondo morado

Incluyen un badge "PS/PT" para identificarlos fácilmente

3. Visualización de horas

Las horas de cada módulo aparecen junto al nombre entre paréntesis: (6h)

Se mantienen al mover módulos entre columnas

4. Vista exclusiva del profesor

Nuevo panel profesorPanel.php donde el profesor solo ve sus módulos asignados

Muestra tabla con ciclo, nombre, horas, categoría y total acumulado

5. Botón eliminar todos los datos

En el panel admin, botón rojo "🗑️ Eliminar todos los datos"

Modal de confirmación antes de borrar

Elimina profesores y módulos, y resetea auto_increment

6. Acceso directo a asignación

Enlace destacado en adminPanel.php que lleva directamente a la página de asignación

❌ LO QUE FALTA POR TERMINAR
Selector de profesores en la página de asignación

En index.php?vista=asignacion debería aparecer:

Un dropdown con todos los profesores de la base de datos

Un contador que muestre las horas totales del profesor seleccionado

Estado actual: La página carga los módulos correctamente pero el dropdown de profesores no aparece. La variable $profesoresConHoras no se está pasando desde index.php a la vista asignarModulos.php.

Por hacer: Corregir la comunicación entre el router y la vista para que los profesores se muestren y se pueda completar la asignación.

=======
Este es nuestro TFG del ciclo de DAW 


## 🔌 Activación de extensiones PHP

Para trabajar con archivos Excel es necesario activar ciertas extensiones.

* 1. Editar archivo php.ini
C:\xampp\php\php.ini
* 2. Activar extensiones

Buscar las siguientes líneas y eliminar el ;:
  extension=gd
  extension=zip

* 3. Reiniciar Apache

## 🧰 Instalación de Composer

Composer es un gestor de dependencias para PHP.

* 1. Descargar Composer

  Desde la web oficial:
  👉 https://getcomposer.org/

* 2. Instalación

  Ejecutar Composer-Setup.exe
  
  Seleccionar la ruta de PHP (ejemplo en XAMPP):
  
  C:\xampp\php\php.exe
  
* 3. Verificación
  composer -V (si no va reiniciar terminal)


## ✅ Verificación de extensiones
php -m

Debe aparecer:
gd
zip

## 📦 Instalación de PhpSpreadsheet

La librería utilizada para leer archivos Excel es PhpSpreadsheet.

* 1. Acceder al proyecto
  cd C:\xampp\htdocs\nombre_proyecto
* 2. Instalar dependencia
  composer require phpoffice/phpspreadsheet
* 3. Archivos generados
    vendor/
    
    composer.json
    
    vendor/autoload.php

## 💻 Uso en el código

Incluir el autoload en los archivos PHP:

require 'vendor/autoload.php'

6. Uso de la librería en el proyecto

En los archivos PHP donde se vaya a utilizar la librería, añadir:

require 'vendor/autoload.php';
>>>>>>> 5b7bc574d2ec7e749ae3ceb474b49e06d3eb6748
