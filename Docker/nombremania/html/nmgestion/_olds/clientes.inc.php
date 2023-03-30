<?
/*
  clientes.inc
  sopporte para agregar operaciones a clientes y consultas de saldos y demas cosas
  le pasamos un array vacio para recibir los errores
*/
Function cargo_a_clientes($form,&$errores){
Global $conn;

       $sql=insertdb("transacciones",$form);
       $rs=$conn->execute($sql);
return $conn->Insert_ID();
}
?>

