<HTML>

<HEAD><TITLE>ficha de opcione de pago Nombremania -administracion interna</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

<style type="text/css">
form#zona_change input, form#zona_change select {
	width: 300px;
	font-size:1.2em;

}
form#zona_change label {

}
</style>
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
if(isset($_REQUEST['grabarficha']))
{
	$sql = "select * from solicitados where id = ".$_REQUEST['id']; 
	$rs = $conn->execute($sql) ; 
	if ($rs===false) die("la ficha no existe") ; 
	$sql= $conn->GetUpdateSQL($rs ,$_REQUEST, true, get_magic_quotes_gpc());
	if ($sql!="")
	{
		$rs2 = $conn->execute($sql ) ; 
		if ($rs2===false) die("Error al grabar la ficha ") ; 
	}
} 


	if(!isset($id) or $id=='')
	{
		echo "<h3 align=center>Falta id</h3>";
		exit;
	}

	if (isset($borrar))
	{
		$sql="delete from solicitados where id=$id";
		$rs=$conn->execute($sql);
		echo "<p class=alerta>registro borrado</a>";
		exit;
	}
	
	if(isset($zona_change)){
		$sql = "update solicitados set domain='$id_zona_ch', reg_type='$reg_type', nm_registro_tipo='$nm_registro_tipo', vencimiento='$vencimiento' where id=$id";
		$rs = $conn->execute($sql);
		echo "<p class=alerta>Actualizado el registro</a>";
		exit;
	}
	
	
	if (isset($duplicar))
	{
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$sql="select * from solicitados where id=$id";
		$rs=$conn->execute($sql);
		if($rs->_numOfRows > 0){
			$sql_u="select * from solicitados where id = -1";
			$rs_u=$conn->execute($sql_u);
			unset($rs->fields['id']);
			$sql_d = $conn->getinsertsql($rs_u,$rs->fields);
			$rs_d = $conn->execute($sql_d);
			if($rs_d===false ){
				echo "<p class=alerta>registro duplicado</a>";
			}
			else {
				$id_nuevo=$conn->insert_ID();
				echo "<p class=alerta>registro duplicado. Nuevo id $id_nuevo</a>";
			}
			exit;
		}
		
	}

	echo "<a href=$PHP_SELF?id=$id>Volver a la Ficha</a>";

	if (isset($opensrs))
	{
		include "procesator.php";

		if(isset($_POST['expired']))
		{
			if (!isset($id_manual) or $id_manual=='')
			{
				echo "<br/>Debe cambiar el nombre al dominio. <br/>";
				exit;
			}
			$expired=true;
			$sql="update solicitados set domain='$id_manual' where id=$id;";
			$conn->execute($sql);
		}
		if (!isset($id_manual) or $id_manual=="")
		{
			echo "<br>Falta el detalle de la operacion. <br>";
			exit;
		}
		else
		{
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

			if($expired)$tmp=pagator("manual",$id,$id_manual,true);
			else $tmp=pagator("manual",$id,$id_manual);
			if($tmp)
			{
				echo "<br><br>Agregado a la cola de procesamiento con ID= nm-$id_manual" ;
				exit;
			}
			else
			{
				echo "<br><br>Error en la cola de procesamiento, no se agrego" ; 
			}
			exit;
		}
	}

   if (isset($volcado))
   {

		$sql="select * from solicitados where id=$id";

		$rs=$conn->execute($sql);

		print "<table  class=tabla align=Center>";

		$ncols = $rs->FieldCount();

		for ($i=0; $i < $ncols; $i++)
		{

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

	if (isset($whois))
	{
		include("whois_php/whois.php");
		/*
              include("whois/main.whois");

              $whois = new Whois($fqdn);

              $result = $whois->Lookup();

              echo "<b>Resultado de  $fqdn :</b><p>";

              if(isSet($whois->Query["errstr"]))
			  {

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
*/
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
    <td colspan="3" bgcolor="#CC0000"><b><font color="#FFFFFF" size="2">ACCIONES</font></b></td>
  </tr>
  <tr align="center">
    <td width="50%" height="25" align="left" valign="top"><h1>
        <?=$rs->fields["domain"];?></h1>
       
        
    </td>
    <td width="50%" height="25" align="right" valign="top">
    <?
$doms=split("\*\*",$rs->fields["domain"]);
while (list($l,$dom)=each($doms))
echo "<br><a href=\"$PHP_SELF?whois=1&id=$id&fqdn=$dom\">whois</a>";
?>
   
            <b> <a href="<?=$PHP_SELF?>?duplicar=si&id=<?=$id?>">Duplicar</a> </b>
			<b> <a href="<?=$PHP_SELF?>?editar=1&id=<?=$id?> ">Editar</a></b>
			<?
				$sql="select * from operaciones where domain='".$rs->fields["domain"]."' order by id desc;";
				//echo $sql;
				$tmp=$conn->execute($sql);
				/*
				if(!strcasecmp($tmp->fields["tipo"],"PRO3"))
				{
					echo "<b><a href=\"downgrade.php?id=$id&domain=".$rs->fields["domain"]."\">Downgradar</a></b>";
				}
				if(!strcasecmp($tmp->fields["tipo"],"ESTANDAR"))
				{
					echo "<b><a href=\"upgrade.php?id=$id&domain=".$rs->fields["domain"]."\">Upgradar</a></b>";
				}
			 	if(!strcasecmp($tmp->fields["tipo"],"PRO2"))
				{
					echo "<b><a href=\"upgrade.php?id=$id&domain=".$rs->fields["domain"]."\">Upgradar</a></b> ";
					echo "<b><a href=\"downgrade.php?id=$id&domain=".$rs->fields["domain"]."\">Downgradar</a></b>";
					
				}*/
				echo "<b><a href=\"calculo_pro.php?id=$id&dominio=".$rs->fields["domain"]."\">Cambiar tipo</a></b>";
			 ?>
         <a href="<?=$PHP_SELF?>?borrar=si&id=<?=$id?> "><strong>BORRAR</strong></a>

    </td>
    </tr>
  <tr align="center">
    <td valign="top" bgcolor="#CCCCCC"> <strong>Aprobar pago y enviar a la cola</strong>

    </td>
	<td valign="top" bgcolor="#CCCCCC"> <b>Enviar a la cola expired</b></td>
  </tr>
  <tr align="center">
    <td valign="top" bgcolor="#CCCCCC">      
    <form name="cola_normal" action="<?=$PHP_SELF?>" method="post">
        <input type="text" size=20 name="id_manual">
        <input type="hidden" name="opensrs" value="1">
        <input type="hidden" name="id" value="<?=$id?>">
        <input name="normal" type="submit" value="enviar">
        <br>
        <font size="1"><em>Registrar&aacute; el dominio ignorando las opciones de pago</em></font>
    </form></td>
    <td valign="top" bgcolor="#CCCCCC">
          <form name="cola_expired" action="<?=$PHP_SELF?>" method="post">
<input type="text" size="20" name="id_manual">
            <input type="hidden" name="opensrs" value="21">
            <input type="hidden" name="id" value="<?=$id?>">
            <input name="expired" type="submit" value="enviar">
            <br>
            <em>usar como valor el dominio a registrar (la fecha de inicio se puede cambiar después en la cola)</em>
      </form>
    </td>
  </tr>
  <tr align="center">
    <td colspan="3" align="left" bgcolor="#CC0000"><b><font color="#FFFFFF">CAMBIOS A SOLICITUD</font></b></td>
    </tr>
  <tr align="center">
    <td height="25" colspan="3" align="left" bgcolor="#CCCCCC">

   <form name="zona_change" id="zona_change" action="<?=$PHP_SELF?>" method="post">
    <table width="100%" border="1">
  <tr>
    <th align="right" scope="row">Dominio</th>
    <td><input type="text" size=20 name="id_zona_ch" value="<?=$rs->fields["domain"];?>"></td>
  </tr>
  <tr>
    <th align="right" scope="row">Tipo de renovaci&oacute;n</th>
    <td><select name="reg_type">
      <option value="<?=$rs->fields["reg_type"];?>">
        <?=$rs->fields["reg_type"];?>
        </option>
      <option>--</option>
      <option value="new">(new)</option>
      <option value="renew">(renew)</option>
      <option value="transfer">(transfer)</option>
      <option value="zona_change">(zona_change) - cambio zona hasta fecha</option>
      <option value="renew+upgrade">(renew+upgrade) -renovar con upgrade a zonas</option>
    </select></td>
  </tr>
  <tr>
    <th align="right" scope="row">Tipo zonas</th>
    <td><select name="nm_registro_tipo">
      <option value="<?=$rs->fields["nm_registro_tipo"];?>">
        <?=$rs->fields["nm_registro_tipo"];?>
        </option>
      <option>--</option>
      <option value="estandar">estandar</option>
      <option value="pro2">pro2</option>
      <option value="pro3">pro3</option>
    </select></td>
  </tr>
  <tr>
    <th align="right" scope="row">Fecha caducidad</th>
    <td><input type="text" size=20 name="vencimiento" value="<?=$rs->fields['vencimiento'];?>">
      <br>
       aaaa-mm-dd / aplicable a &quot;zona_change&quot; para determinar fin de servicios DNS</td>
  </tr>
  <tr>
    <td align="right" scope="row"><input type="hidden" name="id" value="<?=$id?>"></td>
    <td><br>      <input name="zona_change" type="submit" value="enviar"></td>
  </tr>
    </table>
</form>

    
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
		<?
				$sql="select * from operaciones where domain='".$rs->fields["domain"]."' order by id desc;";
				//echo $sql;
				$tmp=$conn->execute($sql);
		?>
       <font color="#FFFFFF"> <font color="#000000"><?=$tmp->fields["tipo"]?></font></font>
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

      $ncols = $rs->FieldCount();
if (!isset($_REQUEST['editar']) ){
     print "<table  class=tabla align=Center>";
       for ($i=0; $i < $ncols; $i++){
        echo "<tr><td bgcolor=#cocoff>";
        $field = $rs->FetchField($i);
        print $field->name;
        echo "</td>";
        echo "<td>".$rs->fields[$i];
        echo "</td> </tr>";
       }
	   echo "</table>";
}
else {
	//edicion de la ficha
	     print '<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post"> 
		 <input type="hidden" name="grabarficha" value="1">
		 <table class="tabla" align="center">';
		 
       for ($i=0; $i < $ncols; $i++){
        echo "<tr><td bgcolor=#cocoff>";
        $field = $rs->FetchField($i);
        print $field->name;
        echo "</td>";
		if ($field->name=='id')
		echo "<td><input type='hidden' name='".$field->name."' value=\"".$rs->fields[$i]."\">".$rs->fields[$i];
		else 
        echo "<td><input type='text' name='".$field->name."' value=\"".$rs->fields[$i]."\" size=\"50\">" ;
        echo "</td> </tr>";
       }

     print "<tr><td colspan=2><input type=submit></td></tr> </table></form>";
}
?>
