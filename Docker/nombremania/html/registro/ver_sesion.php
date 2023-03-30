<?php
/*include('adodb/adodb.inc.php');
include('adodb/adodb-session.php');
//ini_set("session.gc_maxlifetime",6000);
session_start();
*/
if ($_SERVER["REMOTE_ADDR"]<>"213.96.190.180"){
	 echo "error ";
	 exit;
}
include "sesion.php";
print "<h3>REGISTRANDO</h3><pre>";
var_dump($registrando);
print "</pre>";
print "todas  las SESIONES<pre>";

var_dump($_SESSION);
print "</pre>";


phpinfo(8);
?>
