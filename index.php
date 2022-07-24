<?php
require_once('definiciones.php');
require_once('funciones.php');

revisar_autoinstalacion();

function ECHO_campo($etiqueta, $id){echo '<p style="text-align:center;margin: 0;">'.$etiqueta.'</p><input class="CAMPO ch" style="text-align:center;width: 90%;" type="text" id="'.$id.'"><br><br>';}
function ECHO_barra($id, $etiqueta, $porcentaje){
echo '
<div class="sr" style="height:30px;padding:2px;">
<div class="" style="height:100%;width:80%;background-color: #F0F0F0;position: relative;">
  <div class="BARRA" id="'.$id.'" style="height:100%;width:'.(string)$porcentaje.'%;background-color: #00EEEE;"></div>
  <div class="center">'.$etiqueta.'</div>
</div>
<div style="height:100%;width:20%;position: relative;"><div class="PORCENTAJE center" id="'.$id.'" >'.(string)$porcentaje.'%</div></div>
</div>
';}
function ECHO_boton($etiqueta, $funcion, $datos){
echo '
<div class="animated-button" onmouseup="'.$funcion.'(event)" data-accion="'.$datos.'" style="height:50px;width:100%;">
  <p class="center nopm" style="font-size: 16px;color: white;">'.$etiqueta.'</p>
</div>
';}

function ECHO_campos(){global $opciones;foreach($opciones as $opcion){ECHO_campo($opcion[1].":", $opcion[0]);}}
function ECHO_barras(){global $opciones;foreach($opciones as $opcion){ECHO_barra($opcion[0], $opcion[1], $opcion[2]);}}
function ECHO_array_opciones_js(){global $opciones;$l='';foreach($opciones as $opcion){$l.='"'.$opcion[0].'", ';}echo 'const LISTA_OPCIONES =['.$l.'];'."\r\n";}
function ECHO_array_etiquetas_js(){global $opciones;$l='';foreach($opciones as $opcion){$l.='"'.$opcion[1].'", ';}echo 'const LISTA_ETIQUETAS =['.$l.'];'."\r\n";}
?>


<!DOCTYPE html>
<html>
  <head>
    <link rel="icon" href="data:,">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTADOR DE VOTOS</title>
  </head>
<!-- ================================================================================= -->
<style>

.bblack{border:1px solid black;}

*{font-family: 'Arial', sans-serif;}
.sr{display:flex;flex-direction:row;position:relative;}
.ch{margin: 0;position: absolute;left: 50%;-ms-transform: translateX(-50%);transform: translateX(-50%);}
.cv{margin: 0;position: absolute;top: 50%;-ms-transform: translateY(-50%);transform: translateY(-50%);}
.center{margin: 0;position: absolute;top: 50%;left: 50%;-ms-transform: translate(-50%, -50%);transform: translate(-50%, -50%);}

.animated-button{
  position:relative;background-color: #00AAAA;cursor:pointer;transition: 0.3s;min-height:50px;width: 90%;
  -webkit-user-select: none; /* Safari */
  -ms-user-select: none;     /* IE 10 and IE 11 */
   user-select: none;        /* Standard syntax */
   margin: 0;left: 50%;-ms-transform: translateX(-50%);transform: translateX(-50%);
}
.animated-button:hover{background-color: #00CCCC;} /* Color on mouse-over */
.animated-button:active{background-color: #00EEEE;} /* Color when mousedown (set always after hover) */
.animated-button:disabled {background-color: #FF0000;} /* no hace nada */
</style>

<body style="margin:0;padding:0;">
<!-- ================================================================================= -->

<div class="ch bblack" style="position:relative;width:500px;max-width:100%;padding:5px;">


<h3 class="bblack" style="text-align:center;margin:0;font-size: 20px;">CONTADOR DE VOTOS PARALELO</h3>

<div class="sr" style="height:100px;margin:10px 0 10px 0;">
  <img src="logo.png" style="height:100%;margin-right:15px;">
  <div class=""><div class="cv" style="font-size: 18px;font-weight: 700;"><?php echo NOMBRE_COALICION;?></div></div>
  <div id="info_servidor" style="position:absolute;font-family:'Courier New', monospace;font-size:14px;text-align:right;top:0px;right:0px;width:auto;margin:0;padding:0;">
    <?php echo estados_servidor();?>
  </div>
</div>

<div class="" style="height:auto;margin-bottom:10px;">
  <div id="titulo_resultados" class="" style="font-size:18px;">Resultados globales.</div>
  <?php ECHO_barras();?>
  <div style="padding:5px 0 5px 0;font-size:18px;">Información complementaria global:</div>
  <div id="info_global" style="font-family: 'Courier New', monospace;font-size:16px;">Esperando...</div>
  <div style="padding:5px 0 5px 0;font-size:18px;">Información de mesa (extraido del servidor):</div>
  <div id="info_mesa" style="font-family: 'Courier New', monospace;font-size:16px;">Esperando...</div>
  <div style="padding:5px 0 5px 0;font-size:18px;">Reportes enviados:</div>
  <div id="info_reportes" style="font-family: 'Courier New', monospace;font-size:16px;"></div>
  <?php ECHO_boton("LIMPIAR HISTORIAL", "limpiar_reportes_enviados", "");?>
</div>

<div class="" style="border-style: solid;border-width: 1px 0px 0px 0px;padding:10px 0 5px 0;">
  <div class="" style="text-align:center;">REPORTE DE CONTEO</div>
  <div class="" style="text-align: justify;">
    Si eres testigo presencial del conteo de votos puedes llenar los siguientes campos y mandarlos al servidor de tu partido/coalición
    para que se contabilicen todas las mesas. El supervisor a determinado <?php echo MAXIMO_MODIFICACIONES;?> modificaciones máximas por mesa.
    La información que envías desde acá debe coincidir en todo momento con los datos que aparecen en "Información de mesa" (ver arriba),
    que es la que se está utilizando en el servidor para hacer el recuento global.
    Si detectas alguna anomalía (alteración maliciosa) deberás reportarlo al supervisor.
    <span style="font-weight: 600;">
      AVISO IMPORTANTE: Sólo basta con que escribas el número de mesa (cualquiera existente) para que la información respectiva aparezca arriba.
      NO ES NECESARIO USAR CLAVE NI HACER CLICK EN ENVIAR.
    </span>
  </div>
  <br>
  <?php ECHO_campos();?>
  <?php ECHO_campo("Mesa:", "mesa");?>
  <?php ECHO_campo("Clave mesa:", "clave");?>
  <br>
  <?php ECHO_boton("ENVIAR", "BOTON_mouseup", "CUENTAS");?>
</div>

</div>

<!-- ================================================================================= -->
</body>

<script>
// ---------------------------------------------------------------------------------------
// CÓDIGO JS
// ---------------------------------------------------------------------------------------
<?php ECHO_array_opciones_js();?>
<?php ECHO_array_etiquetas_js();?>
const EVENT_RESOURCE = 'evento.php';
const SEGUNDOS_ACTUALIZACION = <?php echo SEGUNDOS_ACTUALIZACION;?>;
let detalle_conexion = '';
let mesa = '';
let flag_enviando = 0;

// ---------------------------------------------------------------------------------------

function getParam(line, char, param){
  if(!line){return('');}
  var start = 0;
  var pc=0;

  var i=0;var c=line.charAt(i);
  while(c){
    if(c==char){
      if(pc==param){return line.slice(start, i);}
      pc++;start=i+1;
    }
    i++;c=line.charAt(i);
  }
  if(pc==param){return line.slice(start, i);}
  return '';
}

function getParamValue(line, char_sep, char_equ, attr){
  var i=0;var attrval = getParam(line, char_sep, i);
  while(attrval != ''){
    if( getParam(attrval, char_equ, 0) == attr ){return getParam(attrval, char_equ, 1);}
    i++;attrval = getParam(line, char_sep, i);
  }
  return '';
}

function getKeyValue(mssg, key){return getParamValue(mssg, '|', '=', key);}
function STRING_keyvalue(key, value){return key + '=' + value + '|';}
function STRING_invalid(str){if(str==''){return(1);}if(0<=str.indexOf('=')){return(1);}if(0<=str.indexOf('|')){return(1);}return(0);}
function HTML_string_color(color, text){return(`<span style="color:${color};">${text}</span>`);}
function ELEMENT_find(list, id){for(var i=0;i<list.length;i++){if(list[i].id==id){return(list[i]);}}return(null);}

// ---------------------------------------------------------------------------------------

function salvar_campos(){
  var content = '';
  var campos = document.getElementsByClassName("CAMPO");
  for (var i=0;i<campos.length;i++){content+=STRING_keyvalue(campos[i].id, campos[i].value);}
  localStorage.setItem("conteo_local", content);
  mesa = document.getElementById("mesa").value;
}

function recuperar_campos(){
  var content = localStorage.getItem("conteo_local");
  var campos = document.getElementsByClassName("CAMPO");
  var p;var campo;
  p=0;campo=getParam(content, '|', p);
  while(campo!=''){var tb=ELEMENT_find(campos, getParam(campo, '=', 0));if(tb){tb.value=getParam(campo, '=', 1);}p++;campo=getParam(content, '|', p);}
  mesa = document.getElementById("mesa").value;
}
// ---------------------------------------------------------------------------------------

function actualizar_reportes_enviados(){
  var info_r=localStorage.getItem("reportes");if(!info_r){info_r='No se han enviado reportes desde este terminal.<br><br>';}
  document.getElementById("info_reportes").innerHTML = info_r;
}
function agregar_reporte_enviado(reporte){
  var info_r=localStorage.getItem("reportes");if(!info_r){info_r='';}
  reporte += info_r;localStorage.setItem("reportes", reporte);
  actualizar_reportes_enviados();
}
function limpiar_reportes_enviados(event){
  if(1 < event.button){return;}
  localStorage.setItem("reportes", '');
  actualizar_reportes_enviados();
}
// ---------------------------------------------------------------------------------------

function actualizar_resultados(mssg){
  document.getElementById("info_servidor").innerHTML = getKeyValue(mssg, 'ESTADOS_SERVIDOR');

  var hora = getKeyValue(mssg, 'HORA_RECUENTO');
  var mesas = getKeyValue(mssg, 'MESAS');
  var pmesas = getKeyValue(mssg, 'PORC_MESAS');
  var tvotos = Number( getKeyValue(mssg, 'TOTAL_VOTOS') );
  var barras = document.getElementsByClassName("BARRA");
  var indicadores = document.getElementsByClassName("PORCENTAJE");

  for (var i=0;i<LISTA_OPCIONES.length;i++){
    var votos = Number( getKeyValue(mssg, LISTA_OPCIONES[i]) );
    var porc; if(tvotos==0){porc=0;}else{porc = 100*(votos/tvotos);}
    var barra = ELEMENT_find(barras, LISTA_OPCIONES[i]);barra.style.width = `${porc.toFixed(1)}%`;
    var iporc = ELEMENT_find(indicadores, LISTA_OPCIONES[i]);iporc.innerHTML = `${porc.toFixed(1)}%`;
  }

  var info_g = '';
  info_g += `Último recuento: ${hora}`;
  info_g += `<br>Mesas escrutadas: ${mesas} (${pmesas})`;
  info_g += `<br>Total votos emitidos: ${tvotos}`;
  for (var i=0;i<LISTA_OPCIONES.length;i++){info_g += `<br>Votos ${LISTA_ETIQUETAS[i]}: ${getKeyValue(mssg, LISTA_OPCIONES[i])}`;}
  document.getElementById("info_global").innerHTML = info_g;

  var info_m = getKeyValue(mssg, 'MESA');if(info_m==''){info_m='Mesa no especificada o incorrecta.';}
  document.getElementById("info_mesa").innerHTML = info_m;
}

// ---------------------------------------------------------------------------------------

function procesar_respuesta(mssg){
  if(mssg == 'ERROR_COM'){var ed='Hay problemas con la comunicación. Verifica tu conexión.';detalle_conexion = HTML_string_color('red', ed);if(flag_enviando){flag_enviando=0;window.alert(ed);}return;}
  if(mssg == 'ERROR_RESP'){var ed='El servidor no responde. Reintentando en breve.';detalle_conexion = HTML_string_color('red', ed);if(flag_enviando){flag_enviando=0;window.alert(ed);}return;}
  var recpcionado = getKeyValue(mssg, 'RECEPCIONADO');if(recpcionado!=''){agregar_reporte_enviado(recpcionado + '<br><br>');}
  var resultados = getKeyValue(mssg, 'RESULTADOS');if(resultados=='DISPONIBLE'){actualizar_resultados(mssg);}
  var alerta = getKeyValue(mssg, 'ALERTA');if(alerta!=''){flag_enviando=0;window.alert(alerta);}
}

// ---------------------------------------------------------------------------------------

function send2server(url, mssg, process){
  fetch(url, {method: 'POST', headers: {'Content-Type': 'text/plain'}, body: mssg})
  .then(function(response){if(response.ok) {return response.text();}return('ERROR_RESP');})
  .catch(function(error){process('ERROR_COM');})
  .then(process);
}

// ---------------------------------------------------------------------------------------

let segundos=SEGUNDOS_ACTUALIZACION;
function revisar_actualizacion(){
  document.getElementById("titulo_resultados").innerHTML = `Resultados globales. La información se actualizará en ${segundos} segundos. ` + detalle_conexion;
  segundos--;if(0<segundos){return;}segundos=SEGUNDOS_ACTUALIZACION;
  detalle_conexion = '';
  salvar_campos();send2server(EVENT_RESOURCE, `ACCION=RESULTADOS|mesa=${mesa}|`, procesar_respuesta);
}
setInterval(revisar_actualizacion, 1000);

// ---------------------------------------------------------------------------------------

function BOTON_mouseup(event){
  if(1 < event.button){return;}
  var parent = event.currentTarget.parentElement;
  var params = STRING_keyvalue("ACCION", event.currentTarget.getAttribute('data-accion'));
  var elements = parent.children;
  for (var i = 0; i < elements.length; i++) {
    if( 0 <= elements[i].className.indexOf("CAMPO") ){
      if(STRING_invalid(elements[i].value) && elements[i].id!='clave'){window.alert(`Campo invalido en <${elements[i].id}>`);return;}
      params += STRING_keyvalue(elements[i].id, elements[i].value);
    }
  }
  salvar_campos();send2server(EVENT_RESOURCE, params, procesar_respuesta);flag_enviando=1;
}

// ---------------------------------------------------------------------------------------

recuperar_campos();
actualizar_reportes_enviados();

</script>
</html>
