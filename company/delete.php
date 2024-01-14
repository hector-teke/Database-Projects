<?php

if (isset($_POST["id"]) && !empty($_POST["id"])) {
    require_once "config.php";
    $id = $_POST["id"];

    if (mysqli_query($conn, "DELETE FROM Employees WHERE Id = '$id'")) {
        header("location: list.php");
    } else {
         echo "Error";
    }

    mysqli_close($conn);
} else {
    if (empty($_GET["id"])) {
        echo "Error";
        header("location: list.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    <?php
    

    if (isset($_GET["id"]) && !empty($_GET["id"])) {
        require_once "config.php";
    
        $id = trim($_GET["id"]);
        $query = mysqli_query($conn, "SELECT * FROM Employees WHERE Id = '$id'");
    
        if ($item = mysqli_fetch_assoc($query)) {
            $name   = $item["Name"];
            $email  = $item["Email"];
        } else {
            header("location: list.php");
            exit();
        }
    
        mysqli_close($conn);
    } else {
        header("location: list.php");
        exit();
    }
    ?>

    <div class="m-4">
        <h1>The following row will be deleted</h1>
        <form action="delete.php" method="post">
            <div>
                <div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                </div>
                <div>
                    <label><b>Name: </b><?php echo $name ?></p>
                </div>
                <div class="form-group">
                    <label><b>Email: </b><?php echo $email ?></p>
                </div>
                <div>
                    <input class="btn btn-success" type="submit" value="Delete">
                    <a href="list.php" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>