<?php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $songId = isset($_POST['songId']);
    $userId = isset($_SESSION['id']);

    // Is the song between user's favorites?
    $query = "SELECT * FROM favorites WHERE user_id = $userId AND song_id = $songId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Favorite -> Then delete from table
            $query = "DELETE FROM favorites WHERE user_id = $userId AND song_id = $songId";
            mysqli_query($conn, $query);
            $response = ['isFavorite' => false];
        } else {
            // Not Favorite -> Then add the relation to table
            $query = "INSERT INTO favorites (user_id, song_id) VALUES ($userId, $songId)";
            mysqli_query($conn, $query);
            $response = ['isFavorite' => true];
        }

        // Send JSON with the boolean isFavorite
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

header('HTTP/1.1 500 Internal Server Error');
exit();
?>
