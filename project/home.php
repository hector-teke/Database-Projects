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
    <link rel="stylesheet" type="text/css" href="home.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

    <!-- Upper bar -->
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><?php echo $username; ?></a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Music</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Search Music</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main container -->
    <div class="container-fluid">
        <div class="row">

            <!-- People -->
            <div class="col-md-3">
                <div class="mt-4">
                    <!-- People seach input -->
                    <input type="text" id="searchPeople" class="form-control mb-3" placeholder="Search Users">

                    <!-- People search results -->
                    <div id="resultsPeople" class="overflow-auto" style="max-height: 100vp;"></div>
                </div>
            </div>

            <!-- Music -->
            <div class="col-md-9">
                <!-- Feed  -->
                <div class="mt-4 overflow-auto" style="max-height: 100vp;">
                    
                    <?php
                        require_once "config.php";

                        // Show the last 4 songs added
                        $query = "SELECT * FROM songs ORDER BY id DESC LIMIT 10";
                        $result = mysqli_query($conn, $query);

                        // Verifica si la consulta fue exitosa
                        if ($result) {
                            while ($item = mysqli_fetch_assoc($result)) {
                                // Accede a los datos de cada canción
                                $name = $item['name'];
                                $album = $item['album'];
                                $artist = $item['artist'];
                                $likes = $item['likes'];
                                $link = $item['link'];

                                ?>
                                <div class="card mb-3">
                                <div class="row g-0">

                                    <div class="col-md-3">
                                        <a href="<?php echo $link; ?>" target="_blank">
                                        <img src="<?php echo getYouTubeThumbnail($link); ?>" class="card-img-top" alt="Video Thumbnail">
                                        </a>                                    
                                    </div>

                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $name; ?></h5>
                                            <p class="card-text"><?php echo "$artist - $album"; ?></p>
                                            <p class="card-text"><?php echo "Likes: $likes"; ?></p>
                                        </div>
                                    </div>

                                    <div>
                                        
                                    </div>

                                </div>
                                </div>
                                <?php
                            }

                            // Libera el resultado de la consulta
                            mysqli_free_result($result);
                        } else {
                            // Manejo de error si la consulta no fue exitosa
                            echo "Error al ejecutar la consulta: " . mysqli_error($conn);
                        }

                        // Cierra la conexión a la base de datos
                        mysqli_close($conn);
                    ?>

                </div>
            </div>
        </div>
    </div>

    <script src="searchPeople.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
