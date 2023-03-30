<?php

//Include the code

include("phplot.php");

$meses=array("1"=>"Enero",

"2"=>"Febrero","3"=>"Marzo","4"=>"Abril","5"=>"Mayo","6"=>"Junio","7"=>"Julio",

"8"=>"Agosto","9"=>"Setiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");

//Define the object

$graph = new PHPlot;

include "basededatos.php";

$ncampos="mes|tipo|cantidad";

$rs=$conn->execute("select year(from_unixtime(fecha)) as anio,month(from_unixtime(fecha)) as mes,count(*) as cantidad ,tipo from operaciones group by month(from_unixtime(fecha)),tipo order by mes,tipo");

//rs2html($rs,"class=tabla align=center",explode("|",$ncampos));

$data=array();



while (!$rs->EOF){

$mes=$meses[$rs->fields["mes"]];

$tipo=strtoupper($rs->fields["tipo"]);

$cantidad=$rs->fields["cantidad"];

$anio=$rs->fields["anio"];

          if ($tipo=="PRO"){
            $data["$mes"."-$anio"][1]=$cantidad;
          }
          elseif ($tipo=="PRO2"){
            $data["$mes"."-$anio"][2]=$cantidad;
          }elseif($tipo=="PRO3"){
            $data["$mes"."-$anio"][3]=$cantidad;
         }
          else {

          $data["$mes"."-$anio"][0]=$cantidad;

          }

  $rs->movenext();

}

reset($data);

$aux=array();

while (list($k,$arr)=each($data)){
      $val=array_pop($arr);
       $aux[]=array($k,$val,$arr);



}

include "hachelib.php";
var_crudas($aux);
var_crudas($data);
//Set some data

$graph->SetDataValues($aux);

//Error_Reporting(0);

$graph->SetPlotType('bars');

$graph->SetLabelScalePosition(3);

$graph->SetLegend(array('Estandar','PRO',"PRO mail","PRO dns"));

$graph->SetXlabel("Tiempo");

$graph->SetYlabel("Registros");

$graph->SetBackgroundColor("white");

//Draw it

$graph->DrawGraph();



?>
