<?
//ini_set("include_path",".:/usr/lib/php:/home/webs/phplibs:/home/webs/nombremania.com/html/phplibs");

  //programa para la gestion de las ventas de dominios

  // opciones listar, agregar , borrar  (confirma borrado),

  

  $campos = array("id","fecha_vta","dominio","precio","email",

  "affiliate_id","contador");

  include "basededatos.php";

  include "hachelib.php";

  $conn->debug=0;

   $tareas=array("agregar","listar","borrar","grabar","ofertar");

if (!isset($op) or ! in_array($op,$tareas)) {

   $op="listar";

}

   include "EasyTemplate.inc.php";

$op();

function agregar()
{

   global $dominio,$_COOKIE,$PHP_SELF,$conn;

         $datos=array();

       include "class.templateh.php";

           $t = new Template("templates","remove");

           $t->set_file("template", "agregar_vta.html");

           $t->set_var("dominio",$dominio);

           $t->set_var("DOMAIN_NAME",$dominio);

           $t->set_var("CGI",$PHP_SELF."?op=grabar");

           $t->pparse("template");

}

function grabar(){

Global $_POST,$conn;

extract($_POST);

$datos["fecha_vta"]=date("Y-m-d",time());

$datos["precio"]=$precio;

$datos["email"]=$email;

$datos["dominio"]=$dominio;

$datos["contador"]=0;

$sql1="select id from venta_dominios where dominio='$dominio'";

$rs=$conn->execute($sql1);

if ($conn->affected_rows()==0){

     $sql=insertdb("venta_dominios",$datos);

}

else{

     $sql=updatedb("venta_dominios",$datos," where id=".$rs->fields["id"]);

}

$conn->debug=false;

       $rs=$conn->execute($sql);

       if($rs===false){

           header("Location: /nm/error.php?error=error en la base de datos");

           print mysql_error();

           exit;

           }

           else {

           readfile("templates/gracias_oferta.html");

           }

       }

function listar()
{
            global $conn;

            extract($_GET);

            if(!isset($order))
			{
				$order='fecha_vta desc';

			}

            

           $sql="select * from venta_dominios order by $order";

           $rs=$conn->execute($sql);

           include "class.templateh.php";

           $t = new Template("templates","remove");

           $t->set_file("template", "lista.html");

           while (!$rs->EOF){

               extract($rs->fields);

               $fecha_vta=substr($fecha_vta,0,10);

               $d=explode("-",$fecha_vta);

               $fecha_vta=$d[2]."-".$d[1]."-".$d[0];

               $t->set_var("fecha_vta",$fecha_vta);

               $t->set_var("dominio", utf8_encode($dominio));



               $t->set_var("precio",$precio);

               $t->set_var("ofertar","/venta/index.php?op=ofertar&id=$id");

               $t->parse("ofertas","ofertas", true );

               $rs->movenext();

               }

$t->pparse("template");

       }

function ofertar(){

Global $_GET,$_POST,$conn,$PHP_SELF,$REMOTE_ADDR;

if  (isset($_POST["oferta"])){

    $mensaje ="Hemos recibido la siguiente oferta por su dominio:\n ";

    $mensaje .= $_POST["oferta"]."\n\n" ;

$mensaje .= "en fecha  :".date("d-m-Y",time());

    $mensaje.= " desde numero IP".$REMOTE_ADDR ."\n";

    $mensaje.=" El email del oferante es {$_POST["email"]}\n ";

    $mensaje.=" esta interesado en: ";

    if (isset($_POST["alquiler"])) $mensaje.="\nALQUILER";

    if (isset($_POST["venta"])) $mensaje.="\nVENTA";

$mensaje.="\n\nRecuerde que puede registrar, administrar, comprar o vender dominios en NOMBREMANIA.COM";

$sql="select * from venta_dominios where dominio=\"{$_POST["oferta"]}\"";

$conn->debug=false;

$rs=$conn->execute($sql);

$destino=$rs->fields["email"];

mail($destino,"OFERTA de venta/alquiler de Dominios",$mensaje,"From: registros@nombremania.com");

readfile("templates/gracias_oferta.html");

exit;

}else{

EXTRACT($_GET);

    if (!isset($id) or $id==""){

    header("Location: /error.php?error=Error+al+ofertar");

exit;

        }

$sql="select * from venta_dominios where id=$id";

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

$t->assign("CGI",$PHP_SELF);

$t->easy_print();

}

}
?>