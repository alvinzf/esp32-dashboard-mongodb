<?php
session_start();
function insertReading($sensorId, $value)
{
    require 'config.php';
    $now = date('Y-m-d H:i:s');
    $orig_date = (new DateTime($now))->getTimestamp();
    $mongo_date = new MongoDB\BSON\UTCDateTime($orig_date * 1000);
    $collection->insertOne([
        'sensorId' => $sensorId,
        'value' => (int) $value,
        'ts' => $mongo_date,
    ]);
    $_SESSION['success'] = 'Readings created successfully';
}

function getAllReadings()
{
    require 'config.php';
    $data = $collection->find([]);
}
// function getLastReadings()
// {
//     // Create connection
//     $conn = new mysqli($servername, $username, $password, $dbname);
//     // Check connection
//     if ($conn->connect_error) {
//         die('Connection failed: ' . $conn->connect_error);
//     }

//     $sql =
//         'SELECT id, sensor, value, reading_time FROM sensordata order by reading_time desc limit 1';
//     if ($result = $conn->query($sql)) {
//         return $result->fetch_assoc();
//     } else {
//         return false;
//     }
//     $conn->close();
// }

// function minReading($limit, $value)
// {
//     // Create connection
//     $conn = new mysqli($servername, $username, $password, $dbname);
//     // Check connection
//     if ($conn->connect_error) {
//         die('Connection failed: ' . $conn->connect_error);
//     }

//     $sql =
//         'SELECT MIN(' .
//         $value .
//         ') AS min_amount FROM (SELECT ' .
//         $value .
//         ' FROM sensordata order by reading_time desc limit ' .
//         $limit .
//         ') AS min';
//     if ($result = $conn->query($sql)) {
//         return $result->fetch_assoc();
//     } else {
//         return false;
//     }
//     $conn->close();
// }

// function maxReading($limit, $value)
// {
//     // Create connection
//     $conn = new mysqli($servername, $username, $password, $dbname);
//     // Check connection
//     if ($conn->connect_error) {
//         die('Connection failed: ' . $conn->connect_error);
//     }

//     $sql =
//         'SELECT MAX(' .
//         $value .
//         ') AS max_amount FROM (SELECT ' .
//         $value .
//         ' FROM sensordata order by reading_time desc limit ' .
//         $limit .
//         ') AS max';
//     if ($result = $conn->query($sql)) {
//         return $result->fetch_assoc();
//     } else {
//         return false;
//     }
//     $conn->close();
// }

// function avgReading($limit, $value)
// {
//     // Create connection
//     $conn = new mysqli($servername, $username, $password, $dbname);
//     // Check connection
//     if ($conn->connect_error) {
//         die('Connection failed: ' . $conn->connect_error);
//     }

//     $sql =
//         'SELECT AVG(' .
//         $value .
//         ') AS avg_amount FROM (SELECT ' .
//         $value .
//         ' FROM sensordata order by reading_time desc limit ' .
//         $limit .
//         ') AS avg';
//     if ($result = $conn->query($sql)) {
//         return $result->fetch_assoc();
//     } else {
//         return false;
//     }
//     $conn->close();
// }
?>
