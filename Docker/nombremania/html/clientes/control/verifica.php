<?

if(!isset($_COOKIE['id_cliente']) || $_COOKIE['id_cliente']=='')
{
	header('Location: ../login.php');
}

?>
