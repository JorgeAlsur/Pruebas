<?
include("conf.inc.php");
setcookie("REGISTRANT_LIVE_KEY","");
//echo $_COOKIE["REGISTRANT_LIVE_KEY"];exit;
//echo $_SERVER['SERVER_NAME']."/renovar/login.php";exit;
header("Location: ".$server_url."/renovar/login.php?op=delete");
?>