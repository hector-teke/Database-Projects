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
                        <a class="nav-link text-white">Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="music.php">My Music</a>
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

            <!-- People -->
            <div class="col-md-3">
                <div class="mt-4">
                    <!-- People seach input -->
                    <input type="text" id="searchPeople" class="form-control mb-3" placeholder="Search Users">

                    <!-- People search results -->
                    <div id="resultsPeople" class="overflow-auto" style="max-height: 100vp;">

                    </div>
                </div>
            </div>

            <!-- Feed -->
            <div class="col-md-6">
                <div class="mt-4 overflow-auto" style="max-height: 100vp;">
                    <?php
                        require_once "config.php";
                        
                        $query = "SELECT * FROM songs ORDER BY RAND() LIMIT 1";
                        $result = mysqli_query($conn, $query);

                        if ($result) {
                            if ($item = mysqli_fetch_assoc($result)) {
                                
                                $name = $item['name'];
                                $album = $item['album'];
                                $artist = $item['artist'];
                                $likes = $item['likes'];
                                $link = $item['link'];

                                $query = "SELECT * FROM favorites WHERE user_id = {$_SESSION['id']} AND song_id = {$item['id']}";
                                $isFavorite = mysqli_num_rows(mysqli_query($conn, $query)) > 0;

                                ?>
                                <div class="card mb-3 top-likes-card">
                                <div class="row g-0">

                                    <div class="col-md-4">
                                        <a href="<?php echo $link; ?>" target="_blank">
                                        <img src="<?php echo getYouTubeThumbnail($link); ?>" class="card-img-top rounded" alt="Video Thumbnail">
                                        </a>                                    
                                    </div>

                                    <div class="col-md-7">
                                        <div class="card-body">
                                            <h2 class="card-title text-white"><?php echo $name; ?></h2>
                                            <h4 class="card-text text-white"><?php echo "$artist - $album"; ?></h4>
                                            <p class="card-text text-white"><i class="fas fa-thumbs-up"></i><?php echo " Likes: $likes"; ?></p>
                                        </div>
                                    </div>

                                    <!-- Creates the hearts with the id off the song -->
                                    <div class="col-md-1 d-flex align-items-end justify-content-center mb-3">
                                        <?php if ($isFavorite) : ?>
                                            <i class="fas fa-heart fs-4 text-danger favorite-btn-up heartbeat" data-id="<?php echo $item['id']; ?>"></i>
                                        <?php else : ?>
                                            <i class="far fa-heart fs-4 favorite-btn-up" data-id="<?php echo $item['id']; ?>"></i>
                                        <?php endif; ?>
                                    </div>

                                </div>
                                </div>
                                <?php
                            }

                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    ?>

                    <h2>Last Added:</h2>
                    <?php
                        require_once "config.php";

                        // Show the last songs added
                        $query = "SELECT * FROM songs ORDER BY id DESC LIMIT 10";
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
                                ?>
                                <div class="card mb-3">
                                <div class="row g-0">

                                    <div class="col-md-3 d-flex align-items-center">
                                        <a href="<?php echo $link; ?>" target="_blank">
                                        <img src="<?php echo getYouTubeThumbnail($link); ?>" class="card-img-top rounded" alt="Video Thumbnail">
                                        </a>                                    
                                    </div>

                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $name; ?></h5>
                                            <p class="card-text"><?php echo "$artist - $album"; ?></p>
                                            <p class="card-text"><i class="fas fa-thumbs-up"></i><?php echo " Likes: $likes"; ?></p>
                                        </div>
                                    </div>

                                    <!-- Creates the hearts with the id off the song -->
                                    <div class="col-md-1 d-flex align-items-end justify-content-center mb-3">
                                        <?php if ($isFavorite) : ?>
                                            <i class="fas fa-heart fs-4 text-danger favorite-btn heartbeat" data-id="<?php echo $item['id']; ?>"></i>
                                        <?php else : ?>
                                            <i class="far fa-heart fs-4 favorite-btn" data-id="<?php echo $item['id']; ?>"></i>
                                        <?php endif; ?>
                                    </div>

                                </div>
                                </div>
                                <?php
                            }

                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }

                        mysqli_close($conn);
                    ?>

                </div>
            </div>

            <!-- Music -->
            <div class="col-md-3">
                <div class="mt-4">
                    <!-- Music seach input -->
                    <input type="text" id="searchMusic" class="form-control mb-3" placeholder="Search Music (name, artist or album)">

                    <!-- Music search results -->
                    <div id="resultsMusic" class="overflow-auto" style="max-height: 100vp;">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="likes.js"></script>
    <script src="searchPeople.js"></script>
    <script src="searchMusic.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
