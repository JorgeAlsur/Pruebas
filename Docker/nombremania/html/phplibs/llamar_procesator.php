<?
ini_set("include_path",".:/usr/lib/php:/usr/share/pear:/usr/share/php:/home/webs/nombremania.com/html/phplibs:/home/webs/phplibs");
include("procesator.php");
$expired=$argv[1];
if($expired)procesa_expired();
else procesa_cola();
exit;
?>
