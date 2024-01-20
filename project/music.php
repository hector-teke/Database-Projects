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

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Delete
        if (isset($_POST["songId"]) && !empty($_POST["songId"])) {
            $song_id = $_POST["songId"];
        
            mysqli_query($conn, "DELETE FROM songs WHERE id = '$song_id'");
            mysqli_query($conn, "DELETE FROM favorites WHERE song_id = '$song_id'");
        } else {
            // Add Song
            $name = mysqli_real_escape_string($conn, $_POST["name"]);
            $artist = mysqli_real_escape_string($conn, $_POST["artist"]);
            $album = mysqli_real_escape_string($conn, $_POST["album"]);
            $link = mysqli_real_escape_string($conn, $_POST["link"]);

            $query = "INSERT INTO songs (name, artist, album, link) VALUES ('$name', '$artist', '$album', '$link')";
            mysqli_query($conn, $query);
        }
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
    <title>Music</title>
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
                        <a class="nav-link" href="profile.php">My Profile</a>
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
                        if($_SESSION['id']==1){
                            ?>
                            <form action="music.php" method="post">
                                <div class="card mb-3">
                                    <div class="row g-0">

                                        <div class="col-md-3">
                                            <div class="card-body">
                                                <input type="text" placeholder="Name" class="form-control" id="name" name="name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="card-body">
                                                <input type="text" placeholder="Artist" class="form-control" id="artist" name="artist" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="card-body">
                                                <input type="text" placeholder="Album" class="form-control" id="album" name="album" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card-body">
                                                <input type="text" placeholder="Link" class="form-control" id="link" name="link" required>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="card-body">
                                                <button type="submit" class="btn btn-primary">Add Song</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>

                            <?php
                        }

                        require_once "config.php";

                        $query = "SELECT * FROM songs ORDER BY name";
                        $result = mysqli_query($conn, $query);

                        if ($result) {
                            while ($item = mysqli_fetch_assoc($result)) {
                                
                                $name = $item['name'];
                                $album = $item['album'];
                                $artist = $item['artist'];
                                $likes = $item['likes'];
                                $link = $item['link'];

                                $query = "SELECT * FROM favorites WHERE user_id = {$_SESSION['id']} AND song_id = {$item['id']}";
                                $isFavorite = mysqli_num_rows(mysqli_query($conn, $query)) > 0;

                                if ($isFavorite || $_SESSION['id']==1) {
                                ?>
                                <div class="card mb-3">
                                <div class="row g-0">

                                    <div class="col-md-1 d-flex align-items-center">
                                        <a href="<?php echo $link; ?>" target="_blank">
                                        <img src="<?php echo getYouTubeThumbnail($link); ?>" class="card-img-top rounded" height=70px alt="Video Thumbnail">
                                        </a>                                    
                                    </div>

                                    <div class="col-md-3 d-flex align-items-center justify-content-end">
                                        <div class="card-body">
                                            <a style="text-decoration: none; color: black;" href="album.php?songId=<?php echo $item['id'] ?>"><h4 class="card-title"><?php echo $name; ?></h4></a>
                                        </div>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-center justify-content-end">
                                        <div class="card-body">
                                            <h5 class="card-text"><?php echo "$artist"; ?></h5>
                                        </div>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-center justify-content-end">
                                        <div class="card-body">
                                            <h5 class="card-text"><?php echo "$album"; ?></h5>
                                        </div>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-center justify-content-end">
                                        <div class="card-body">
                                            <h5 class="card-text"><i class="fas fa-thumbs-up"></i><?php echo " Likes: $likes"; ?></h5>
                                        </div>
                                    </div>

                                    <?php if ($_SESSION['id']!=1){ ?>
                                    <!-- Creates the hearts with the id off the song -->
                                    <div class="col-md-2">
                                        <div class="card-body d-flex align-items-center justify-content-end">
                                            <i class="fas fa-heart fs-4 text-danger favorite-btn heartbeat" data-id="<?php echo $item['id']; ?>"></i>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                        <div class="col-md-2">
                                        <div class="card-body d-flex align-items-center justify-content-end">
                                        <form method="POST" action="music.php">
                                            <input type="hidden" name="songId" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>

                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>
                                </div>
                                <?php
                                }
                            }

                        } else {
                            echo "Error: " . mysqli_error($conn);
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
