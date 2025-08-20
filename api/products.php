<?php
header('Content-Type: application/json; charset=utf-8');
$labels = ["A","B","C","D"];
$data = [3500, 2800, 1500, 2200];
echo json_encode([ "labels"=>$labels, "data"=>$data ]);
