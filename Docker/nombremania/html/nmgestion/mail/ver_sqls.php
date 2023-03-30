<?
include "conf_email.inc.php";
include "hachelib.php";
include "basededatos.php";
$conn->debug=true;
$rs=$conn->execute($sqls[$ver]);
rs2html($rs, "width=700 style='background-color:#ffff99'; border: solid,black ");
?>