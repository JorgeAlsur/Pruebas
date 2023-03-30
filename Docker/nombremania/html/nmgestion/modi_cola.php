    <HTML>

<HEAD><TITLE>modificacion de estado de cola</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

</HEAD>


<?
include "hachelib.php";
include "barra.php";

include "basededatos.php";

if (!isset($modificar)){
echo "ID = $id";
$rs=$conn->execute("select * from cola where id = $id"); 
rs2html($rs);
//$estados= array(1 =>"alta en zoneedit",2=>"alta en opensrs", 3=>"alta en operaciones", 90=>"error en zoneedit",99=>"en espera", 100=>"proceso terminado");
$estados= array(20=>"alta en opensrs", 30=>"alta en operaciones", 40 =>"alta en hexonet", 41 => "alta en zona", 66 => "borrar zona", 77 => "cola expired", 90=>"error en opensrs", 91 => "error en hexonet", 92 => "error en zona", 94 => "error al borrar zona", 99=>"en espera", 100=>"proceso terminado"); // nueva numeracion

$rs->movefirst();

echo "<B>FECHA ACTUAL (".$rs->fields['procesar_fecha'].") - ".date('Y-m-d', $rs->fields['procesar_fecha']).'</B>';

echo "<form name=enviar action=$PHP_SELF METHOD=POST>
<input type=hidden name=id value=\"$id\">
<select name=new_estado>".  form_select($estados,"new_estado",$rs->fields["estado"])."</select>
<input type=submit name=modificar></form>
<form name=enviar action=$PHP_SELF METHOD=POST>
<input type=hidden name=id value=\"$id\">
Nueva fecha: (formato YYYY-mm-dd) <input type='text' name='new_fecha' value='".date('Y-m-d', $rs->fields["procesar_fecha"])."'> 
<input type=submit name=modificar></form>


";

}
else {
if($new_estado <>""){
	 $sql="update cola set estado = $new_estado where id=$id";
	 $rs=$conn->execute($sql);
	 if ($rs) mostrar_error( "modificado con exito");
	 
}
elseif ($new_fecha <>""){
	 $sql="update cola set procesar_fecha = UNIX_TIMESTAMP('$new_fecha') where id=$id";
	 $rs=$conn->execute($sql);
	 if ($rs) mostrar_error( "modificado con exito");
	 
}
else {
		 mostrar_error("seleccionar el estado");
}
}

?>
