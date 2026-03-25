# TFJ_Kevin_Edu-Vic
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

