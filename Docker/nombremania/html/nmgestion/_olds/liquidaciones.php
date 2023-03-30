<?
/*
Liquidaciones realizadas a clientes opciones de pago y modificacion de datos
*/
$campos="liquidaciones.id,clientes.nombre,clientes.email, importe, pagado,forma_pago,fecha,fecha_pago, CONCAT('<A HREF=liquidaciones.php?tarea=modifica&id=',liquidaciones.id,'>modifica</a>')  as tarea ";
$campo_tit=array("nombre","Email","importe","pagado", "forma pago","fecha liq.", "fecha pago","tarea");

?>
<html><head>
<link rel="stylesheet" type="text/css" href="estilo.css">
</head><body>
<?
include "barra.php"; include "basededatos.php"; include "hachelib.php";
?>
  <form action=<?=$SCRIPT_NAME?> method=get>
	<INPUT TYPE=hidden name=tarea value=buscar>
 <table width="700" border="0" cellspacing="0" cellpadding="2" align="center">
   <tr>
     <td width="210" bgcolor="#000033" align="center">
       <div align="center"><font color="#FFFFFF"><b><font size="2">LIQUIDACIONES</font></b></font></div>
     </td>
     <td width="8" align="right"><font size="2"> </font></td>
     <td colspan="2" align="left" bgcolor="#666666" width="470"><b><font color="#FFFFFF" size="1">Buscar:
       <input type=text size=25 name=buscar>
       </font></b></td>
   </tr>
   <tr>
     <td width="210" height="5">&nbsp;</td>
     <td width="8" height="5">
       <div align="right"><font size="1"></font></div>
     </td>
     <td colspan="2" valign="middle" align="left" height="5" bgcolor="#999999" width="470"><font size="2">
       <input type=radio name=campo value=affiliate_id checked>
       </font>
			 <font size="1">Por Id cliente&nbsp;&nbsp;
       <input type=radio name=campo value=fecha>
       Por fecha.(dd-mm-aaaa dd-mm-aaaa)</font></td>
   </tr>
   <tr>
     <td width="210" height="5">&nbsp;</td>
     <td width="8" height="5">&nbsp;</td>
     
		 <td colspan="2" valign="middle" align="left" height="5" bgcolor="#FFFF66" width="470">
		 <font size="2">
		 <? $cons="tarea=consulta&buscar=".urlencode("='S'")."&campo=pagado"?>
		 <a href="<?=$PHP_SELF?>?<?=$cons?>">Mostrar solo las pagadas</a> |
		 <? $cons="tarea=consulta&buscar=".urlencode("='N'")."&campo=pagado"?>	
		 <a href="<?=$PHP_SELF?>?<?=$cons?>">Mostrar solo NO las pagadas
		 </a>|
		 </font> </td>
   </tr>
   <tr>
     <td colspan="4" height="5">
       <hr>
     </td>
   </tr>
 </table>
</FORM>
<?
$conn->debug=0;

function consulta($buscar="",$campo="",$orden="") {
GLOBAL $conn,$PHP_SELF,$REQUEST_URI,$campos,$campos_tit;


$sql="select $campos from  clientes, liquidaciones  where liquidaciones.affiliate_id = clientes.id ";
$sql .= " and $campo $buscar ";

if ($orden !="") $sql.=" order by $orden";

$rs=$conn->execute($sql);
rs2html($rs,"class=tabla border=1 align=Center",$campo_tit);
return;
}


function ver_todos() {
global $conn,$REQUEST_URI,$campos,$campos_tit;

$sql="select $campos from  clientes, liquidaciones  where liquidaciones.affiliate_id = clientes.id ";

$rs=$conn->execute($sql);

rs2html($rs,"class=tabla border=1 align=Center",$campo_tit);
return;
}

function modificar($id){
Global $conn,$campos,$campos_tit,$SCRIPT_NAME;
$sql="select * from liquidaciones where id=$id";
$rs=$conn->execute($sql);
rs2html($rs,"class=tabla border=1 align=Center",$campo_tit);
$rs->movefirst();
$fecha_pago=$rs->fields["fecha_pago"];
$forma_de_pago=$rs->fields["forma_pago"];
if ($fecha_pago <>""){
list($a,$m,$d) = explode("-",$fecha_pago);
$fecha_pago="$d/$m/$a";
}
?>
<form action=<?=$SCRIPT_NAME?> method=post>
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="tarea" value="grabar">
<table class=tabla border=1 align=Center>
<tr><td><LABEL for="forma" accesskey="F">
<u>F</u>orma de pago : 
</LABEL>
</td><td><input type=text size=20 name=forma_de_pago id=forma value="<?=$forma_de_pago?>">
</tr>
<tr><td>
<LABEL for="pago" accesskey="P">
Fecha de <u>P</u>ago:
</LABEL>
<script language='javascript' src='calendar/popcalendar.js'></script>
<script language="JavaScript" type="text/javascript">
<!--
addHoliday(25,12,0, "Christmas Day")

//-->
</script>


 </td><td>
 <input type=text size=20 name=fecha_pago id=pago value="<?=$fecha_pago?>" onclick='popUpCalendar(this, this, "d/m/yyyy")'></tr>
<tr><td colspan="2"><input type="submit" value="enviar" ></td></tr>
</table>



</form>


<?



}

function grabar(){
GLOBAL $HTTP_POST_VARS,$conn;
extract($HTTP_POST_VARS);
list($d,$m,$a)=explode("/",$fecha_pago);
$fecha_pago="$a/$m/$d";
$sql="update liquidaciones set pagado='S',fecha_pago='$fecha_pago',forma_pago='$forma_de_pago' where id=$id";
$rs=$conn->execute($sql); 
if ($rs===false ){
	 mostrar_error("Error al grabar los datos de la liquidacion".mysql_error());
}
else {
		 mostrar_error("liquidacion modificada con exito");	 
}
}


if (!isset($tarea) or $tarea=="") $tarea="ver_todos";


switch ($tarea) {
case "buscar":
    consulta("='$buscar'",$campo);
		break;
 case "consulta":
    consulta(stripslashes($buscar),$campo,$orden);
    break;
case "modifica":
    modificar($id);
     break;
case "grabar":
     grabar();
		 break;		 
   default:
      ver_todos();
      break;
       }
?>
</body></HTML>
