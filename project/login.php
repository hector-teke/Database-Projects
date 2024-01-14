<?php
require_once "config.php";
session_start();

$passwordIncorrect = false;
$userIncorrect = false;

// Verify if a form was sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prevent SQL inyections
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM users WHERE username='$username'";

    if ($result = mysqli_query($conn, $query)) {
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result); //All user information

            // Compare the password
            if ($password === $user['password']) {
                $_SESSION['id'] = $user['id'];
                header("Location: home.php");
                exit();

            } else {
                $passwordIncorrect = true;
            }
        } else {
            $userIncorrect = true;
        }

    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="login.css"/>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="m-4 p-4 rounded shadow bg-light <?php if ($passwordIncorrect || $userIncorrect) echo 'shake';?>">
            <h2>Login</h2>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" <?php if ($userIncorrect) echo 'placeholder="User not found"';?> name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" <?php if ($passwordIncorrect) echo 'placeholder="Wrong password"';?> name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="register.php" class="btn btn-success">Register</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>