<?php
    session_start();
    require_once "config.php";
    $username = NULL;

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];

        $query = "SELECT username FROM users WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $item = mysqli_fetch_assoc($result);
            $username = $item['username'];
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } else {
        header("Location: login.php");
        exit();
    }

    function getYouTubeThumbnail($youtubeLink) {
        $videoId = getYouTubeVideoId($youtubeLink);
        $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/0.jpg";
        return $thumbnailUrl;
    }
    
    function getYouTubeVideoId($youtubeLink) {
        $videoId = '';
        parse_str(parse_url($youtubeLink, PHP_URL_QUERY), $params);
        if (isset($params['v'])) {
            $videoId = $params['v'];
        }
        return $videoId;
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" type="text/css" href="home.css"/>
</head>
<body>

    <!-- Upper bar -->
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand navbar-item" href="profile.php"><?php echo $username; ?></a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white">My Music</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artist.php">My Artists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="album.php">My Albums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Log-out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main container -->
    <div class="container-fluid" style="margin-top: 50px;">
        <div class="row">
        <div class="mt-4 overflow-auto" style="max-height: 100vp;">
            <?php
            $query = "SELECT * FROM songs";

            if ($songs = mysqli_query($conn, $query)){
                
                if (mysqli_num_rows($songs) > 0) {
                    ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Album</th>
                                <th>Artist</th>
                                <th>Likes</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($song = mysqli_fetch_array($songs)){
                                    $id = $song["id"];

                                    echo "<tr>";
                                    echo "<td>" . $song["name"] . "</td>";
                                    echo "<td>" . $song["album"] . "</td>";
                                    echo "<td>" . $song["artist"] ."</td>";
                                    echo "<td>" . $song["likes"] . "</td>";
                                    echo "<td><a href='" . $song["link"] . "' target='_blank'><img src='" . getYouTubeThumbnail($song["link"]) . "' class='card-img-top rounded' alt='Video Thumbnail'></a></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "<p><em>No records found</em></p>";
                }

            } else {
                echo "<label>ERROR: Could not execute $query" . mysqli_error($conn) . "</label>";
            }

            mysqli_close($conn);
            ?>
        </div>
        </div>
    </div>

    <script src="likes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
