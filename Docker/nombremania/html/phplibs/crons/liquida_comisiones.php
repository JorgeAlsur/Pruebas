<? 
include "basededatos.php";
include "hachelib.php";
$liquidar_en=30; //se liquidan las comisiones al llegar a 30 euros 

$conn->debug=false;
$sql="select * from clientes where id >100 order by id";
$clientes = $conn->execute($sql);
$comisiones=array();  
while (!$clientes->EOF){
			$id_cliente = $clientes->fields["id"];
			$op_liquidadas=$clientes->fields["op_liquidadas"];
			$email=$clientes->fields["email"];
			$rs=$conn->execute("Select comision, id  from operaciones where affiliate_id = $id_cliente and id >$op_liquidadas and comision >0 order by id ASC");
			if ($rs<>false and $conn->affected_rows()>0){
				 						 while (!$rs->EOF){
										 			 $t_comision+=$rs->fields[0];
													 $ult_operacion=$rs->fields[1];
										 $rs->movenext();
										 }
			
				 $comisiones[$id_cliente]=array($ult_operacion,round($t_comision,5),$email); //redondeo por error en Mysql con los float y los doubles,
//				 var_crudas($rs->fields[0]);
			}
			$clientes->movenext();
}

//var_crudas(count($comisiones));

foreach($comisiones as $id_cliente =>$datos){
				$sql="insert into liquidaciones (affiliate_id,importe,fecha,op_liquidadas,pagado) values ($id_cliente,{$datos[1]},NOW(),{$datos[0]},'N')";

        $rs=$conn->execute($sql);								
        if ($rs===false) {
					 mail("soporte@nombremania.com","error en liquidaciones","error en el ingreso de la liquidacion afiliado $id_cliente en liquidaciones");
				}
				else {
				  $sql2="update clientes set op_liquidadas = {$datos[0]} where id = $id_cliente";
					$rs=$conn->execute($sql2);
				}
				$rs2=$conn->execute($sql2);
				if ($rs2===false){
					 mail("soporte@nombremania.com","error en liquidaciones","error en el ingreso de la liquidacion de la ultima operacion liquidada al afiliado $id_cliente -- operacion_liquidada = {$datos["0"]}");					 
				}
// mail a cliente anulado para el futuro

//				mail("{$datos[2]}","Liquidacion de Comisiones de Nombremania", "En el día de la fecha se le han liquidado comisiones por {$datos["1"]} euros. \n Ingrese en su panel de administracion y compruesbe que esto sea correcto, posteriormente pongase en contacto con administracion@nombremania.com para hacer efectivo el pago"); 
				

}

// mail para nombremania
if (count($comisiones)>0){
    mail("administracion@nombremania.com","Liquidacion de comisiones", "se han liquidado comisiones en nombremania entrar en administracion y chequear las liquidaciones");
}
 ?>