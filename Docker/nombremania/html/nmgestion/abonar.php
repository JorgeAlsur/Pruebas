    <HTML>

<HEAD><TITLE>ABONO de facturas y operaciones - administracion nombremania</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

</HEAD>


<?
echo "ID = $id";
include "barra.php";

include "basededatos.php";
$rs=$conn->execute("select * from operaciones where id = $id"); 

?>
<h4 align="center">DEVOLUCION DE OPERACIONES</h4>
   <table align=center class=tabla >
   <tr>
   <td bgcolor=#cocoff>
   Nombre:
   </td>
<td><?echo htmlentities($rs->fields["owner_last_name"].", ".$rs->fields["owner_first_name"]) ;?></td>

   </tr>

   <tr>

   <td bgcolor=#cocoff>

   Dominio/s registrado/s:

   </td>

<td><?=$rs->fields["domain"];?></td>

   </tr>

   <td bgcolor=#cocoff>

   Tel&eacute;fono:

   </td>

<td><?=$rs->fields["owner_phone"];?></td>

   </tr>
   <tr>
  <td bgcolor=#cocoff>
   Tipo de registro (pro o nada):
   </td>
    <td><?=$rs->fields["tipo"];?> </td>
   </tr>
<tr>
   <td bgcolor=#cocoff>
   Codigo de aprobacion del pago:
   </td>
    <td> <?=$rs->fields["cod_aprob"];?></td>
   </tr>
<tr>
   <td bgcolor=#cocoff>
   Codigo de aprobacion OPENSRS:
   </td>
   <td><?=$rs->fields["codigo_opensrs"];?></td>
   </tr>
<tr>
   <td bgcolor=#cocoff>
   Cantidad de A&ntilde;os:
   </td>
<td><?=$rs->fields["period"];?></td>
   </tr>
<tr>
   <td bgcolor=#cocoff>
   Precio total Abonado:
   </td>
<td><?=$rs->fields["precio"];?></td>

   </tr>

<tr>
   <td bgcolor=#cocoff>
   N&deg; Solicitud:
  </td>
<td><a  href=ficha.php?id=<?=$rs->fields["id_solicitud"];?>><?=$rs->fields["id_solicitud"];?> </a>
</td>
   </tr>
  <tr>
   <td bgcolor=#cocoff>
   email:</td>
   <td>
   <a href=mailto:<?=$rs->fields["owner_email"]?>><?=$rs->fields["owner_email"]?></a>

   </td>

   </tr>

   <tr>
	 <td bgcolor=#cocoff>NOTAS:</td>
	 <td><?=$rs->fields["notas"]?></td>
	 </tr>

   </table>
  <br>

<?
$notas= $rs->fields["notas"];
$id_solicitud= $rs->fields["id_solicitud"];

echo "<form action=abonar2.php method=post>";
echo "<input type=hidden name=id_solicitud value=$id_solicitud>
<input type=hidden name=id value=$id>

";

$rs= $conn->execute("select * from dominios where id_operacion=$id");
if ($conn->affected_rows()>0){
echo "<h5 align=center>Dominios </h5>";
echo "<table align=Center bgcolor=#cocoff>
<tr><td>Dominio</tD><td>Precio</tD><td>Tipo</td><td>marcar</tD>
 "; 
while (!$rs->EOF){
echo "<tr><td> {$rs->fields["dominio"]}</td><td>{$rs->fields["precio"]}</td>
<td>{$rs->fields["tipo"]}</td>
		 <td><input type=checkbox name=dominios[] value={$rs->fields["id"]}></td>
</tr>";
$rs->movenext();
}
echo "</table><br>";
}

$rs= $conn->execute("select * from zonas where id_operacion=$id");
if ($conn->affected_rows()>0){
echo "<h5 align=center>ZONAS</h5>";
echo "<table align=Center bgcolor=#cocoff>
<tr><td>Dominio</tD><td>Precio</tD><td>Tipo</td><td>marcar</tD>
 "; 
while (!$rs->EOF){
echo "<tr><td> {$rs->fields["dominio"]}</td><td>{$rs->fields["precio"]}</td>
<td>{$rs->fields["tipo"]}</td>
		 <td><input type=checkbox name=zonas[] value={$rs->fields["id"]}></td>
</tr>";
$rs->movenext();
}
echo "</table><br>";
}
echo "<center>NOTAS : <textarea name=notas cols=70 rows=5 >$notas</textarea></center>";

$rs= $conn->execute("select * from facturas  where id_solicitud=$id_solicitud");
if ($conn->affected_rows()>0){
	 echo "<H5 ALIGN=CENTER>EXISTE FACTURA  : GENERAMOS FACTURA EN NEGATIVO POR EL IMPORTE CORRESPONDIENTE</H5>";
echo "	 <input type=hidden name=factura value=1><input type=hidden name=numero_factura value={$rs->fields["id"]}>";
	 }
echo  "<input type=submit ></form>";
?>