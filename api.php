<?php
header('Content-Type: application/json; charset=utf8');

require 'config.php';
$result = $collection->find(array(), array('limit' => 700));

//mengubah data array menjadi format json
echo json_encode(iterator_to_array($result));
