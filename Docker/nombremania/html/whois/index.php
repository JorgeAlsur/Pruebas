<?
if (isset($domain)){
		enviar($domain);
}
else {
readfile("whois.html");

}

function enviar($domain){

list($nom,$ext)=explode(".",$domain);
if ($ext=="es"){
	 $pasar="list=Dominios&key=$domain";
	 $text="http://www.nic.es/cgi-bin/consulta.whois?$pasar";
	 $dom_es="Lo sentimos pero en estos momentos no podemos acceder a la informacion de los dominios .es<br>
	 Para realizar su consulta  <a href=".$text.">pulse aqui</a>";
	 include_once "EasyTemplate.inc.php";
	$template = new EasyTemplate("../Templates/whois/whois.html");
	$template->debug=true;
	$template->first = "\$";
	$template->end = "\$";
 	$template->assign("RESULT", $dom_es);
	$contenido=$template->easy_parse();

	print $contenido;
	//ver_es($domain);
	exit;
}
else {
header("Location: /cgi-bin/whois/whois.cgi?domain=$domain");
exit;
}
}


//funcion ver_esp no vale, metodo cambiado a post en la pagina a la que se llama
function ver_es($domain){
$pasar="tipo=procesar&list=Dominios&key=$domain";
$text=file("http://www.nic.es/cgi-bin/consulta.whois?$pasar");
$mostrar=0 ;
$disponible=0;
$filas="";
while (list($k,$fila)=each($text)){

       if ($mostrar==0 and eregi("<H1> ¡No se ha encontrado ningun objeto! </H1>",$fila)){

           $disponible=1;

           break;

       }

       if ($mostrar==0  and eregi("<BLOCKQUOTE><TABLE><TR><TD><STRONG>Dominio</STRONG>:",$fila)){

       $mostrar=1;

       }

       if ($mostrar){

        $filas.=$fila;

              if (eregi("volver",$fila)){

              $mostrar=0;

              break;

              }

        }



     }
     
     

if (!$disponible){

$filas=trim(strip_tags($filas,"<table>|<tr>|<td>"));
$separador="</TD><TD>";
$separador_filas="</TR><TR>";
$datos=explode($separador_filas,$filas);
/*print "<pre>";
var_dump($datos);
print "</pre>";
  */


foreach($datos as $dato){
    list($nombre,$valor)=explode($separador,$dato);
    $nombre=trim(strip_tags($nombre));
    if ($nombre <>""){
    $valor=strip_tags($valor);
    $whois[$nombre]=$valor;
}
       }
$dom_es= "<table>\n";
while (list($k,$v)=each($whois)){
    $k= htmlentities($k);
    $v=htmlentities($v);
      $dom_es.= "<tr><td>$k</td><td>$v</td></tr>\n";

    }
$dom_es.= "</table>\n";


include_once "EasyTemplate.inc.php";
$template = new EasyTemplate("../Templates/whois/whois.html");
$template->debug=true;
$template->first = "\$";
$template->end = "\$";
 $template->assign("RESULT", $dom_es);
$contenido=$template->easy_parse();

print $contenido;

exit;
}



if ($disponible){

echo  "Registro disponible";

}


    }

    
?>
