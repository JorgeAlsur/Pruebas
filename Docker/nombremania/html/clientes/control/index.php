<?
if (!isset($_COOKIE["id_cliente"]) or $_COOKIE["id_cliente"]==""){
header("Location: login.php");
exit;
}
else
{
header("Location: panel.php");
}

?>