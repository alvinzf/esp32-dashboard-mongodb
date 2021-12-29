<?php
session_start();


function insertReading($sensorId, $value, $classification)
{
    require 'config.php';

    $sql = "INSERT INTO readings (sensor, value, classification) VALUES ('" . $sensorId . "', '" . $value . "', '" . $classification . "')";
    if ($db->query($sql) === TRUE) {
        return "New record created successfully";
    } else {
        return "Error: " . $sql . "<br>" . $db->error;
    }
}

function getAllReadings()
{
    require 'config.php';
    $data = $collection->find([]);
}
