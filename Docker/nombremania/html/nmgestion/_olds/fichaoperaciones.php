    <HTML>

<HEAD><TITLE>ficha de opcione de pago Nombremania -administracion interna</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

</HEAD>



<body bgcolor=#fffccc>

<?

   include "barra.php";

   include "basededatos.php";

   $conn->debug=0;

   if (!isset($id) or $id=="" ){

   echo "falta id";

   exit;

   }



   if (isset($borrar)){

         $sql="delete from operaciones where id=$id";

         $rs=$conn->execute($sql);

         echo "<p class=alerta>registro borrado</a>";

   exit;

   }

   echo "<a href=$PHP_SELF?id=$id>Volver a la Ficha</a>";

   if (isset($opensrs)){

       include "procesos.php";

       $salida=alta_opensrs($id);

       if (!$salida["retorno"]){

        echo "<h3 align=Center>FALLO en Llamada a OPEN-SRS</h3>";

       }

       else {

        echo "<h3 align=Center>Resultado de la llamada a OPEN-SRS</h3>";

        print "<hr><pre>".$salida["mensaje"]."</pre><hr>";

       }

         if ($tipo=="PRO"){

             echo "<font size=+2 color=red>No olvide registrar zona</font>";

             echo "<a href=\"$PHP_SELF?zoneedit=si&id=$id\">---Alta en Zoneedit---</a>";

              }

         exit;

   }

   if (isset($modificar)){

       $tipo=strtoupper($tipo);

       $cod_aprob=strtoupper($cod_aprob);

       $sql="update operaciones set cod_aprob=\"$cod_aprob\", tipo=\"$tipo\" where id=$id";

       $rs=$conn->execute($sql);

       echo "<h4>Registro modificado</h4>";

   }



   if (isset($volcado)){

      $sql="select * from operaciones where id=$id";

      $rs=$conn->execute($sql);

      print "<table  class=tabla align=Center>";

       $ncols = $rs->FieldCount();

        for ($i=0; $i < $ncols; $i++){

         echo "<tr><td bgcolor=#cocoff>";

         $field = $rs->FetchField($i);

         print $field->name;

         echo "</td>";

         echo "<td>".$rs->fields[$i];

         echo "</td> </tr>";

        }

      print "</table>";

      exit;

   }

 if (isset($whois)){

               include("whois/main.whois");

               $whois = new Whois($fqdn);

               $result = $whois->Lookup();

               echo "<b>Resultado de  $fqdn :</b><p>";

               if(isSet($whois->Query["errstr"])) {

               echo "<b>Errores:</b><br>".implode($whois->Query["errstr"],"<br>");

               }

               if($output=="object") {

                       include("whois/utils.whois");

                       $utils = new utils;

                       $utils->showObject($result);

               } else {

                       if(!empty($result["rawdata"])) {

                               echo implode("<BR>\n",$result["rawdata"]);

                       } else { echo "<br>nada"; }

               }

}



   $sql="select * from operaciones where id=$id";

   $rs=$conn->execute($sql);

   if ($rs===false) {

   echo "<p class=alerta> No existe la ficha con ID = $id</p>";

   exit();

   }

   ?>
  <table width="700" border="0" align="center">

    <tr>

      <td colspan="3" bgcolor="#CC0000"><b><font color="#FFFFFF" size="2">ACCIONES

        MANUALES</font></b></td>

    </tr>

    <tr align="center">

      <td width="25%" bgcolor="#CCCCCC"><b>Ver
  whois</b>
<?
$doms=split("\*\*",$rs->fields["domain"]);
while (list($l,$dom)=each($doms))
echo "<br><a href=\"$PHP_SELF?whois=1&id=$id&fqdn=$dom\">$dom</a>";
?>

</td>

      <td width="25%" height="25" bgcolor="#CCCCCC"><b><a href="<?=$PHP_SELF?>?borrar=si&id=<?=$id?>">Borrar</a></b></td>

      <td width="25%" height="25" bgcolor="#CCCCCC"><b><a href="abonar.php?id=<?=$id?>">Abonar</a></b></td>
    </tr>

    <tr valign="middle">

      <td width="50%" bgcolor="#FFFFFF">

        <div align="center"><font size="1">Ver ficha integra</font></div>

      </td>

      <td width="50%" bgcolor="#FFFFFF">

        <div align="center"> <font size="1">Eliminar por completo este r</font><font size="1">egistro</font>

        </div>

      </td>
      <td width="50%" bgcolor="#FFFFFF">

        <div align="center"> <font size="1">Anular la operacion (facturas, ctacte etc)</font><font size="1"></font>

        </div>

      </td>

    </tr>

    <tr valign="middle">

      <td width="50%">&nbsp;</td>

      <td width="50%">&nbsp;</td>

    </tr>

</table>

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
if (strtolower($rs->fields["tipo"])<>"estandar"){

  $sqlz="select date_format(from_unixtime(fecha),\"%d-%m-%Y\") as fecha, dominio, period, emails, redirecciones, precio  from zonas where id_operacion=$id";
  $zona=$conn->execute($sqlz);
echo "<h3>Zonas contratadas</a>";
if ($zona===false or $conn->affected_rows()==0){
    echo "sin zonas contratadas";
}
else{
     rs2html($zona,"class=tabla align=center");
}
}
$sqld="select * from dominios where id_operacion=$id";
$domins=$conn->execute($sqld);
echo "<h3>Dominios contratados</a>";
if ($domins===false or $conn->affected_rows()==0){
    echo "sin dominios contratados";
}
else{

rs2html($domins,"class=tabla align=center");

}
      $sql="select * from operaciones where id=$id";

      $rs=$conn->execute($sql);

      print "<br><table  class=tabla align=Center>";

       $ncols = $rs->FieldCount();

        for ($i=0; $i < $ncols; $i++){

         echo "<tr><td bgcolor=#cocoff>";

         $field = $rs->FetchField($i);

         print $field->name;

         echo "</td>";

         echo "<td>".$rs->fields[$i];

         echo "</td> </tr>";

        }

      print "</table>";
?>



