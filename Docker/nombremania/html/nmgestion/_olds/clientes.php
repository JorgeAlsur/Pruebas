<?
/*
alta de clientes via administracion en nombremania
archivo relacionado
*************************
formcliente.inc.php   easytemplate
********************
*/
?>
<html><head>
<link rel="stylesheet" type="text/css" href="estilo.css">
</head><body>
<?
include "barra.php"; include "basededatos.php"; include "hachelib.php";
?>
  <form action=<?=$PHP_SELF?> method=get>
 <table width="700" border="0" cellspacing="0" cellpadding="2" align="center">
   <tr>
     <td width="210" bgcolor="#000033" align="center">
       <div align="center"><font color="#FFFFFF"><b><font size="2">CLIENTES</font></b></font></div>
     </td>
     <td width="8" align="right"><font size="2"> </font></td>
     <td colspan="2" align="left" bgcolor="#666666" width="470"><b><font color="#FFFFFF" size="1">Buscar:
       <input type=text size=20 name=buscar>
       </font></b></td>
   </tr>
   <tr>
     <td width="210" height="5">&nbsp;</td>
     <td width="8" height="5">
       <div align="right"><font size="1"></font></div>
     </td>
     <td colspan="2" valign="middle" align="left" height="5" bgcolor="#999999" width="470"><font size="2">
       <input type=radio name=campo value=nombre checked>
       </font><font size="1">Por Nombre&nbsp;&nbsp;
       <input type=radio name=campo value=id_interno>
       Por ID. Interno&nbsp;&nbsp;
       <input type=radio name=campo value=id>
       Por ID.</font></td>
   </tr>
   <tr>
     <td width="210" height="5">&nbsp;</td>
     <td width="8" height="5">&nbsp;</td>
     <td colspan="2" valign="middle" align="left" height="5" bgcolor="#FFFF66" width="470"><font size="2"><a href="clientes.php?tarea=alta"><font size="1">Alta
       Manual</font></a><font size="1"> | <a href="clientes.php?tarea=inactivos">Consulta
       inactivos/nuevos</a> | <a href="clientes.php?tarea=ver_todos">Ver todos
       los clientes</a> | 	<a href="clientesdistintos.php">Listado de clientes distintos (operaciones)</a>
			 </font></font> </td>
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

function consulta($buscar="",$campo,$orden="") {
GLOBAL $conn,$PHP_SELF,$REQUEST_URI;
$campos="id,usuario,nombre,email, CONCAT('<A HREF=clientes.php?tarea=modifica&id=',id,'>modifica</a>')  as tarea  ,
cONCAT('<A HREF=cuentas.php?tarea=consulta&id_cliente=',id,'>Cta.Cte.</a>')  as CtaCte ,
cONCAT('<A HREF=cuentas.php?tarea=consulta&id_cliente=',id,'&nombre=&tarea=alta>Ingreso</a>')  as operaciones";
$campo_tit=array("<a href=$REQUEST_URI&orden=id>ID</a>",
"<a href=$REQUEST_URI&orden=usuario>Usuario</a>","<a href=$REQUEST_URI&orden=nombre>Nombre</a>","EMAIL","TAREA","CTA cte",
"OPERACIONES");
if ($campo!="" and $buscar!="" ){
$sql="select $campos from  clientes where ";
if ($campo=="id"){
$sql.= " id=$buscar";
}
elseif ($campo=="nombre") {
$sql.= " nombre like \"%$buscar%\"";
}
elseif ($campo=="id_interno"){
$sql.= " id_interno like \"%$buscar%\"";
}
else {
 $sql="select $campos from  clientes " ;

}
if ($orden !="") $sql.=" order by $orden";
$rs=$conn->execute($sql);
rs2html($rs,"class=tabla border=1 align=Center",$campo_tit);
return;
}

}
function modifica($id) {
gLOBAL $conn,$PHP_SELF;
$sql="select * from clientes where id=$id";
$rs=$conn->execute($sql);
if (!$rs) return false;
print "Modificando $id";
include "EasyTemplate.inc.php";

$template = new EasyTemplate("formcliente.inc.php");
$campos=array("nombre","clave","telefono","direccion","email","id_interno","usuario","cp","fax","poblacion","dni_nif","contacto","provincia","pais");
for($i=0;$i<=count($campos);$i++){
   $template->assign($campos[$i],$rs->fields[$campos[$i]]);
}
$template->assign("id",$id);
if ($rs->fields["activo"]==1){
   $template->assign("checked_si","SELECTED");
   $template->assign("checked_no","");
}
else {
   $template->assign("checked_no","SELECTED");
   $template->assign("checked_si","");
}
// Assign template values
$template->assign("HEADER", "modificacion de clientes");
$template->assign("tarea", "actualiza");
$template->assign("ACTION", $GLOBALS["PHP_SELF"]);
$template->easy_print() or die($template->error);
echo "<center><a href=cuentas.php?id_cliente=$id>Manejo de Cuenta Corriente</a>
</center>";
exit();
}


function actualiza($id="",$form) {
global $conn;
if ($id=="")
   return false;
$sql=updatedb("clientes",$form,"where id=$id");
$rs=$conn->execute($sql);
echo "<p class=alerta>Registro Modificado</p>";
return true;
}

function formulario() {
include "EasyTemplate.inc.php";
$template = new EasyTemplate("formcliente.inc.php");
$campos=array("nombre","clave","telefono","direccion","email","id_interno","usuario","cp","fax",
"poblacion","dni_nif","contacto","provincia","pais");
// Assign template values
$template->assign("HEADER", "Alta de clientes");
$template->assign("tarea", "graba-alta");
$template->assign("ACTION", $GLOBALS["PHP_SELF"]);
for($i=0;$i<=count($campos);$i++){
$template->assign($campos[$i],"");
$template->easy_print() or die($template->error);
exit();
return;
}
}

function graba($form){
global $conn;
$sql=insertdb("clientes",$form);
$rs=$conn->execute($sql);
if ($rs===false){
   echo "error en el ingreso <br>".$conn->ErrorMsg();
}
else{
echo "<p class=alerta>Cliente Ingresado</p>";
}
}
function inactivos() {
global $conn;
$sql="select id,usuario,email, CONCAT('<A HREF=clientes.php?tarea=modifica&id=',id,'>modifica</a>')  as tarea
from  clientes where activo = 0 ";
$rs=$conn->execute($sql);
rs2html($rs,"class=tabla border=1 align=Center");
return;
}


function ver_todos() {
global $conn,$REQUEST_URI;
$campos="id,usuario,nombre,email, CONCAT('<A HREF=clientes.php?tarea=modifica&id=',id,'>modifica</a>')  as tarea  ,
cONCAT('<A HREF=cuentas.php?tarea=consulta&id_cliente=',id,'>Cta.Cte.</a>')  as CtaCte ,
cONCAT('<A HREF=cuentas.php?tarea=consulta&id_cliente=',id,'&nombre=&tarea=alta>Ingreso</a>')  as operaciones";
$campo_tit=array("<a href=$REQUEST_URI&orden=id>ID</a>",
"<a href=$REQUEST_URI&orden=usuario>Usuario</a>","<a href=$REQUEST_URI&orden=nombre>Nombre</a>","EMAIL","TAREA","CTA cte",
"OPERACIONES");

$sql="select $campos
from  clientes " ;
$rs=$conn->execute($sql);
rs2html($rs,"class=tabla border=1 align=Center",$campo_tit);
return;
}

if (!isset($tarea) or $tarea=="") $tarea="ver_todos";

switch ($tarea) {
 case "consulta":
    consulta($buscar,$campo,$orden);
    break;
  case "alta":
    formulario();
    break;
  case "graba-alta":
    graba($form);
    break;
  case "modifica":
      modifica($id);
   break;
  case "actualiza":
       actualiza($id,$form);
       break;
   case "inactivos":
     inactivos();
      break;
   case "ver_todos":
      ver_todos();
      break;
       }
?>
</body></HTML>
