<?

set_time_limit(0);
$que=$_GET['codigo'];
$id_operacion='';
if($_SERVER['REMOTE_ADDR']=='89.130.215.195')$debug=1;

list($id_operacion,$p,$dominios,$usuario)=split(":",base64_decode($que));

/*if($debug)
{
	echo "id_operacion=$id_operacion p=$p dominios=$dominios usuario=$usuario";
	exit;
}*/

include('basededatos.php');
include('hachelib.php');

$error='';
$exito='';

// print "usuario : $usuario , $id_operacion , $dominios";

if($usuario=='' || $id_operacion=='' || $dominios=='')
{
	$error="Error de validacion, vuelva atr&aacute;s y reintente<hr>.$usuario -- $id_operacion -- $dominios";
	header("Location: /error.php?error=$error");
	exit;

}

// contina

     else {

     //chequear que no tenga datos de pago en dominios

     $check=$conn->execute("select nm_cod_aprob from solicitados where id=$id_operacion");

     if ($check->fields["nm_cod_aprob"]!=""){

         $error="El registro que por el que pretende realizar el pago se encuentra pagado en nuestros registros. Si cree que es un error por favor comuniquese con <a href=mailto:administracion@nombremania.com>administracion@nombremania.com</a>. ";

     }

     else {

     $sql_dominios="select nm_preciototal,domain as dominios from solicitados where id=$id_operacion";

     $conn->debug=0 ;  //presenta mensajes en pantalla del sql

     $rs_dominios=$conn->execute($sql_dominios);



     $sql1="select id,nombre from clientes where usuario=\"$usuario\"";

     $rs=$conn->execute("$sql1");

     if ($rs->fields["id"]==0) die("error en usuario");

     $id_cliente=$rs->fields["id"];
     $saldo=$conn->execute("select sum(importe) as saldo from transacciones where id_cliente=$id_cliente");
     $saldo=$saldo->fields["saldo"];
     $id_cliente =$rs->fields["id"];
     $datos["id_cliente"]=$id_cliente;
     $datos["tipo"]="d";
     $datos["importe"]=$rs_dominios->fields["nm_preciototal"]*-1;
     $datos["observacion"]="pago por la registracion de: ".$rs_dominios->fields["dominios"];
     $datos["fecha"]=date("Y-m-d");



     if ($saldo<$rs_dominios->fields["nm_preciototal"]){

     $error= "Su saldo no alcanza para cancelar la operacion.";

     }

         else {



     // ingreso de debito en la cuenta del cliente

     $insertar=insertdb("transacciones",$datos);

     if ($conn->execute($insertar) === false) {

        $error= '<p class=alerta>error insertando el pago</p>'.$conn->ErrorMsg().'<BR> comuniquese con nosotros para concretar el pago';

        }

        else {

     //obtencion del ID de la operacion

     $id_pago=mysql_insert_id();

                   include "procesator.php";

                     if (!pagator("cliente",$id_operacion,$id_pago)){

                            mail("soporte@nombremania.com","error en el pago CLIENTES","id_operacion=$id_operacion,id_pago=$id_pago","From: noreply@nombremania.com") ;

                       }

                        else {

                            mail("registros@nombremania.com","Pago realizado a traves de la pasarela","Pago solicitud=$pszPurchorderNum , codigo de aprobacion= $pszApprovalCode. ","From: noreply@nombremania.com");

                       }

              }



        }

   }

   }

   // sale por error o por  ok segun el mensaje recibido

  if ($error==""){

     header("Location: pagogracias.php?codigo=$que");

     exit;

     }

     else {

     $error=urlencode($error);

     header("Location: /error.php?error=$error");

     exit;

     }





?>
