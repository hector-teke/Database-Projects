<?php
    session_start();
    require_once "config.php";
    $username = NULL;
    $info = NULL;
    $passwordIncorrect = false;
    $userIncorrect = false;

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];

        $query = "SELECT username, info FROM users WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $item = mysqli_fetch_assoc($result);
            $username = $item['username'];
            $info = $item['info'];
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } else {
        header("Location: login.php");
        exit();
    }

    if (isset($_POST['deleteAccount'])) {
        $userId = $_SESSION['id'];
    
        $query = "DELETE FROM users WHERE id = $userId";
        mysqli_query($conn, $query);
        $query = "DELETE FROM favorites WHERE user_id = $userId";
        mysqli_query($conn, $query);
        session_destroy();
        header("Location: login.php");
        exit();
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_SESSION['id'];
        
        // Actual information
        $query = "SELECT * FROM users WHERE id = $userId";
        $result = mysqli_query($conn, $query);
    
        if ($result) {
            $userInfo = mysqli_fetch_assoc($result);
    
            // Some fields can be empty if the user don't wanna change them
            $name = isset($_POST["username"]) && $_POST["username"] !== "" ? mysqli_real_escape_string($conn, $_POST["username"]) : $userInfo['username'];
            $password = isset($_POST["password"]) && $_POST["password"] !== "" ? mysqli_real_escape_string($conn, $_POST["password"]) : $userInfo['password'];
            $password2 = isset($_POST["password2"]) && $_POST["password2"] !== "" ? mysqli_real_escape_string($conn, $_POST["password2"]) : $userInfo['password'];
            $description = isset($_POST["description"]) && $_POST["description"] !== "" ? mysqli_real_escape_string($conn, $_POST["description"]) : mysqli_real_escape_string($conn, $userInfo['info']);    

            // Verify passwords are equal
            if ($password === $password2) {

                // Verify if the name is already used
                $query = "SELECT * FROM users WHERE username='$name' AND id != $userId";
                $result = mysqli_query($conn, $query);
        
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        $userIncorrect = true;
                    } else {
                        // Update
                        $query = "UPDATE users SET username='$name', password='$password', info='$description' WHERE id = $userId";
                        $result = mysqli_query($conn, $query);
        
                        if ($result) {
                            header("Location: {$_SERVER['HTTP_REFERER']}");
                            exit();
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }

            } else {
                $passwordIncorrect = true;
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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

                    <div class="card mb-3 <?php if($userIncorrect || $passwordIncorrect){ echo "shake"; }?>">
                    <div class="row g-0">
                        <div class="card-body">
                            <h4 class="card-title">Change Information:</h4>
                            <form action="" method="post">
                                <?php if($_SESSION['id'] != 1){?>
                                <div class="mb-3">
                                    <label for="username" class="form-label">New Username:</label>
                                    <input type="text" class="form-control" id="username" <?php if ($userIncorrect) echo 'placeholder="This user already exists"';?> name="username">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Tell the people a bit about yourself!"></textarea>
                                </div>
                                <?php } ?>
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password:</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="password2" class="form-label">Confirm New Password:</label>
                                    <input type="password" class="form-control" id="password2" <?php if ($passwordIncorrect) echo 'placeholder="Passwords do not match"';?> name="password2">
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="submit" name="deleteAccount" class="btn btn-danger" onclick="return confirm('This action can\'t be undone. Do you really want to delete this account?')">Delete Account</button>
                            </form>
                        </div>
                    </div>
                    </div>

                </div>
            </div>

            <div class="col-md-2">
                
            </div>

            <?php
                $userId = $_SESSION['id'];

                // List of songs id
                $query = "SELECT song_id FROM favorites WHERE user_id = $userId";
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

            <div class="col-md-6">
                <div class="mt-4 overflow-auto" style="max-height: 100vp;">
                    <div class="card mb-6 mb-3">
                        <div class="row g-0">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="favorites-circle">
                                        <span><?php echo $numSongs ?></span>
                                    </div>
                                    <h2 class="ms-1 mb-0 align-middle">Songs</h2>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="favorites-circle">
                                        <span><?php echo $numArtists ?></span>
                                    </div>
                                    <h2 class="ms-1 mb-0 align-middle">Artists</h2>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="favorites-circle">
                                        <span><?php echo $numAlbums ?></span>
                                    </div>
                                    <h2 class="ms-1 mb-0 align-middle">Albums</h2>
                                </div>
                            </div>
                        </div>
                    </div>

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
                </div>
            </div>




        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>