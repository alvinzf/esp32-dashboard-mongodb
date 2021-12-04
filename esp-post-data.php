<?php
include_once 'esp-database.php';

// Keep this API Key value to be compatible with the ESP code provided in the project page. If you change this value, the ESP sketch needs to match
$api_key_value = 'tPmAT5Ab3j7F9';

$api_key = $sensor = $value =  '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $api_key = test_input($_POST['api_key']);
    if ($api_key == $api_key_value) {
        $sensor = test_input($_POST['sensor']);
        $value = test_input($_POST['value']);
        $classification = test_input($_POST['classification']);
        $result = insertReading($sensor, $value, $classification);
        echo $result;
    } else {
        echo 'Wrong API Key provided.';
    }
} else {
    echo 'No data posted with HTTP POST.';
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
