<?php
    session_start();
    require_once "config.php";
    $visitor = NULL;

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];

        $query = "SELECT username FROM users WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $item = mysqli_fetch_assoc($result);
            $visitor = $item['username'];
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    if (isset($_GET['userId'])) {
        $user_id = $_GET['userId'];
        $query = "SELECT * FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $item = mysqli_fetch_assoc($result);
            $username = $item['username'];
            $info = $item['info'];
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        header("Location: home.php");
        exit();;
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
    <title><?php echo $username?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" type="text/css" href="home.css"/>
</head>
<body>

    <!-- Upper bar -->
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand navbar-item" href="profile.php"><?php echo $visitor; ?></a>
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

            <!-- Personal Info -->
            <div class="col-md-3">
                <div class="mt-4 overflow-auto" style="max-height: 100vp;">
                    
                    <div class="card mb-3">
                    <div class="row g-0">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $username ?></h4>
                            <p class="card-text"><?php echo $info ?></p>
                        </div>
                    </div>
                    </div>

                    <?php
                        // List of songs id
                        $query = "SELECT song_id FROM favorites WHERE user_id = $user_id ORDER BY id";
                        $result = mysqli_query($conn, $query);
                    
                        if ($result) {
                            $songIds = [];
                            while ($row = mysqli_fetch_assoc($result)) {
                                $songIds[] = $row['song_id'];   //Save the songs in an array
                            }
                            $numSongs = count($songIds);
                            $numArtists = 0;
                            $numAlbums = 0;
                            $favArtist = "Unknown";
                            $lastSong = "Unknown";
                            $lastArtist = "Unknown";
                            $lastAlbum = "Unknown";
                    
                            if($numSongs > 0) {
                                // Number of albums
                                $query = "SELECT COUNT(DISTINCT album) AS numAlbums FROM songs WHERE id IN (" . implode(',', $songIds) . ")";
                                $result = mysqli_query($conn, $query);
                                $numAlbums = ($result) ? mysqli_fetch_assoc($result)['numAlbums'] : 0;
                        
                                // Number of artists
                                $query = "SELECT COUNT(DISTINCT artist) AS numArtists FROM songs WHERE id IN (" . implode(',', $songIds) . ")";
                                $result = mysqli_query($conn, $query);
                                $numArtists = ($result) ? mysqli_fetch_assoc($result)['numArtists'] : 0;

                                // Favorite artist
                                $query = "SELECT artist, COUNT(*) AS numSongs FROM songs WHERE id IN (" . implode(',', $songIds) . ")
                                GROUP BY artist ORDER BY numSongs DESC LIMIT 1;";
                                $result = mysqli_query($conn, $query);
                                $favArtist = ($result) ? mysqli_fetch_assoc($result)['artist'] : "Unknown";

                                // Last added song
                                $query = "SELECT * FROM songs WHERE id = '" . $songIds[$numSongs - 1] . "';";
                                $result = mysqli_query($conn, $query);
                                $lastSongInfo = ($result) ? mysqli_fetch_assoc($result) : null;

                                $lastSong = ($lastSongInfo) ? $lastSongInfo['name'] : "Unknown";
                                $lastArtist = ($lastSongInfo) ? $lastSongInfo['artist'] : "Unknown";
                                $lastAlbum = ($lastSongInfo) ? $lastSongInfo['album'] : "Unknown";
                            }
                        }
                    ?>

                    <div class="card mb-6 mb-3 top-likes-card text-white">
                        <div class="row g-0">
                            <div class="card-body">
                                <h4 class="card-title">Favorite Artist:</h4>
                                <h1 class="card-title"><?php echo $favArtist ?></h1>
                                <br>
                                <h4 class="card-title">Last Added Song:</h4>
                                <h1 class="card-title"><?php echo $lastSong ?></h1>
                                <p class="card-text"><?php echo $lastArtist . " - " . $lastAlbum ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-6 mb-3">
                        <div class="row g-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="favorites-circle">
                                        <span><?php echo $numSongs ?></span>
                                    </div>
                                    <h2 class="ms-1 mb-0 align-middle">Songs</h2>
                                </div>
                                <br>
                                <div class="d-flex align-items-center">
                                    <div class="favorites-circle">
                                        <span><?php echo $numArtists ?></span>
                                    </div>
                                    <h2 class="ms-1 mb-0 align-middle">Artists</h2>
                                </div>
                                <br>
                                <div class="d-flex align-items-center">
                                    <div class="favorites-circle">
                                        <span><?php echo $numAlbums ?></span>
                                    </div>
                                    <h2 class="ms-1 mb-0 align-middle">Albums</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- List of music -->
            <div class="col-md-9">
                <div class="mt-4 overflow-auto" style="max-height: 100vp;">
                    
                    <?php

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

                            $query = "SELECT * FROM favorites WHERE user_id = {$user_id} AND song_id = {$item['id']}";
                            $isFavorite = mysqli_num_rows(mysqli_query($conn, $query)) > 0;

                            $query = "SELECT * FROM favorites WHERE user_id = {$_SESSION['id']} AND song_id = {$item['id']}";
                            $visitorFavorite = mysqli_num_rows(mysqli_query($conn, $query)) > 0;

                            if ($isFavorite) {
                            ?>
                            <div class="card mb-3">
                            <div class="row g-0">

                                <div class="col-md-1 d-flex align-items-center">
                                    <a href="album.php?songId=<?php echo $item['id'] ?>">
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

                                <!-- Creates the hearts with the id off the song -->
                                <div class="col-md-2">
                                    <div class="card-body d-flex align-items-center justify-content-end">
                                        <?php if ($visitorFavorite) : ?>
                                            <i class="fas fa-heart fs-4 text-danger favorite-btn heartbeat" data-id="<?php echo $item['id']; ?>"></i>
                                        <?php else : ?>
                                            <i class="far fa-heart fs-4 favorite-btn" data-id="<?php echo $item['id']; ?>"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>

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
    </div>

    <script src="likes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
