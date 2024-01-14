<?php
require_once "config.php";

$name = "";
$email = "";

if (isset($_POST["id"]) && !empty($_POST["id"])) {

    $id = $_POST["id"];

    $name = $_POST["name"];
    $email = $_POST["email"];

    if (mysqli_query($conn, "UPDATE `Employees` SET `name`= '$name', `email`= '$email' WHERE id='$id'")) {
        header("location: list.php");
    } else {
        echo "Error";
    }
    

    mysqli_close($conn);
} else {
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);
        $query = mysqli_query($conn, "SELECT * FROM Employees WHERE Id = '$id'");

        if ($item = mysqli_fetch_assoc($query)) {
            $name   = $item["Name"];
            $email    = $item["Email"];
        } else {
            echo "Error";
            header("location: edit.php");
            exit();
        }
        mysqli_close($conn);
    }  else {
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
    <title>Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    <div class="m-4">
        <h2>Edit</h2>
        <form action="edit.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>

            <div>
                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" placeholder="Insert full name here" required>
            </div>

            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" placeholder="Insert email here" required>
            </div>

            <div>
                <input class="btn btn-success" type="submit" value="Submit">
                <a href="list.php" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>