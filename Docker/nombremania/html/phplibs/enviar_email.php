<?

// funcion para enviar mail desde una template

function envioemail($template,$datos)
{

/*if($_SERVER['REMOTE_ADDR']=='89.130.215.195')
{
	print_r($datos);
	exit;
}*/

if(!is_file($template))
{
	die("Error en el template de envio del email. $template");
}

include_once("EasyTemplate.inc.php");
$template = new EasyTemplate("$template");

$datos['nombre']=utf8_decode($datos['nombre']);

while (list($k,$v)=each($datos)){
       //asigna los distintos valores
       $template->assign($k, $v);
}

$t_email=$template->easy_parse();
if ($t_email===false){
    print "<br>".$template->error;
    exit();
}

$lineas=split("\n",$t_email);
// limpiar los comentarios empiezan con #
for ($i=0;$i<count($lineas);$i++) {
     $v=$lineas[$i];
     if (!ereg("^#",$v)){
          $ultimo_comentario=$i;
          break;
	}
}
//recorta los comentarios no pueden haber comentarios intermedios solo en la cabecera
$lineas=array_slice($lineas,$ultimo_comentario);

$from=substr($lineas[0],5);
$to=substr($lineas[1],3);
$subject=substr($lineas[2],8);

$t_email=join("\n",array_slice($lineas,3));
//print "from: $from  -to - $to  --  $subject";
$m=mail($to,"$subject","$t_email","From: $from\n","-fwebmaster@nombremania.com");
//var_dump($m);
return $m;
}
?>