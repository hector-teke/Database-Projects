<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Songs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
</head>
<body>
    <div class="m-4">
        <div class="wrapper">
            <div class="row">
                <div>

                    <?php
                        require_once "config.php";
                        $query = "SELECT * FROM songs";

                        if ($songs = mysqli_query($conn, $query)){
                            
                            if (mysqli_num_rows($songs) > 0) {
                                ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
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
                                                echo "<td>$id</td>";
                                                echo "<td>" . $song["name"] . "</td>";
                                                echo "<td>" . $song["album"] . "</td>";
                                                echo "<td>" . $song["artist"] ."</td>";
                                                echo "<td>" . $song["likes"] . "</td>";
                                                echo "<td> <a href='" . $song["link"] ."' title='YouTube' data-toggle='tooltip'> <span class='fab fa-youtube'> </span></a> </td>";
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>