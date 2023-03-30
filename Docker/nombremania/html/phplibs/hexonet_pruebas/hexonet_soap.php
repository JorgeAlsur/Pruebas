<?
//ini_set("include_path",$_SERVER['DOCUMENT_ROOT']."/phplibs");
$client = new SoapClient(NULL,
array(
"location" => "http://93.190.235.176:8080",
"uri" => "urn:Api",
"style" => SOAP_RPC,
"use" => SOAP_ENCODED,
"trace" => 1
));

$params = array(array("s_login" => "alsur.hexonet.net", "s_pw" => "Luciano08",  "s_entity" => 1234, "command" => "DeleteDNSZone", "dnszone" => "aaaalsur.es.", "extended" => 1));

//$params = array(array("s_login" => "alsur.hexonet.net", "s_pw" => "Luciano08",  "s_entity" => 1234, "command" => "ModifyUser", "subuser" => "nombremania.com", "password" => "200800", "credit" => "150.00", "vat" => 16.00, "currency" => "EUR", "active" => 1));
//"command" => "AddUser", "subuser" => $usuario, "password" => $password


//$params = array(array("s_login" => "alsur.hexonet.net", "s_pw" => "Luciano08", "s_user" => "nombremania.com", "s_entity" => 1234, "command" => "CreateDNSZone", "dnszone" => "alsurr.es."));

$result = $client->__call(
"xcall",
$params,
array("uri" => "urn:Api", "soapaction" => "urn:Api#xcall")
);
$datos['xml']=$result;
$rr=$result->PROPERTY->RR[0];
$rr_split=split(" ", $rr);
/*
if($rr_split[4]=='MX') // TIPO
{
	
}
*/
//$dnsfrom=$rr_split[0];
//print_r($rr_split);
echo "<pre>"; print_r($result->PROPERTY->RR); echo "</pre>";





?>