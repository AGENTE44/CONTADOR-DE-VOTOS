# CONTADOR-DE-VOTOS
Portal WEB libre para la recepción de recuento de votos electorales

<img src="https://user-images.githubusercontent.com/109446387/180042518-1bca89ad-6351-41b2-865f-d886b0f81614.jpg" height="280">

A raíz de los cuestionables resultados de las últimas votaciones electorales en CHILE, con estadísticas y cifras fuera de norma, candidatos desconocidos electos y otros reelectos con historial cuestionable, he creado este contador de votos que permite a cualquier agrupación de personas, ya sea partido político, coalición, institución, etc., poder implementar libre y fácilmente su propio portal WEB de recepción de votos independiente, permitiendoles llevar un conteo paralelo al utilizado por los organismos oficiales del gobierno (en este caso el SERVEL). Cualquier persona puede participar del recuento de votos, solo necesita de un smartphone con conexión a internet durante el escrutinio, el cual a través del navegador reportará los resultados de su mesa al portal y acceder al recuento global en tiempo real, antes de los publicados por el organismo oficial. 

<img src="https://user-images.githubusercontent.com/109446387/180041986-a4db381a-8e47-4fcd-b4e0-0adbe9c4e855.png" height="280">

Al contrario de lo que se piensa sobre este tipo de herramientas, no se necesita nada sofisticado ni software complejo para implementarlo: solo 3 archivos de código PHP en un servidor (hosting) alquilado temporalmente serán suficientes para poner en marcha esta herramienta. Solo faltaría la coordinación y voluntad por los distintos grupos que deseen utilizar esta herramienta para asegurar un recuento transparente. Se entiende que al final del día, todas las agrupaciones que implementan este sistema de forma paralela deberán converger en sus resultados. La información de TODAS las mesas deberá ser publicada para que cualquier persona pueda acceder a ella y corrobore por sí misma la veracidad de esta. Al ser esta una herramienta de código abierto, ninguna de las agrupaciones tiene el control absoluto de ella por lo tanto no podría intervenir en la implementación de las otras.

<img src="https://user-images.githubusercontent.com/109446387/180037519-52ee27c1-905f-4e33-99bb-e051cedb5535.png" height="280">

----

Archivos involucrados

Las funciones necesarias para el portal se reducen a 3 archivos de código abierto PHP publicados acá: "index.php", "funciones.php" y "evento.php". Al ser un programa muy breve, resultará fácil para cualquier programador revisarlo y verificar la presencia de errores o potenciales trampas.

Todos estos archivos deben colocarse en una misma carpeta pública de nuestro servidor (public_html, htdocs, etc), aunque también se pueden duplicar y colocar en sub-carpetas distintas si se desea llevar varios recuentos simultáneos supervisados bajo la misma agrupación (por ejemplo, elección de presidente, parlamentarios, concejales, etc., realizados el mismo día de votación).

Archivo de configuración

Debemos agregar un cuarto archivo "definiciones.php" para configurar y personalizar nuestro portal WEB. Aquí podrán definir el nombre de la agrupación, opciones de votación, claves a generar, entre otros. Mas abajo trataremos esto en detalle.

Archivo de imágen

Puedes incluir una imagen o logo que identifique a tu agrupación el cual se mostrará en tu portal. Este debe ser un archivo png de nombre "logo.png", con unos 400x400 pixeles sería suficiente para no sobrecargar el servidor.

----

Instalación

Una vez puesto los 5 archivos en una carpeta pública del servidor y haber definido los parámetros en el archivo "definiciones.php", desde un navegador cualquiera accedemos al portal por primera vez para que se generen 3 carpetas de trabajo: MESAS, RESULTADOS y SEGURIDAD. Para reiniciar el conteo, solo basta con borrar estas 3 carpetas.

MESAS: aquí se guardarán los reportes de cada mesa en archivos de texto separados: "MESA_1.txt", "MESA_2.txt", "MESA_3.txt", etc. Se asume que cada mesa de votación debe identificarse con un número único y correlativo. A continuación se muestra el contenido de uno de estos archivos. En este ejemplo se muestran 3 líneas que corresponden a 3 modificaciones del conteo (configurable). En cada línea esta la información corespondiente a cada recuento. El recuento global utilizará la última línea (última modificación) para el cómputo final. 

<img src="https://user-images.githubusercontent.com/109446387/180044380-2c9ec920-9c17-425b-8aa1-6f186aec0fe0.png" height="100">

RESULTADOS: aquí dentro se guardará un archivo "CONTEO.txt" con el resultado del recuento global (sumatoria de todas las mesas). Este proceso de recuento de muchos archivos "MESA_XXXX.txt" consume tiempo y podria saturar el servidor, por lo tanto esto lo agilizamos utilizando un archivo de respaldo del recuento global que será consultado frecuentemente por todos los usuarios conectados (lectura en tiempo real). Entre las miles de consultas de usuarios, solo una de ellas gatillará el recuento global para actualizar el cómputo en este archivo, de forma periódica según el tiempo especificado en "definiciones.php". A continuación se muestra el contenido de este archivo. Esta información corresponde al recuento global y debiera actualizarse mínimo cada 1 minuto (configurable) para no saturar el servidor.

<img src="https://user-images.githubusercontent.com/109446387/180046171-c6ef2398-1288-459b-9450-3af64ba31fc0.png" height="100">

SEGURIDAD: esta última carpeta es muy importante, puesto que acá se creará automáticamente un archivo con todas las claves que se especificaron en "definiciones.php" y que permitirá autentificar a los usuarios. En este archivo se pondrá 1 clave por linea y solo el supervisor o el administrador del servidor podrá acceder a ella para su eventual distribución (se generará con nombre aleatorio para evitar accesos externos indebidos). Hay 3 niveles de seguridad que se pueden implementar con estas claves según lo defina el grupo, lo cual explicaremos más abajo. En el siguiente ejemplo se muestra una lista con claves aleatorias de 10 dígitos. Nótese también la aleatoriedad del nombre del archivo.

<img src="https://user-images.githubusercontent.com/109446387/180046713-6aa3b1c3-9f6c-4501-a665-1283fe14433e.png" height="100">

----

Configuración del archivo "definiciones.php"

lorem




