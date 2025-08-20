<?php
header('Content-Type: application/json; charset=utf-8');
$labels = ["Muy Satisfecho","Satisfecho","Neutral","Insatisfecho"];
$values = [120, 200, 50, 30];
echo json_encode([ "labels"=>$labels, "values"=>$values ]);
