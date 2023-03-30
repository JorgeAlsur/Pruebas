<?
$DOCUMENT_ROOT = '/home/webs/nombremania.com/html';
include( $DOCUMENT_ROOT."/phplibs/basededatos.php");
include("cron_traslados.php");
procesa_cron();
?>
