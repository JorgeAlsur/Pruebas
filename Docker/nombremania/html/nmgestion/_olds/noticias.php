HTML>

<HEAD><TITLE>ficha de opcione de pago Nombremania -administracion interna</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

</HEAD>

<body bgcolor=#fffccc>

<?
include "barra.php";
include "hachelib.php";
  ?>

  <H3 align="CENTER">Noticias</H3>

   <?

   include "basededatos.php";

  $conn->debug=0;

  if (!isset($id) or $id=="" ){

  echo "falta id";

  exit;

  }

   if (isset($borrar)){

        $sql="delete from moticias where id=$id";

        $rs=$conn->execute($sql);

        echo "<p class=alerta>registro borrado</a>";

  exit;

  }elseif (isset($editar)){
  if (!isset($id)){
     echo "<h3 align=Center>Error falta el ID </h3>";
     exit;
  }
  $sql="select * from noticias where id=$id";
  $rs=$conn->execute($sql);
  if ($rs===false or $conn->affected_rows()){
  echo "<h3 align=Center>Error noticia no encontrada</h3>";
   }

   $datos=$rs->fields;

  }



