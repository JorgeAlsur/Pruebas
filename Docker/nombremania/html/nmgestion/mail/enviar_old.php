<?
include "hachelib.php";
include "conf_email.inc.php";
include "basededatos.php";
//var_crudas($_GET);
if (!isset($subject) or $subject==""){
    mostrar_error("falta definir el tema del mensaje. Imposible enviar");
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
	 echo "buscado : $sqls[$to]";
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
/***************************************
** Create the mail object. Optional headers
** argument. Do not put From: here, this
** will be added when $mail->send
** Does not have to have trailing \r\n
** but if adding multiple headers, must
       ** be seperated by whatever you're using
		** as line ending (usually either \r\n or \n)
        ***************************************/

 $mail = new html_mime_mail(array('X-Mailer: Html Mime Mail Class'));

		/***************************************
        ** Read the image background.gif into
		** $background
        ***************************************/

//        $background = $mail->get_file('background.gif');

        /***************************************
        ** If sending an html email, then these
        ** two variables specify the text and
        ** html versions of the mail. Don't
        ** have to be named as these are. Just
        ** make sure the names tie in to the
        ** $mail->add_html() call further down.
        ***************************************/

//        $text = $mail->get_file('example.txt');
  ///      $html = $mail->get_file('example.html');
$html=false ; 
if (isset($texto_mensaje_html) and $texto_mensaje_html <>"") $html=stripslashes($texto_mensaje_html);

$text=false;
if (isset($texto_mensaje) and $texto_mensaje <>"")		$text=stripslashes($texto_mensaje);
    /***************************************
        ** Add the text, html and embedded images.
		** Here we're using the third argument of
		** add_html(), which is the path to the
		** directory that holds the images. By
		** adding this third argument, the class
		** will try to find all the images in the
		** html, and auto load them in. not 100%
		** accurate, and you MUST enclose your
		** image references in quotes, so src="img.jpg"
		** and NOT src=img.jpg. Also, where possible,
		** duplicates will be avoided.
        ***************************************/
				if ($html){
				if (!$text) $text ="";
        $mail->add_html($html,$text);
				}
				else {
				$mail->add_text($text);
				}
        /***************************************
        ** Builds the message.
        ***************************************/



        /***************************************
        ** Sends the message. $mail->build_message()
        ** is seperate to $mail->send so that the
        ** same email can be sent many times to
        ** differing recipients simply by putting
        ** $mail->send() in a loop.
        ***************************************/
				if (isset($test)){
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

}else {
			echo "Envio a todos estos destinatarios";
			$ok=false;
			foreach($emails as $email=>$datos){
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
			}
			exit;
			
}
  
?>