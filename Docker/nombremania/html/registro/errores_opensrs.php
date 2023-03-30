<?
function traduce($error){
 switch ($error){
          case "Transfer in progress":
          return "La transferencia de este dominio se encuentra en proceso";
          break;
          case "Domain not registered":
          return "Este dominio no est&aacute; registrado, registrelo en Nombremania.com.";
          break;
         default:
          return $error;
             }

}
