<?
include "hachelib.php";
include "conf_email.inc.php";
include "basededatos.php";
//var_crudas($_GET);
if (!isset($subject) or $subject==""){
    mostrar_error("Falta definir el tema del mensaje. Imposible enviar");
		echo "<a href=\"#\" onclick='history.back'>Volver</a>";
		exit;
}

if (isset($test)){
mostrar_error("Enviando a correo de test: $test_email");
mostrar_error("Volver Atras y realizar el envio");
$emails=$test_email;
}
else {
$rs=$conn->execute($sqls[$to]);
$emails =array();
if ($rs===false or $conn->affected_rows()==0){
	 mostrar_error("nada encontrado para enviar mails");
	 echo "Buscado : $sqls[$to]";
}

$rs->movefirst();
while (!$rs->EOF) {
     $emails[$rs->fields["email"]] = array("nombre"=>$rs->fields["nombre"],
		 															 	 "empresa" =>$rs->fields["empresa"],
																		 "campo1" =>$rs->fields["campo1"],
																		 "campo2"=>$rs->fields["campo2"],
																		 "campo3"=>$rs->fields["campo3"]);
$rs->movenext();
}

}


 include('class.html.mime.mail.inc');

	define('CRLF', "\n", TRUE);


$html=false ; 
if (isset($texto_mensaje_html) and $texto_mensaje_html <>"") $html=stripslashes($texto_mensaje_html);

$text=false;
if (isset($texto_mensaje) and $texto_mensaje <>"")		$text=stripslashes($texto_mensaje);

/*					if ($html){
			if (!$text) $text ="";
//        $mail->add_html($html,$text);
				}
				else {
	//			$mail->add_text($text);
				}
*/
if (isset($test)){
 $mail = new html_mime_mail(array('X-Mailer: Html Mime Mail Class'));
				if ($html){
				if (!$text) $text ="";
					 foreach ($campos_reemplazo as $campo){
  		       $html=preg_replace('{{'.$campo.'}}',$campo,$html);
			  }
				       $mail->add_html($html,$text);
				}
				else {
				$mail->add_text($text);
				}
        /***************************************
        ** Builds the message.
        ***************************************/
        $mail->build_message();
var_crudas( $mail->send("$test_nombre", "$test_email", "$from_nombre", "$from", "$subject"));
unset($mail);
}else {
			echo "Envio a todos estos destinatarios";
			$ok=false;
			foreach($emails as $email=>$datos){
			 $mail = new html_mime_mail(array('X-Mailer: Html Mime Mail Class'));
				if ($html){
  				if (!$text) $text ="";
					$html_cambio=$html;
			 foreach ($campos_reemplazo as $campo){
  		       $html_cambio=preg_replace('{{'.$campo.'}}',$datos[$campo],$html_cambio);
			  }
				   $mail->add_html($html_cambio,$text);
				}
				else {
				$mail->add_text($text);
				}
       $mail->build_message();
		 $ok=	$mail->send($datos["nombre"], "$email", "$from_nombre", "$from", "$subject");
						 if (!$ok){
		 							echo "<p style='background-color:red'>enviando a $email <br>con estos datos : " ;
				   			   var_crudas($datos);
									 echo "</p>";
							 }
							 else {
                   echo "<p style='background-color:green'>enviando a $email <br>con estos datos : " ;
				   			   var_crudas($datos);
									 echo "</p>";
							 }
			unset($mail);				 
			}
			exit;
			
}
  
?>