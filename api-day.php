<?php
header('Content-Type: application/json; charset=utf8');

require 'config.php';
// $result = $collection->find(array(), array('limit' => 5));
$result = $collection->aggregate(array(
    array(
        '$project' => array(
            'day' => array('$dayOfYear' => '$executed')
        ),
    ),
    array(
        '$group' => array(
            '_id' => array('day' => '$day'),
            'count' => array('$sum' => 1)
        ),
    ),
    array(
        '$sort' => array(
            '_id' => 1
        ),
    ),
    array(
        '$limit' => 30
    )
));



//mengubah data array menjadi format json
echo json_encode(iterator_to_array($result));
