<html><head>
<link rel="stylesheet" type="text/css" href="estilo.css">
</head><body>
<h2 align=Center>CLIENTES</h2>
<?



/*

alta de clientes via administracion en nombremania

archivo relacionado

**************************

formcliente.inc.php   easytemplate

*********************

*/

include 'barra.php';
include 'basededatos.php';
include 'hachelib.php';

if($tarea=='impresion')
{
	$sql="select * from clientes where id=$id_cliente";

	$rs=$conn->execute("$sql");

	rs2html($rs, "class=tabla align=center");

	$sql="select id,date_format(fecha,\"%d-%m-%y\") as fechas,tipo,importe,observacion from transacciones where id_cliente=$id_cliente order by fecha desc";

	//$rs1=$conn->execute("$sql");

	echo $rsl->fields[1];exit;

	rs2html($rs1, "border=1 class=tabla align=center");

	exit;

}

//include "barraclientes.php";

function consulta($id_cliente)
{
	global $conn;
	
	$conn->debug=0;
	$sql="select sum(importe) as saldo from transacciones where id_cliente=$id_cliente";
	$rs1=$conn->execute("$sql");
	rs2html($rs1, "width=50% class=tabla align=center");
	$sql="select id,date_format(fecha,\"%d-%m-%y\") as fechas,tipo,importe,observacion  from transacciones where id_cliente=$id_cliente order by fecha desc";
	$rs=$conn->execute($sql);
	rs2html($rs,"class=tabla align=Center");

	return;
}

function formulario($id_cliente,$nombre)
{
	include('EasyTemplate.inc.php');

	$template=new EasyTemplate("formcuenta.inc.php");

	$campos=array('importe','fechas','fechas','observacion');

	// Assign template values

	$template->assign('tarea','graba-alta');
	$template->assign('id_cliente',$id_cliente);
	$template->assign('fecha',date('d/m/Y'));
	$template->assign('action',$_SERVER['PHP_SELF']);

	for($i=0;$i<=count($campos);$i++)
	{
		$template->assign($campos[$i],'');
	}
	$template->easy_print() or die($template->error);
	exit();
	return;
}

function graba($form,$id_cliente)
{

	//validacion
	include('clientes.inc.php');
	include('String_Validation.inc.php');

	$errores=array();
	if(!is_numeric($form['importe']))$errores[]='Importe no es numerico';
	if(!is_date($form['fecha']))$errores[]='Fecha no válida';
	if($form[tipo]=='debito')$form['importe']=$form['importe']*-1 ;
	if(count($errores)>0)
	{
		print "<p class=alerta>".join("<br>",$errores)."</p>";
		return false;
	}

	$form['fecha']=fecha2mysql($form['fecha']);
	//echo $form['fecha'];exit;

	if(cargo_a_clientes($form,$errores))
	{
		echo 'Insert correcto en la base de datos.';
	}
	else
	{
		echo 'Errores en el insert en la base de datos.';
	}

	return;
}

function barra($id_cliente)
{
	global $conn;

	$rs=$conn->execute("select * from clientes where id=$id_cliente");
	echo "<div align='center'><center><table border='1' align=Center class=tabla>\n";
	echo "<tr>\n<td> <a href=\"$PHP_SELF?id_cliente=$id_cliente&nombre=$nombre&tarea=alta\">Ingreso de operaciones </a> </td>\n";
	echo "\n<td><a href=$PHP_SELF?tarea=impresion&id_cliente=$id_cliente target=impresos> Impresion de Ficha y Saldos</a> </td>\n</tr>";
	echo "<tr class=titulo>
<td>ID cliente: </td><td>$id_cliente
</td>
</tr>
<tr class=titulo>
<td>nombre</td><td>".$rs->fields["nombre"]."</td></tr> ";
	echo "\n</table></center></div>";
}

if (!isset($tarea) or $tarea=='')$tarea='consulta';
$id_cliente=$_GET['id_cliente'];

switch ($tarea)
{
	case 'consulta':
	barra($id_cliente);
	consulta($id_cliente);
	break;
	case 'alta':
	barra($id_cliente);
	formulario($id_cliente,$nombre);
	break;
	case "graba-alta":
	graba($form,$id_cliente);
     barra($form["id_cliente"]);
     break;
	case "actualiza":
        actualiza($id_cliente,$form);
        break;
}
?>
</body>