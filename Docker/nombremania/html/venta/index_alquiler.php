<?
//ini_set("include_path",".:/usr/lib/php:/home/webs/phplibs:/home/webs/nombremania.com/html/phplibs");

  //programa para la gestion de las alquiler de dominios
  // opciones listar, agregar , borrar  (confirma borrado),

  $campos = array("id","fecha_alq","dominio","precio","email",
  "affiliate_id","contador","precio_vta","maximo");

	include("basededatos.php");
	include("hachelib.php");
	$conn->debug=0;

	$tareas=array("agregar","listar","borrar","grabar","ofertar");


if (!isset($op) or ! in_array($op,$tareas))
{
	$op="listar";
}
include("EasyTemplate.inc.php");
$op();


function agregar()
{
	global $dominio,$conn;

	$datos=array();
	include("class.templateh.php");
	$t=new Template("templates","remove");

	$t->set_file("template", "agregar_alquiler.html");
	$t->set_var("dominio",$dominio);
	$t->set_var("DOMAIN_NAME",utf8_encode($dominio));
	$t->set_var("CGI",$_SERVER['PHP_SELF']."?op=grabar");
	$t->pparse("template");
}

function grabar()
{
	global $conn;

	extract($_POST);
	$datos["fecha_alq"]=date("Y-m-d",time());
	$datos["precio"]=$precio;
	$datos["email"]=$email;
	$datos["dominio"]=$dominio;
	$datos["anos"]=$anos;
	if(isset($vende) and $vende)
	{
		$datos["precio_vta"]=$precio_vta;
	}
	$sql1="select id from alq_dominios where dominio='$dominio'";
	$rs=$conn->execute($sql1);
	if($conn->affected_rows()==0)
	{
		$sql=insertdb("alq_dominios",$datos);
	}
	else
	{
		$sql=updatedb("alq_dominios",$datos," where id=".$rs->fields["id"]);
	}
	$conn->debug=false;
	$rs=$conn->execute($sql);
	if($rs===false)
	{
		header("Location: /nm/error.php?error=error en la base de datos");
		print mysql_error();
		exit;
	}
	else
	{
		readfile("templates/gracias.html");
	}
}

function listar()
{
	global $conn;
	
	//extract($_GET);

            if (!isset($order)){
                $order="fecha_alq desc";
                }
           $sql="select * from alq_dominios order by $order";
           $rs=$conn->execute($sql);
           include "class.templateh.php";
           $t = new Template("templates","remove");
           $t->set_file("template", "listar_alq.html");
           $t->set_var("orden_fecha","<a href=".$_SERVER['PHP_SELF']."?op=listar&order=fecha_alq>Fecha</a>");
           $t->set_var("orden_dominio","<a href=".$_SERVER['PHP_SELF']."?op=listar&order=dominio>Dominio</a>");
           $t->set_var("orden_precio","<a href=".$_SERVER['PHP_SELF']."?op=listar&order=precio>Precio/a&ntilde;o</a>");
           while (!$rs->EOF){
               extract($rs->fields);
               $fecha_alq=substr($fecha_alq,0,10);
               $d=explode("-",$fecha_alq);
               $fecha_alq=$d[2]."-".$d[1]."-".$d[0];
               $t->set_var("fecha_alq",$fecha_alq);
               $t->set_var("dominio",utf8_encode($dominio));
               $t->set_var("precio",$precio);
               $t->set_var("maximo",$maximo);
               $t->set_var("precio_vta",$precio_vta);
               $t->set_var("ofertar",$_SERVER['PHP_SELF']."?op=ofertar&id=$id");

               $t->parse("ofertas","ofertas", true );
               $rs->movenext();
               }
$t->pparse("template");
       }

function ofertar()
{
	global $conn;


if  (isset($_POST["oferta"])){
    $mensaje ="Hemos recibido la siguiente oferta por su dominio:\n ";
    $mensaje .= $_POST["oferta"]."\n\n" ;
$mensaje .= "en fecha  :".date("d-m-Y",time());
    $mensaje.= " desde numero IP".$_SERVER['REMOTE_ADDR'] ."\n";
    $mensaje.=" El email del oferante es {$_POST["email"]}\n ";
    $mensaje.=" esta interesado en: ";
    if (isset($_POST["alquiler"])) $mensaje.="\nALQUILER";
    if (isset($_POST["venta"])) $mensaje.="\nVENTA";

$mensaje.="\n\nRecuerde que puede registrar, administrar, comprar o vender dominios en NOMBREMANIA.COM";

$sql="select * from alq_dominios where dominio=\"{$_POST["oferta"]}\"";
$conn->debug=false;
$rs=$conn->execute($sql);
$destino=$rs->fields["email"];
mail($destino,"OFERTA de venta/alquiler de Dominios",$mensaje,"From: registros@nombremania.com");
readfile("templates/gracias_oferta.html");
exit;
}else{

EXTRACT($_GET);
    if (!isset($id) or $id==""){
        echo "error al ofertar";
        exit;
        }
$sql="select * from alq_dominios where id=$id";
$rs=$conn->execute($sql);
if ($rs===false or $conn->affected_rows()==0) {
   mostrar_error("Dominio no a la venta");
   exit;
}
//include "EasyTemplate.inc.php";
$t=new EasyTemplate("templates/oferta.html");
$dominio=$rs->fields["dominio"];
$t->assign("dominio",$dominio);
$t->assign("oferta",$dominio);
$t->assign("CGI",$_SERVER['PHP_SELF']);
$t->easy_print();
}
}
?>