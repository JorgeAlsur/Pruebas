<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php

$ext=strstr($fqdn,".");
$pos=strpos($fqdn,".");
$domain=substr($fqdn,0,$pos);
$ext=substr($ext,1,100);
//echo $domain." ".$ext;exit;
include("whois_php/server_list.php");
include("whois_php/whois_class.php");
// error_reporting(E_ALL);
$my_whois = new Whois_domain;
$my_whois->possible_tlds = array_keys($servers); // this is the array from the included server list
$my_whois->domain=$domain;
$my_whois->tld=$ext;
$my_whois->free_string = $servers[$ext]['free'];
$my_whois->whois_server = $servers[$ext]['address'];
$my_whois->whois_param = $servers[$ext]['param'];
$my_whois->full_info="yes";
//echo "llega aquí<br/>";
$my_whois->process();
echo $my_whois->msg;
echo "<br/>";
echo nl2br($my_whois->info);
?>