<?php if(count(get_included_files()) <=1){echo "CACA";exit;};
// ---------------------------------------------------------------------------------------
// funciones de utilidad publica
// ---------------------------------------------------------------------------------------
function IP_cliente() : string {return $_SERVER['REMOTE_ADDR'];}//function IP_cliente() : string {return "127.0.0.1";}
function round_1d($n){return floor($n * 10) / 10;}
function round_2d($n){return floor($n * 100) / 100;}
function timelapse_sec ($stime) : float {return (microtime(TRUE)-$stime);}
function STRING_empty($str) : bool{if($str==null){return(1);}if($str==''){return(1);}return(0);}
function STRING_keyvalue($key, $value) : string {return $key.'='.$value.'|';}
function STRING_random($length) : string {return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', $length)), 1, $length);}
function STRING_valid($str, $filtro) : int {
  if(STRING_empty($str)){return(0);}
  for($i=0;$i<strlen($str);$i++){ if( substr_count($filtro, substr($str, $i, 1)) == 0 ){return(0);} }
  return(1);
}
// ---------------------------------------------------------------------------------------
// funciones de strings
// ---------------------------------------------------------------------------------------
function getParam($line, $char, $param) : string {
  if($line==''){return '';}
  $start = 0;
  $pc=0;

  $i=0;$c=substr($line, $i, 1);
  while($c != ''){
    if($c==$char){
      if($pc==$param){return substr($line, $start, $i-$start);}
      $pc++;$start=$i+1;
    }
    $i++;$c=substr($line, $i, 1);
  }
  if($pc==$param){return substr($line, $start, $i-$start);}
  return '';
}

function getParamValue($line, $char, $attr) : string {
  $i=0;$attrval = getParam($line, $char, $i);
  while($attrval != ''){
    if( getParam($attrval, '=', 0) == $attr ){return getParam($attrval, '=', 1);}
    $i++;$attrval = getParam($line, $char, $i);
  }
  return '';
}
// ---------------------------------------------------------------------------------------
// funciones de archivos
// ---------------------------------------------------------------------------------------
function FILE_write($file, $content){$myfile = fopen($file, "w");fwrite($myfile, $content);fclose($myfile);}  // debe existir la carpeta
function FILE_read($file) : string {if(!file_exists($file)){return('');}return file_get_contents($file);}
function FILE_addline($file, $line){$fp = fopen($file, "a");fwrite($fp, $line."\r\n");fclose($fp);}
function FOLDER_ensure($folder){if(is_dir($folder)){return;}mkdir($folder, 0777, true);}
// ---------------------------------------------------------------------------------------
// ejemplo de uso: $a = FILE_get_lines(file);foreach($a as $line){echo $line.'<br>';}
function FILE_get_lines($file){
  $a = array();$i=0;
  if(!file_exists($file)){return($a);}
  $fi = fopen($file, "r");
  while(!feof($fi)){ $li = rtrim(fgets($fi), "\r\n");if(!STRING_empty($li)){$a[$i]=$li;$i++;}}
  fclose($fi);
  return($a);
}

// ejemplo de uso: $a = FILE_get_list(FOLDER);foreach($a as $entry){echo $entry.'<br>';}
function FILE_get_list($folder){
  $a = array();$i=0;
  $entries = scandir($folder);
  foreach ($entries as $entry){ if(is_file($folder.$entry) == true){$a[$i]=$entry;$i++;} }
  return($a);
}

function FILE_get_last_line($file) : string {
  if(!file_exists($file)){return('');}
  $fi = fopen($file, "r");
  $line = '';$lline = '';while(!feof($fi)) {$line = rtrim(fgets($fi), "\r\n");if($line!=''){$lline=$line;}}
  fclose($fi);
  return($lline);
}
// ---------------------------------------------------------------------------------------
// FUNCIONES MAESTRAS
// ---------------------------------------------------------------------------------------
// aqui esta el meollo del asunto, esta es la funcion que recuenta TODOS los votos reportados en los archivos MESA_XXXX.txt
// si quieren saber si hay trampa, simplemente revisen acá
// pero ojo, no recomiendo ejecutar esta funcion persistentemente porque saturará el servidor
// yo recomiendo ejecutar esta funcion minimo cada 1 minuto, ya que el recuento podría demorar 12-15 segundos en el peor de los casos
// esto es lo que tardaría sumar los archivos de 47000 mesas (en un disco SSD, porque en uno mecánico tardaría aún más)
// teniendo miles de usuarios reportando y monitoreando al mismo tiempo, este tiempo de espera es fatal para la fluidez del portal
// por eso yo recomiendo siempre usar el gatillador temporal (ver abajo) para proteger el servidor y no se caiga
function recontabilizar_todo($folder) : string {
  global $opciones;

  // obtenemos la lista de todos los archivos MESA_XXXX.txt generados, el cual tambien corresponde al numero de mesas escrutadas
  $a = FILE_get_list($folder);
  // analizamos los archivos MESA_XXXX.txt uno por uno
  foreach($a as $file){
    // leemos la informacion del conteo en la ultima linea del archivo MESA_XXXX.txt (ultima modificacion si es que hubieron varias)
    $line = FILE_get_last_line($folder.$file);
    // aqui hacemos la suma (se guardará en el arreglo "$opciones" , declarado en definiciones.php)
    if($line!=''){ for($i=0;$i<count($opciones);$i++){$valor=(int)getParamValue($line, '|', $opciones[$i][0]);$opciones[$i][2]+=$valor;} }
  }

  // pondremos el resultado en un archivo público que será consultado por todos los
  // usuarios conectados para que actualizen sus navegadores
  $fc  = STRING_keyvalue("HORA_RECUENTO", (string)date("H:i:s"));                   // adjuntar hora del ultimo recuento
  $fc .= STRING_keyvalue("MESAS" , (string)count($a)."/".(string)MESA_ULTIMA);      // adjuntar total de mesas escrutadas
  $fc .= STRING_keyvalue("PORC_MESAS", (string)round_2d(100*count($a)/MESA_ULTIMA)."%");    // adjuntar porcentaje de mesas escrutadas
  $total=0;foreach($opciones as $opcion){$total += $opcion[2];}                     // calculo total votos emitidos
  $fc .= STRING_keyvalue("TOTAL_VOTOS" , (string)$total);                           // adjuntar total votos emitidos
  foreach($opciones as $opcion){$fc .= STRING_keyvalue($opcion[0], $opcion[2]);}    // adjuntar recuentos individuales de cada opcion

  // finalmente guardamos los datos recopilados
  FOLDER_ensure(CARPETA_RESULTADOS);      // se asegura la existencia de la carpeta
  FILE_write(ARCHIVO_CONTEO, $fc);        // este será el archivo consultado por todos los usuarios conectados
  //FILE_addline(ARCHIVO_EVOLUCION, $fc);   // este es un archivo acumulativo para ir midiendo la evolucion del recuento, esto puede servir a futuro para detectar posibles fraudes
  return($fc);
}
// -------------------------------------------
function gatillador_temporal_unico_recuento(){
  if(file_exists(ARCHIVO_RECALCULO)){
    $linea = FILE_read(ARCHIVO_RECALCULO);if(getParamValue($linea, '|', 'RECALCULANDO') == '1'){return;}                // si ya se esta recalculando, no hacer nada
    $stime = (float)getParamValue($linea, '|', 'MICROSEC');if( timelapse_sec ($stime) < SEGUNDOS_RECALCULO ){return;}   // si aun no se cumple el tiempo, no hacer nada
  }
  // preparo la ejecucion del recalculo
  FILE_write(ARCHIVO_RECALCULO, "RECALCULANDO=1|"); // activo el FLAG para avisar a otras ejecuciones que voy a recalcular todo y no me molesten
  recontabilizar_todo(CARPETA_MESAS);               // recalculo (esto podria tomar hasta 12-15 segundos, consultando 47000 archivos en un disco SSD)
  FILE_write(ARCHIVO_RECALCULO, "RECALCULANDO=0|MICROSEC=".(string)microtime(TRUE)."|");  // desactivo el FLAG y reinicio el temporizador
}
// ---------------------------------------------------------------------------------------
function informacion_de_mesa($mesa) : string {
  global $opciones;
  $linea = FILE_get_last_line(ARCHIVO_MESA($mesa));if($linea==''){return('');}

  $info = 'La mesa '.$mesa.' se reportó por ultima vez a las '.getParamValue($linea, '|', 'HORA').' (hora servidor), desde la IP '.getParamValue($linea, '|', 'IP').', con los siguientes resultados:<br>';
  foreach($opciones as $opcion){$valor = getParamValue($linea, '|', $opcion[0]);if($valor!=''){$info .= 'Votos '.$opcion[1].': '.$valor.'<br>';}}

  return($info);
}
// ---------------------------------------------------------------------------------------
function estados_servidor() : string {
  $info  = "<br>HORA SERVIDOR<br>".(string)date("H:i")." Hrs";
  $info .= "<br>TU IP<br>".IP_cliente();
  return($info);
}
// ---------------------------------------------------------------------------------------
// FILTROS DE SEGURIDAD PARA LOS CAMPOS (retornan 1 si es invalido)
// ---------------------------------------------------------------------------------------
function mesa_invalida($mesa) : int {
  if(5 < strlen($mesa)){return(1);}
  if( STRING_valid($mesa, "0123456789") == 0 ){return(1);}
  if((int)$mesa < 1){return(1);}
  if(MESA_ULTIMA < (int)$mesa){return(1);}
  return(0);
}

function cuenta_invalida($valor) : int {
  if(6 < strlen($valor)){return(1);}
  if( STRING_valid($valor, "0123456789") == 0 ){return(1);}
  if(MESA_MAXVOTOS < (int)$valor){return(1);}
  return(0);
}

function limite_modificaciones($mesa) : int {
  $a = FILE_get_lines(ARCHIVO_MESA($mesa));if(MAXIMO_MODIFICACIONES <= count($a)){return(1);}
  return(0);
}

function clave_invalida($mesa, $clave) : int {
  // si no existen archivos en la carpeta de seguridad (fueron borrados deliberadamente), se asume que no se requerirá clave alguna
  $a = FILE_get_list(CARPETA_SEGURIDAD);if(count($a)==0){return(0);}

  if(STRING_empty($clave)){return(1);}
  $i = 1;
  foreach($a as $file){
    if($file!='index.php'){
      $fp = fopen(CARPETA_SEGURIDAD.$file, "r");
      while(!feof($fp)){
        $linea = rtrim(fgets($fp), "\r\n");
        if(!STRING_empty($linea) && CLAVE_ESTRICTA == 0 && $clave==$linea){fclose($fp);return(0);}
        if(!STRING_empty($linea) && CLAVE_ESTRICTA == 1 && $clave==$linea && $mesa==$i){fclose($fp);return(0);}
        $i++;
      }
      fclose($fp);
    }
  }
  return(1);
}
// ---------------------------------------------------------------------------------------
// Funcion de autoinstalacion (solo en el caso que no existan las carpetas de trabajo)
// ---------------------------------------------------------------------------------------
function revisar_autoinstalacion(){

  // aseguramos carpetas
  FOLDER_ensure(CARPETA_MESAS);
  FOLDER_ensure(CARPETA_RESULTADOS);
  if(is_dir(CARPETA_SEGURIDAD)){return;}

  // creamos la carpeta de seguridad
  FOLDER_ensure(CARPETA_SEGURIDAD);

  // creamos archivo de pantalla
  FILE_write(CARPETA_SEGURIDAD."index.php", "<?php echo 'SAL DE ACÁ MARICON!!!';?>");

  // creamos las claves
  $n=TOTAL_CLAVES;$claves='';while($n){$claves .= STRING_random(CARACTERES_CLAVE)."\r\n";$n--;}

  // guardamos las claves en un archivo de nombre complejo para que nadie pueda acceder a el desde afuera
  FILE_write(CARPETA_SEGURIDAD.STRING_random(64), $claves);
}

?>
