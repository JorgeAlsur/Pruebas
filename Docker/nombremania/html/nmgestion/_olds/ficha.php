<HTML>

<HEAD><TITLE>ficha de opcione de pago Nombremania -administracion interna</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

</HEAD>

<body bgcolor=#fffccc>

<?

  include "barra.php";
include "hachelib.php";
  ?>

  <H3 align="CENTER">Dominios solicitados</H3>

   <?

   include "basededatos.php";

  $conn->debug=0;

  if (!isset($id) or $id=="" ){

  echo "<h3 align=center>Falta id</h3>";

  exit;

  }

   if (isset($borrar)){

        $sql="delete from solicitados where id=$id";

        $rs=$conn->execute($sql);

        echo "<p class=alerta>registro borrado</a>";

  exit;

  }

  echo "<a href=$PHP_SELF?id=$id>Volver a la Ficha</a>";

  if (isset($opensrs)){

      include "procesator.php";

      if (!isset($id_manual) or $id_manual==""){

      echo "<br>Falta el detalle de la operacion. <br>";

      exit;

       }

      else {
           /*
           grabo datos en la cuenta de manuales
           */
       $conn->debug=0;
       $sql="select domain,nm_preciototal from solicitados where id=$id";
       $rs_dominios=$conn->execute($sql);

     $datos=array();
     $datos["id_cliente"]=2;
     $datos["tipo"]="d";
     $datos["importe"]=$rs_dominios->fields["nm_preciototal"]*-1;
     $datos["observacion"]= str_replace("**",", ",$rs_dominios->fields["dominios"]);
     $datos["fecha"]=date("Y-m-d");
     $insertar=insertdb("transacciones",$datos);
     $rs=$conn->execute($insertar);

if (pagator("manual",$id,$id_manual)){

         echo "<br><br>Agregado a la cola de procesamiento con ID= nm-$id_manual" ;

      exit;

       }

      else {

          echo "<br><br>Error en la cola de procesamiento, no se agregó";

           }

        exit;

  }

 }

   if (isset($volcado)){

     $sql="select * from solicitados where id=$id";

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

  $sql="select * from solicitados where id=$id";

  $rs=$conn->execute($sql);

  if ($rs===false or $conn->affected_rows()<1) {

  echo "<p class=alerta> No existe la ficha con ID = $id</p>";

  exit();

  }
 if($conn->affected_rows()<1){
       echo "<h3 align=center>ERROR numero de solicitud no enncontrado $id </h3>"   ;
       exit;
       }

  ?>

<table width="700" border="0" align="center">
  <tr>
    <td colspan="3" bgcolor="#CC0000"><b><font color="#FFFFFF" size="2">ACCIONES
      MANUALES</font></b></td>
  </tr>
  <tr align="center">
    <td width="25%" bgcolor="#CCCCCC"> <b>Enviar a la cola de proceso</b>
      <form action=<?=$PHP_SELF?> method=post>
        <input type=text size=20 name=id_manual>
        <input type=hidden name=opensrs value=1>
        <input type=hidden name=id value=<?=$id?>>
        <input type=submit value=enviar>
      </form>
    </td>
    <td width="25%" bgcolor="#CCCCCC" class=tabla>WHOIS <BR>
<?
$doms=split("\*\*",$rs->fields["domain"]);
while (list($l,$dom)=each($doms))
echo "<br><a href=\"$PHP_SELF?whois=1&id=$id&fqdn=$dom\">$dom</a>";
?></td>
            <td width="25%" height="25" bgcolor="#CCCCCC"><b> <a href="<?=$PHP_SELF?>?borrar=si&id=<?=$id?> ">Borrar</a></b></td>
  </tr>
  <tr valign="middle">
    <td width="25%" bgcolor="#FFFFFF">
      <div align="center"><font size="1">Registrar&aacute; el dominio ignorando
        las opciones de pago</font></div>
    </td>
    <td width="25%" bgcolor="#FFFFFF">
      <div align="center"><font size="1">realiza un whois (para traslados)</font></div>
    </td>
    <td width="25%" bgcolor="#FFFFFF">
      <div align="center"> <font size="1">Eliminar por completo este r</font><font size="1">egistro</font>
      </div>
    </td>
  </tr>
</table>
 <br>

  <table align=center class=tabla width="700" >
   <tr>
     <td bgcolor=#000066>
      <div align="right"><font color="#FFFFFF"> Nombre: </font></div>
     </td>
     <td bgcolor="#CCCCFF"><?=$rs->fields["reg_username"];?></td>
   </tr>
   <tr>
     <td bgcolor=#000066>
       <div align="right"><font color="#FFFFFF"> Dominio/s registrado/s: </font></div>
     </td>
     <td bgcolor="#CCCCFF"><?=$rs->fields["domain"];?></td>
   </tr>
   <td bgcolor=#000066>
     <div align="right"><font color="#FFFFFF"> Tel&eacute;fono: </font></div>
   </td>
   <td bgcolor="#CCCCFF"><?=$rs->fields["owner_phone"];?></td>
   </tr>
   <tr>
     <td bgcolor=#000066>
       <div align="right"><font color="#FFFFFF"> Tipo de registro </font></div>
     </td>
     <td bgcolor="#CCCCFF">

       <font color="#FFFFFF"> <font color="#000000"><?=$rs->fields["tipo"]?></font></font>
     </td>
   </tr>
   <tr>
     <td bgcolor=#000066>
       <div align="right"><font color="#FFFFFF"> Codigo de aprobacion banco:
         </font></div>
     </td>
     <td bgcolor="#CCCCFF">
       <?=$rs->fields["cod_aprob"];?>
       ( vacio si el pago no se ha realizado)</td>
   </tr>
   <tr>
     <td bgcolor=#000066>
       <div align="right"><font color="#FFFFFF"> email:</font></div>
     </td>
     <td bgcolor="#CCCCFF"><a href=mailto:<?=$rs->fields["owner_email"]?>><?=$rs->fields["owner_email"]?></a>
     </td>
   </tr>
 </table>

 <br>


<br>






<?
     $sql="select * from solicitados where id=$id";

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

?>
