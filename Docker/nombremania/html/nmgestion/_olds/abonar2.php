<HEAD><TITLE>ABONO de facturas y operaciones -  PASO 2 - administracion nombremania</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

</HEAD>


<?
echo "ID = $id";
include "barra.php";

include "basededatos.php";
$rs=$conn->execute("select * from operaciones where id = $id"); 

?>
<h4 align="center">DEVOLUCION DE OPERACIONES - PASO 2</h4>
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
<tr>
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
$id_solicitud= $rs->fields["id_solicitud"];
echo "<form action=abonar_final.php method=post>";
echo "<input type=hidden name=id_solicitud value=$id_solicitud>

<input type=hidden name=id value=$id>";
$anul_dominios= $conn->execute("select * from dominios where id in (".join(",",$dominios).")" );
$total_anulacion = 0 ;
if (isset($dominios) and is_array($dominios)){
if ($conn->affected_rows()>0){
echo "<h4 align=Center>Dominios a anular</h4>";
echo "<table align=center > <tr><td>DOMINIO</td><td>IMPORTE</td></tr>";
$sdominios="";
while (!$anul_dominios->EOF){
			echo "<td>{$anul_dominios->fields["dominio"]}</td>
			<td>{$anul_dominios->fields["precio"]}</td>
			</tr>";
			$total_anulacion += $anul_dominios->fields["precio"];
			$sdominios.= " ".$anul_dominios->fields["dominio"];
			echo "<input type=hidden name=dominios[] value={$anul_dominios->fields["id"]}>";

$anul_dominios->movenext();
}

echo "<tr><td colspan=2>Total $total_anulacion</td></tr></table>";
}
}
if (isset($zonas) and is_array($zonas)){

$anul_zonas= $conn->execute("select * from zonas where id in (".join(",",$zonas).")" );

if ($conn->affected_rows()>0){
echo "<h4 align=Center>zonas a anular</h4>";
echo "<table align=center > <tr><td>DOMINIO</td><td>IMPORTE</td></tr>";
while (!$anul_zonas->EOF){
			echo "<td>{$anul_zonas->fields["dominio"]}</td>
			<td>{$anul_zonas->fields["precio"]}</td>
			</tr>";
			$total_anulacion += $anul_zonas->fields["precio"];
			echo "<input type=hidden name=zonas[] value={$anul_zonas->fields["id"]}>";			
$anul_zonas->movenext();
}


echo "<tr><td colspan=2>Total $total_anulacion</td></tr></table>";
}
}

if (isset($factura) and $factura==1){
	 			echo "<hr><p align=center>Generar la factura negativa por el importe de $total_anulacion ? 
				SI <input type=radio name=factura value=1 checked > NO <input type=radio name=factura value=0></p>";						
}
echo "<center>Concepto FACTURA:<br><textarea name=concepto cols=60 rows=5 >Anulacion de compra dominios, $sdominios ref: $numero_factura</textarea> ";

echo "<input type=hidden name=numero_factura value=$numero_factura>\n";
echo "<input type=hidden name=importe value=$total_anulacion>";
 
echo "<br><input type=submit ></form></center>";


include "hachelib.php";

?>
