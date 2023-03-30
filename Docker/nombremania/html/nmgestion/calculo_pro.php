<?

include("basededatos.php");

$gastos_gestion=10;

//programa en php horacio degiorgi

$fi=date("Y-m-d");
	
$sql="select * from precios where producto='pro2' or producto='pro3';";
$rs=$conn->execute($sql);
	
$sql="select * from operaciones where domain='$dominio' order by id desc;";
$rs=$conn->execute($sql);
$tipo=$rs->fields["tipo"];

if($dominio!="")$readonly="readonly='readonly'";
//$dns=22;
?>
<form action="calculo_pro_result.php" method="post">
Dominio = <input type="text" name="dominio" value="<?=$dominio?>" <?=$readonly;?>>
<br/>
fecha actual = <input type="text" name="fi" value="<?=$fi?>">
<br>
fecha vencimiento dominio  = <input type="text" name="ff" value="<?=$ff?>"> (mirar whois)
<br>
<!-- Precio DNS avanzada  = <input type="text" name="dns" value="<?=$dns?>"> -->
Tipo actual = <?=$tipo?>
<br/>
pasar a <select name="new_tipo">
<?php
	if(!strcasecmp($tipo,"PRO2"))
	{
		echo "<option>ESTANDAR</option>";
		echo "<option selected=\"selected\">PRO3</option>";
	}
	else if(!strcasecmp($tipo,"PRO3"))
	{
		echo "<option selected=\"selected\">PRO2</option>";
	}
	else echo "<option selected=\"selected\">PRO2</option>";
?>
</select>
<br>
<input type="hidden" name="tipo" value="<?=$tipo?>"/>
<input type="hidden" name="id" value="<?=$id?>"/>
<br>
<input type=submit name='sumbit' value='valorar'>
</form>
