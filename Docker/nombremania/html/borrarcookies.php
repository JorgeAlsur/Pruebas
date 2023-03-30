<?
   include "hachelib.php";
	 if (!isset($ok)){
	 		foreach($_COOKIE as $n=>$v){
						setcookie($n,"");					
			}
	 }
	 echo "Cookies Definidas ";
	 	 var_crudas($_COOKIE);
		 
		echo  "<H3><a href=\"$PHP_SELF\">BORRAR LAS COOKIES DEFINIDAS PARA ESTE SITIO</a></H3>";

?>