<?php
header('Content-Type: application/json; charset=utf-8');
$items = [
  ["region"=>"CDMX","lat"=>19.4326,"lon"=>-99.1332,"ventas"=>4000],
  ["region"=>"Guadalajara","lat"=>20.6597,"lon"=>-103.3496,"ventas"=>2800],
  ["region"=>"Monterrey","lat"=>25.6866,"lon"=>-100.3161,"ventas"=>3000],
  ["region"=>"Mérida","lat"=>20.9674,"lon"=>-89.5926,"ventas"=>1500]
];
$maxVentas = 0;
foreach($items as $it){ if($it["ventas"] > $maxVentas) $maxVentas = $it["ventas"]; }
echo json_encode([ "items"=>$items, "maxVentas"=>$maxVentas ]);
