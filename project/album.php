<?php
    session_start();
    require_once "config.php";
    $song_id = NULL;
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
    }

    if (isset($_GET['songId'])) {
        $song_id = $_GET['songId'];
        $query = "SELECT * FROM songs WHERE id = $song_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $item = mysqli_fetch_assoc($result);
            $name = $item['name'];
            $album = $item['album'];
            $artist = $item['artist'];
            $likes = $item['likes'];
            $link = $item['link'];
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        $query = "SELECT * FROM favorites WHERE user_id = {$_SESSION['id']} AND song_id = {$song_id}";
        $isFavorite = mysqli_num_rows(mysqli_query($conn, $query)) > 0;
    } else {
        header("Location: home.php");
        exit();;
    }


    if (isset($_POST['deleteSong'])) {
        $song_id = $_POST["songId"];
        
        mysqli_query($conn, "DELETE FROM songs WHERE id = '$song_id'");
        mysqli_query($conn, "DELETE FROM favorites WHERE song_id = '$song_id'");

        header("Location: home.php");
            exit();

    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $song_id = $_POST["songId"];
    
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $artist = mysqli_real_escape_string($conn, $_POST["artist"]);
        $album =  mysqli_real_escape_string($conn, $_POST["album"]);
        $link = mysqli_real_escape_string($conn, $_POST["link"]);    

        // Update
        $query = "UPDATE songs SET name='$name', artist='$artist', album='$album', link='$link' WHERE id = $song_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
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
    <title><?php echo $name?></title>
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
                        <a class="nav-link" href="allmusic.php">All Music</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="music.php">My Music</a>
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

            <!-- Song Info -->
            <div class="col-md-3">
                <div class="mt-4 overflow-auto" style="max-height: 100vp;">
                    
                    <div class="card mb-3 top-likes-card">
                        <div class="row g-0">

                            <div class="col-md-10">
                                <div class="card-body">
                                    <h2 class="card-title text-white"><?php echo $name; ?></h2>
                                    <h4 class="card-text text-white"><?php echo "$artist - $album"; ?></h4>
                                    <p class="card-text text-white"><i class="fas fa-thumbs-up"></i><?php echo " Likes: $likes"; ?></p>
                                </div>
                            </div>

                            <!-- Creates the hearts with the id off the song -->
                            <div class="col-md-2 d-flex align-items-end justify-content-center mb-3">
                                <?php if ($isFavorite) : ?>
                                    <i class="fas fa-heart fs-4 text-danger favorite-btn-up heartbeat" data-id="<?php echo $song_id; ?>"></i>
                                <?php else : ?>
                                    <i class="far fa-heart fs-4 favorite-btn-up" data-id="<?php echo $item['id']; ?>"></i>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                    <?php if($_SESSION['id'] == 1){?>
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="card-body">
                                <h4 class="card-title">Change Information:</h4>
                                <form action="" method="post">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Name:</label>
                                        <input type="text" value="<?php echo $name ?>" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Artist:</label>
                                        <input type="text" value="<?php echo $artist ?>" class="form-control" id="artist" name="artist" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Album:</label>
                                        <input type="text" value="<?php echo $album ?>" class="form-control" id="album" name="album" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Link:</label>
                                        <input type="text" value="<?php echo $link ?>" class="form-control" id="link" name="link" required>
                                    </div>
                                    <input type="hidden" name="songId" value="<?php echo $song_id; ?>">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="submit" name="deleteSong" class="btn btn-danger" onclick="return confirm('This action can\'t be undone. Do you really want to delete this song?')">Delete Song</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php } 
                    
                    // List of songs in the same album
                    $query = "SELECT name, id FROM songs WHERE album = '$album' AND artist = '$artist' AND name != '$name' ORDER BY name";
                    $result = mysqli_query($conn, $query);
                    
                    if (mysqli_num_rows($result) > 0) {
                    ?>

                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="card-body">
                                    <h4 class="card-title">More From <?php echo $album ?>:</h4>
                                    <?php 
                                        while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                            <a style="text-decoration: none;" href="album.php?songId=<?php echo $row['id'] ?>">
                                            <div class="card mb-1">
                                                <div class="row g-0">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?php echo $row['name'] ?></h5>
                                                    </div>
                                                </div>
                                            </div> 
                                            </a>       
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    <?php }

                    // List of songs of the same artist
                    $query = "SELECT name, id FROM songs WHERE artist = '$artist' AND name != '$name' ORDER BY name";
                    $result = mysqli_query($conn, $query);
                    
                    if (mysqli_num_rows($result) > 0) {
                    ?>

                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="card-body">
                                    <h4 class="card-title">All From <?php echo $artist ?>:</h4>
                                    <?php 
                                        while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                            <a style="text-decoration: none;" href="album.php?songId=<?php echo $row['id'] ?>">
                                            <div class="card mb-1">
                                                <div class="row g-0">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?php echo $row['name'] ?></h5>
                                                    </div>
                                                </div>
                                            </div> 
                                            </a>       
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>
            </div>

            <div class="col-md-1">

            </div>

            <div class="col-md-7">
                <div class="mt-4 overflow-auto" style="max-height: 100vp;">
                    <div>
                        <div class="row g-0">

                            <iframe height="650" src="https://www.youtube.com/embed/<?php echo getYouTubeVideoId($link)?>" allowfullscreen frameborder="0"></iframe>                                   
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="likes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>