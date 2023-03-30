<?

$pasar="tipo=procesar&list=Dominios&key=$dominio";

$text=file("http://www.nic.es/cgi-bin/consulta.whois?$pasar");
$mostrar=0 ;
$disponible=0;
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
     $mensaje=urlencode("Dominio Registrado<br>$filas");
     header("Location: /error.php?error=$mensaje");

}

if ($disponible){
echo  "registro disponible";
}


?>