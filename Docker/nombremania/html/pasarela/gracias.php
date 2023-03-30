<?
//pagina de agradecimiento para el usuario despues de la pasarela de pago banco
include("basededatos.php");
$id=$pszPurchorderNum;
$rs=$conn->execute("select * from solicitados where id=$id");
$datos=$rs->fields;
$owner=$datos["owner_first_name"]." ".$datos["owner_last_name"];
include("class.template_file.php");
$t=new Template("./","remove");

$print=array();
$print["pszApprovalCode"]=$pszApprovalCode;
$print["pszTxnID"]=$pszTxnID;
$print["owner"]=$owner;

if($result==0)
{
	$t->set_file("template","gracias.html");
	while(list($k,$v)=each($print))
	{
		$t->set_var("$k", $v);
	}
}
else
{
	// imprime gracias_no
	$t->set_file("template","gracias_no.html");
	while(list($k,$v)=each($print))
	{
		$t->set_var("$k",$v);
	}
}
$t->pparse("template");
?>