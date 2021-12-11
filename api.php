<?php
header('Content-Type: application/json; charset=utf8');

require 'config.php';
// $result = $collection->find(array(), array('limit' => 5));
$result = $collection->find(array(), array('limit' => 10, 'sort' => array('ts' => -1)));

//mengubah data array menjadi format json
echo json_encode(iterator_to_array($result));
