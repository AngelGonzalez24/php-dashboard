<?php
header('Content-Type: application/json; charset=utf-8');
$meses = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
$ingresos = [1200,1500,1800,2000,2200,2400,2100,2300,2500,2700,3000,3200];
echo json_encode([ "meses"=>$meses, "ingresos"=>$ingresos ]);
