<?php
session_start();
function insertReading($sensorId, $value, $classification)
{
    require 'config.php';
    $now = date('Y-m-d H:i:s');
    $orig_date = (new DateTime($now))->getTimestamp();
    $mongo_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
    $collection->insertOne([
        'sensorId' => $sensorId,
        'value' => (int) $value,
        'class' => $classification,
        'ts' => $mongo_date,
    ]);
    $_SESSION['success'] = 'Readings created successfully';
}

function getAllReadings()
{
    require 'config.php';
    $data = $collection->find([]);
}
