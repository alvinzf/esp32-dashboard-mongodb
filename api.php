<?php
header('Content-Type: application/json; charset=utf8');

require 'config.php';
$sql = "SELECT value, UNIX_TIMESTAMP(created_at) as ts FROM readings order by created_at";
// $query = mysqli_query($db, $sql);
// $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
// json_encode(array_column($result, 'count'), JSON_NUMERIC_CHECK);
$sth = mysqli_query($db, $sql);
$rows = array();
while ($r = mysqli_fetch_assoc($sth)) {
    $rows[] = $r;
}
$final = json_encode($rows);
print_r(json_decode($final, true));
