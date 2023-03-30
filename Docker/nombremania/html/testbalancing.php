<?php
if($_SERVER['REMOTE_ADDR']!='89.130.215.195' || ($_SERVER['HTTP_HOST']!='nombremania.com:8998' && $_SERVER['HTTP_HOST']!='www.nombremania.com:8998'))
{
	header("HTTP/1.0 403 Forbidden");
}
else
{
	/*$fd=fopen('test.tgz','r');
	$tmp=fread($fd);
	echo $tmp;
	fclose($fd);
	header('Content-Type: application/x-gzip');*/
	header('Location: http://www.nombremania.com:8998/test.tgz');
}
?>
