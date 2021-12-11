<?php
header('Content-Type: application/json; charset=utf8');

require 'config.php';
$result = $collection->aggregate(
    array(

        array(

            '$group' => array(
                '_id' => '',
                'max' => array('$max' => '$value'),
                'min' => array('$min' => '$value'),
                'average' => array('$avg' => '$value')

            )
        ),
        array('$limit' => 1),
    )
);


//mengubah data array menjadi format json
echo json_encode(iterator_to_array($result));
