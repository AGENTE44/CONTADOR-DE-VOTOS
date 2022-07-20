# CONTADOR-DE-VOTOS
Portal WEB libre para la recepción de recuento de votos electorales

"No importa quién vota, sino quién cuenta los votos." Joseph Stalin.

A raíz de los cuestionables resultados de las últimas votaciones electorales en CHILE, con estadísticas y cifras fuera de norma, candidatos desconocidos electos y otros reelectos con historial cuestionable, he creado este contador de votos que permite a cualquier agrupación de personas, ya sea partido político, coalición, institución, etc., poder implementar libre y fácilmente su propio portal WEB de recepción de votos independiente, permitiendoles llevar un conteo paralelo al utilizado por los organismos oficiales del gobierno (en este caso el SERVEL). Cualquier persona puede participar del recuento de votos, solo necesita de un smartphone con conexión a internet durante el escrutinio, el cual a través del navegador reportará los resultados de su mesa al portal y acceder al recuento global en tiempo real, antes de los publicados por el organismo oficial. 

Al contrario de lo que se piensa sobre este tipo de herramientas, no se necesita nada sofisticado ni software complejo para implementarlo: solo 3 archivos de código PHP en un servidor (hosting) alquilado temporalmente serán suficientes para poner en marcha esta herramienta. Solo faltaría coordinación y voluntad por los distintos grupos que deseen utilizar esta herramienta para asegurar un recuento transparente. Se entiende que al final del día, todas las agrupaciones que implementan este sistema paralelo deberán coincidir en sus resultados. Los resultados de TODAS las mesas deberán ser publicados para que cualquier persona pueda acceder libremente a ella y corrobore por sí misma la veracidad de la información.

----

Archivos involucrados

Las funciones necesarias para el portal se reducen a 3 archivos de código abierto PHP publicados acá: "index.php", "funciones.php" y "evento.php". Al ser un programa muy breve, resultará fácil para cualquier programador revisarlo y verificar la presencia de errores o potenciales trampas.

Todos estos archivos deben colocarse en una misma carpeta pública de nuestro servidor (public_html, htdocs, etc), aunque también se pueden duplicar y colocar en sub-carpetas distintas si se desea llevar varios recuentos simultáneos supervisados por el mismo grupo (por ejemplo, elección de presidente, parlamentarios, concejales, etc., realizados el mismo día de votación).

Archivo de configuración

Debemos agregar un cuarto archivo "definiciones.php" para configurar y personalizar nuestro portal WEB. Aquí podrán definir el nombre de la agrupación, opciones de votación, claves a generar, entre otros. Mas abajo se tratará esto en detalle.

Archivo de imágen

Puedes incluir una imagen o logo que identifique a tu agrupación, el cual se mostrará en tu portal. Este debe ser un archivo png de nombre "logo.png", con unos 400x400 pixeles sería suficiente para no sobrecargar el servidor.

----

Instalación

Una vez puesto los 5 archivos en una carpeta pública del servidor y haber definido los parámetros en el archivo "definiciones.php", desde un navegador cualquiera accedemos al portal por primera vez para que se generen 3 carpetas de trabajo: MESAS, RESULTADOS y SEGURIDAD. Para reiniciar el conteo, solo bastaria con borrar estas 3 carpetas.

MESAS: aquí se guardarán los reportes de cada mesa en archivos de texto separados: "MESA_1.txt", "MESA_2.txt", "MESA_3.txt", etc. Se asume que cada mesa de votación debe identificarse con un número único y correlativo.

RESULTADOS: aquí dentro se guardará un archivo "CONTEO.txt" con el resultado del recuento global (sumatoria de todas las mesas). Este proceso de recuento de muchos archivos "MESA_XXXX.txt" consume tiempo y podria saturar el servidor, por lo tanto esto lo agilizamos utilizando un archivo de respaldo del recuento global que será consultado frecuentemente por todos los usuarios conectados (lectura en tiempo real). Entre las miles de consultas de usuarios, solo una de ellas gatillará el recuento global para actualizar el cómputo en este archivo, de forma periódica según el tiempo especificado en "definiciones.php".

SEGURIDAD: esta última carpeta es muy importante, puesto que acá se creará automáticamente un archivo con todas las claves que se especificaron en "definiciones.php" y que permitirá autentificar a los usuarios. En este archivo se pondrá 1 clave por linea y solo el supervisor o el administrador del servidor podrá acceder a ella (se generará con nombre aleatorio para evitar accesos externos indebidos). Hay 2 niveles de seguridad que se pueden implementar con estas claves según lo defina el grupo, lo cual explicaremos más abajo.







