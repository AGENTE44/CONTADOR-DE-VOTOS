<?php if(count(get_included_files()) <=1){echo "CACA";exit;};
// ---------------------------------------------------------------------------------------

// ESTE CONTADOR DE VOTOS PARALELOS ES COMPLETAMENTE PERSONALIZABLE
// puedes incluir un logotipo de tu partido/coalicion usando un archivo ".png" con el nombre logo.png
// junto con los 3 archivos principales: funciones.php, evento.php, index.php
// este logotipo se mostrará en tu portal
// para reiniciar el contador, configura primero este archivo con los valores deseados y luego borra las carpetas MESAS, RESULTADOS, SEGURIDAD (si es que ya estaban creadas)
// en la carpeta de seguridad se generará automáticamente un archivo con la lista de claves deseadas

define("NOMBRE_COALICION"  , "PARTIDO<br>REPUBLICANO");  // escoge el nombre de tu partido/coalicion (el <br> es solo un ENTER para mostrar 2 lineas), esto se mostrará en tu portal

define("MESA_ULTIMA", 46887);         // aqui puede variar segun la ocacion, esta info debiera proporcionarla el SERVEL (creo que son aprox 46887, de todas formas deben confirmarlo)
define("MESA_MAXVOTOS", 1000);        // maxima cantidad de votos para una mesa, esto se utiliza para proteger un poco (aqui tambien depende de la info del SERVEL)
define("MAXIMO_MODIFICACIONES", 3);   // maximo de intentos para modificar la cuenta de una mesa
define("SEGUNDOS_ACTUALIZACION", 30); // cantidad de segundos para actualizar la informacion en el navegador, no debe ser chico para asi no saturar el servidor (entre 30 a 60 segundos está bien)
define("SEGUNDOS_RECALCULO", 60);     // el recalculo de todos los votos es un proceso que podria saturar el servidor, por lo que se debe ejecutar cada cierto tiempo razonable (minimo cada 60 seg)
define("TOTAL_CLAVES", 100);          // aquí deciden cuantas claves quieren crear (una por mesa? existen 46887 segun el servel)
define("CARACTERES_CLAVE", 4);        // aqui definimos el numero de caracteres para nuestras claves
define("CLAVE_ESTRICTA", 0);          // si este valor es 0, cualquier clave (de la lista) es permitida en cualquier mesa, si es 1, la clave debe estar en la linea (de la lista) equivalente al numero de mesa (esto es mas restrictivo)

date_default_timezone_set("America/Santiago");  // configura la hora del servidor a la hora de chilito

// ---------------------------------------------------------------------------------------

// aqui podemos cambiar/agregar las opciones de votación que queramos
// el primer campo es un identificador de variable para el programa, es interno y debe ser unico (EJ: candidato1, candidato2, etc )
// el segundo campo es la etiqueta publica que se mostrará en el portal web (EJ: José Antonio Kast, MEO, etc)
// el tercer campo es utilizado para el calculo interno y siempre debe estar limpio (o sea cero)

$opciones = array(
  array("apruebo", "Apruebo", 0),   // nombre de la variable = "apruebo" , etiqueta = "Apruebo" , cuenta = 0
  array("rechazo", "Rechazo", 0),   // nombre de la variable = "rechazo" , etiqueta = "Rechazo" , cuenta = 0
  array("blancos", "Blancos", 0),   // nombre de la variable = "blancos" , etiqueta = "Blancos" , cuenta = 0
  array("nulos"  , "Nulos"  , 0),   // nombre de la variable = "nulos"   , etiqueta = "Nulos"   , cuenta = 0
);

// ---------------------------------------------------------------------------------------

// otras definiciones globales (no es necesario tocar nada aqui)
define("CARPETA_MESAS", __DIR__."/MESAS/");
define("CARPETA_RESULTADOS", __DIR__."/RESULTADOS/");
define("CARPETA_SEGURIDAD", __DIR__."/SEGURIDAD/");
define("ARCHIVO_CONTEO", CARPETA_RESULTADOS."CONTEO.txt");
define("ARCHIVO_EVOLUCION", CARPETA_RESULTADOS."EVOLUCION.txt");
define("ARCHIVO_RECALCULO", CARPETA_RESULTADOS."RECALCULO.txt");
function ARCHIVO_MESA($mesa) : string {return(CARPETA_MESAS."MESA_".$mesa.".txt");}

?>
