<?
$templates=$_SERVER['DOCUMENT_ROOT'].'/clientes/templates/';
include('hachelib.php');
include('class.templateh.php');
$t=new Template($templates,'remove');

if(!isset($_COOKIE['id_cliente']) || $_COOKIE['id_cliente']=='')
{
	$t->set_file('template','cliente_no_registrado_inc.html');
	$t->set_var('volver',$_SERVER['REQUEST_URI']);
	$t->pparse('template');
}
else
{
    include('basededatos.php');
    $rs=$conn->execute("select * from clientes where id=".$_COOKIE['id_cliente']);
	if($rs===false)
	{
		header('Location: /error.php?error=error en la base de datos');
		exit;
	}
	$cliente=$rs->fields['usuario'];
	$t->set_file('template','cliente_registrado_inc.html');
	$t->set_var('cliente',$cliente);
	$t->set_var('volver',$_SERVER['REQUEST_URI']);
	$t->pparse('template');
}
?>