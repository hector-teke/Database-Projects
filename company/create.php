<?php
require_once "config.php";

$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];

    if (mysqli_query($conn, "INSERT INTO `Employees` (`name`, `email`) VALUES ('$name', '$email')")) {
        header("location: list.php");
    } else {
        echo "Error";
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
</head>
<body>
    <div class="m-4">
        <h2>Create User</h2>
        <form action="create.php" method="post">
            <div>
                <input type="text" class="form-control" name="name" placeholder="Insert full name here" required>
            </div>

            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Insert email here" required>
            </div>

            <div>
                <input class="btn btn-success" type="submit" value="Submit">
                <a href="list.php" class="btn btn-danger">Back</a>
            </div>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </div>
</body>
</html>