<?php

require_once "config.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = mysqli_real_escape_string($conn, $search);

$query = "SELECT id, username, info FROM users WHERE id != 1 AND username LIKE '%$search%'";
$result = mysqli_query($conn, $query);

//Put the results into an array
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Encode to json before send
header('Content-Type: application/json');
echo json_encode($users);

mysqli_close($conn);
?>
