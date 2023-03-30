<html><head>
<link rel="stylesheet" type="text/css" href="estilo.css">
</head><body>
<?
include "barra.php";
?>
<h3 >TRANSACCIONES</h3>
<FORM  action="<?=$PHP_SELF;?>" method="POST" name="busqueda">
  <table width="700" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr>
      <td align="center" bgcolor="#CCCCCC"><font size="1"><b>Buscar :</b></font>
        <select  name="que" size="1">
          <option value=ultimos >Los Ultimos </option>
          <option value="id">ID</option>
          <option value="fecha">fecha</option>
        </select>
        <input type="HIDDEN"  name="tarea" value="consulta">
        <input type="TEXT"  name="buscar" size="20">
        <input type=submit value=mostrar name="submit">
      </td>
    </tr>
  </table>
  </FORM>
<?
include "basededatos.php"; include "hachelib.php";
/*
transacciones archivo relacionado trans.html
**************************
formcliente.inc.php   easytemplate
*********************
*/

if ($tarea=="impresion"){
$sql="select * from clientes where id=$id_cliente";
$rs=$conn->execute("$sql");
rs2html($rs, "width=100% class=tabla align=center");
$sql= "select id,fecha,tipo,importe,observacion from transacciones where id_cliente=$id_cliente";
$rs1=$conn->execute("$sql");
rs2html($rs1, "width=740     border=1 class=tabla align=center");
exit;
}

function consulta2($buscar="",$que,$orden="t.fecha") {
Global $conn,$PHP_SELF;
//$conn->debug=1;
if ($que == "ultimos") {
if ($buscar==0) $buscar=1;
echo "<center><h4> últimos $buscar</h4></center>";
$sql="select t.id, date_format(t.fecha,\"%d-%m%Y\") ,CONCAT('<A HREF=clientes.php?tarea=modifica&id=',c.id,'>',c.usuario,'</a>') as cliente,t.importe,t.observacion,
from transacciones t,clientes c where t.id_cliente=c.id order by t.$orden DESC Limit 0,$buscar";
$rs1=$conn->execute("$sql");
     rs2html($rs1, "width=95% class=tabla align=center");
 }
elseif ($que=="fecha"){
$buscar="'".fecha2mysql($buscar)."'";
echo "<center><h4>Por fecha  igual a $buscar</h4></center>";
$sql="select CONCAT('<A HREF=trans.php?tarea=modifica&id=',t.id,'>modifica</a>')  as tarea ,t.fecha ,
c.usuario,t.importe,t.observacion from transacciones t,clientes c where t.id_cliente=c.id and fecha = $buscar order by t.fecha DESC ";
$rs1=$conn->execute("$sql");
rs2html($rs1, "width=95% class=tabla align=center");
}
elseif ($que=="id") {
echo "<center><h4>Por ID   igual a $buscar</h4></center>";
$sql="select CONCAT('<A HREF=trans.php?tarea=modifica&id=',t.id,'>modifica</a>')  as tarea ,t.fecha ,c.usuario,t.importe,t.observacion from transacciones t,clientes c where t.id_cliente=c.id and t.id = $buscar  ";
$rs1=$conn->execute("$sql");
rs2html($rs1, "width=95% class=tabla align=center");
}

return;

}

function consulta($buscar="",$que,$orden="t.fecha desc") {
Global $conn,$PHP_SELF,$REQUEST_URI;
if (!ereg("&",$REQUEST_URI)) {
   $REQUEST_URI.="?buscar=\"\"";
}

$campos_tit=array("id","<A HREF={$REQUEST_URI}&orden=t.fecha+desc>FECHA</a> <A HREF={$REQUEST_URI}&orden=t.fecha+asc>Asc</a>","cliente",
"importe","observacion");
$campos="t.id, date_format(t.fecha,\"%d-%m-%Y\") as fecha ,CONCAT('<A HREF=clientes.php?tarea=modifica&id=',c.id,'>',c.usuario,'</a>') as cliente,t.importe,t.observacion ";
//$conn->debug=true;
if ($que == "ultimos") {
if ($buscar==0) $buscar=1;
echo "<center><h4> últimos $buscar</h4></center>";
$sql="where t.id_cliente=c.id " ;
$sqllimit="Limit 0,$buscar ";

 }
elseif ($que=="fecha"){
$buscar="'".fecha2mysql($buscar)."'";
echo "<center><h4>Por fecha  igual a $buscar</h4></center>";
$sql="where t.id_cliente=c.id and fecha = $buscar  ";
}
elseif ($que=="id") {
echo "<center><h4>Por ID   igual a $buscar</h4></center>";
$sql="where t.id_cliente=c.id and t.id = $buscar  ";
}
$sql="select $campos from transacciones t,clientes  c  $sql order by $orden $sqllimit" ;
$rs1=$conn->execute("$sql");

rs2html($rs1, "width=95% class=tabla align=center",$campos_tit,false);
return;

}


function formulario($id) {
Global $conn;

$rs1=$conn->execute("select  t.id, date_format(t.fecha,\"%d-%m-%Y\") as fecha ,c.nombre,t.importe,t.observacion from transacciones t,clientes c where t.id_cliente=c.id and  t.id=$id");
if ($rs1===false) {
print $conn->MsgError();
}

$nombre=$rs1->fields("nombre");

include "EasyTemplate.inc.php";
$template = new EasyTemplate("formtrans.inc.php");

$campos=array("importe","fecha","observacion");
// Assign template values
$template->assign("tarea", "graba");
$template->assign("nombre", $nombre);
$template->assign("id", $id);
$template->assign("HEADER", "Modificacion transacciones");
$template->assign("action", $_SERVER['PHP_SELF']);

    for($i=0;$i<=count($campos);$i++){
         $template->assign($campos[$i],$rs1->fields($campos[$i]));

         }


$template->easy_print() or die($template->error);
exit();
return;

}


function graba($form) {
Global $conn,$id;
//validacion
include "clientes.inc.php";
include "String_Validation.inc.php";
$errores=array();
if (!is_numeric($form["importe"]))    $errores[]="Importe no es numerico";

if (!is_date($form["fecha"])) $errores[]="Fecha no válida";

if ($form[tipo]=="debito") $form["importe"]=$form["importe"]*-1 ;

if (count($errores)>0){
    print "<p class=alerta>".join("<br>", $errores)."</p>";
    return false;

}
$form["fecha"]=fecha2mysql($form["fecha"]);

$sql=updatedb("transacciones",$form," where id=$id");
//$conn->debug=1;
$rs=$conn->execute($sql);
if ($rs===false ){
    echo "error en la actualizacion";
}
else {
    echo "actualizacion realizada con éxito";
}
return;
}

function barratrans($id_cliente){
Global $conn;
//$rs=$conn->execute("select * from transacciones order by fecha DESC LIMIT 0, 30");

//rs2html($rs,"class=tabla border=1 align=Center");
}

if (!isset($tarea) or $tarea=="") $tarea="consulta";

switch ($tarea) {
   case "consulta":
     barratrans($id);
      consulta($buscar,$que);
     break;
   case "alta":
  //    barra($id_cliente);
      formulario($id_cliente,$nombre);
     break;
   case "graba":
     graba($form,$id);
//     barra($form["id_cliente"]);
     break;
   case "modifica":
        formulario($id);
        break;
}

?>
</body>
