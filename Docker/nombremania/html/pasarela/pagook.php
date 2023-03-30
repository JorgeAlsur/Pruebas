<?
$error="";
if ($result==0){
if (!isset($pszApprovalCode) or $pszApprovalCode == ""){
     $error="ok de pasarela result = $result, y no manda el codigo de aprobacion pszApprovalCode=$pszApprovalCode";
}
if (!isset($pszPurchorderNum) or $pszPurchorderNum==""){
     $error="ok de pasarela result = $result, y no manda el codigo de operacion =$pszApprovalCode";
}
   include "procesator.php";
    if($error=="" and !pagator("banco",$pszPurchorderNum,$pszApprovalCode)){
     $error="error en la llamada a pagator cola no conformada pago aceptado Codigo_aprob=$pszApprovalCode , id_solicitud=$pszPurchorderNum";
    }
    if ($error!=""){
    mail("soporte@nombremania.com","error en la pasarela de pago",$error,"From: noreply@nombremania.com") ;
    }
    else {
   // mail("administracion@nombremania.com","Pago realizado a traves de la pasarela","Pago solicitud=$pszPurchorderNum , codigo de aprobacion= $pszApprovalCode. ","From: noreply@nombremania.com");
    }
}
?>
