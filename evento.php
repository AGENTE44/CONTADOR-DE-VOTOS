<?php
require_once('definiciones.php');
require_once('funciones.php');

header('Content-Type: text/plain');

// ---------------------------------------------------------------------------------------

function send2br($mssg){echo $mssg;exit;}

// ---------------------------------------------------------------------------------------

$mssg   = file_get_contents('php://input');
$accion = getParamValue($mssg, '|', 'ACCION');
$mesa   = getParamValue($mssg, '|', 'mesa'  );
$clave  = getParamValue($mssg, '|', 'clave' );

// ---------------------------------------------------------------------------------------
// RECEPCION DEL CONTEO
// ---------------------------------------------------------------------------------------
if($accion == 'CUENTAS'){
  $conteos='';    // formato para guardar en el servidor
  $recibidos='';  // formato para enviar de vuelta al usuario
  foreach($opciones as $cuenta){
    $valor = getParamValue($mssg, '|', $cuenta[0]);if( cuenta_invalida($valor) ){send2br('ALERTA=Cuenta <'.$cuenta[0].'> inválida. Revisa el número.|');exit;}
    $conteos .= STRING_keyvalue($cuenta[0], $valor);
    if($recibidos != ''){$recibidos .= '/';}$recibidos .= $cuenta[1].'('.$valor.')';
  }

  if( mesa_invalida($mesa) ){send2br('ALERTA=Mesa inválida. Revisa el número.|');exit;}
  if( limite_modificaciones($mesa) ){send2br('ALERTA=Se ha alcanzado el límite de modificaciones para esta mesa.|');exit;}
  if( clave_invalida($mesa, $clave) ){send2br('ALERTA=Clave inválida. Revisa tu código.|');exit;}

  $hora = (string)date("H:i:s");

  $linea  = STRING_keyvalue("HORA"     , $hora);
  $linea .= STRING_keyvalue("MICROSEC" , (string)microtime(TRUE));
  $linea .= STRING_keyvalue("IP"       , IP_cliente());
  $linea .= STRING_keyvalue("CLAVE"    , $clave);
  $linea .= $conteos;

  FOLDER_ensure(CARPETA_MESAS);
  FILE_addline(ARCHIVO_MESA($mesa), $linea);

  $r  = STRING_keyvalue("ALERTA", 'Datos recibidos correctamente! En menos de '.SEGUNDOS_RECALCULO.' segundos se actualizaran los resultados.');
  $r .= STRING_keyvalue("RECEPCIONADO", '['.$hora.'] Para la mesa '.$mesa.': '.$recibidos);

  send2br($r);exit;
}

// ---------------------------------------------------------------------------------------
// PUBLICACION DE LOS RESULTADOS
// ---------------------------------------------------------------------------------------
if($accion == 'RESULTADOS'){
  gatillador_temporal_unico_recuento(); // entre muchas peticiones, solo una vez debe gatillarse el recuento

  $resultados = FILE_read(ARCHIVO_CONTEO);if($resultados==''){send2br('RESULTADOS=NO_DISPONIBLE|');exit;}

  $respuesta  = STRING_keyvalue("RESULTADOS", "DISPONIBLE");
  $respuesta .= STRING_keyvalue("ESTADOS_SERVIDOR", estados_servidor());
  $respuesta .= $resultados;

  if( mesa_invalida($mesa) ){send2br($respuesta);exit;}

  $info = informacion_de_mesa($mesa);if($info==''){send2br($respuesta.STRING_keyvalue("MESA", 'La mesa '.$mesa.' no ha sido reportada aún.'));exit;}

  send2br($respuesta.STRING_keyvalue("MESA", $info));exit;
}

// ---------------------------------------------------------------------------------------

send2br('ALERTA=ERROR|');

?>
