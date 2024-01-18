<?php

require_once "config.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = mysqli_real_escape_string($conn, $search);

$query = "SELECT id, name, album, artist, likes, link FROM songs WHERE name LIKE '%$search%' OR album LIKE '%$search%' OR artist LIKE '%$search%' ORDER BY likes DESC LIMIT 15";
$result = mysqli_query($conn, $query);

//Put the results into an array
$songs = [];
while ($item = mysqli_fetch_assoc($result)) {
    $songs[] = $item;
}

// Encode to json before send
header('Content-Type: application/json');
echo json_encode($songs);

mysqli_close($conn);
?>