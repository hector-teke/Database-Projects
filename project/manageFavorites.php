<?php
session_start();
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $songId = isset($_POST['songId']) ? mysqli_real_escape_string($conn, $_POST['songId']) : null;
    $userId = isset($_SESSION['id']) ? mysqli_real_escape_string($conn, $_SESSION['id']) : null;

    // Is the song between user's favorites?
    $query = "SELECT * FROM favorites WHERE user_id = $userId AND song_id = $songId";
    $result = mysqli_query($conn, $query);

    if ($userId != 1 && $result) {
        if (mysqli_num_rows($result) > 0) {
            // Favorite -> Then delete from table
            $query = "DELETE FROM favorites WHERE user_id = $userId AND song_id = $songId";
            mysqli_query($conn, $query);
            $response = ['isFavorite' => false];

            $queryIncrement = "UPDATE songs SET likes = likes - 1 WHERE id = $songId";
            mysqli_query($conn, $queryIncrement);
        } else {
            // Not Favorite -> Then add the relation to table
            $query = "INSERT INTO favorites (user_id, song_id) VALUES ($userId, $songId)";
            mysqli_query($conn, $query);
            $response = ['isFavorite' => true];

            $queryIncrement = "UPDATE songs SET likes = likes + 1 WHERE id = $songId";
            mysqli_query($conn, $queryIncrement);
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

