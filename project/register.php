<?php
require_once "config.php";
$passwordIncorrect = false;
$userIncorrect = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $description = isset($_POST["description"]) ? $_POST["description"] : null; //description can be empty

    // Verify passwords are aqual
    if ($password === $password2) {
        echo "Registration successful";
    } else {
        echo "Passwords do not match";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="login.css"/>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="m-4 p-4 rounded shadow bg-light <?php if ($passwordIncorrect || $userIncorrect) echo 'shake';?>">
            <h2>Register</h2>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password2" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" id="password2" name="password2" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description: (Optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Tell the people a bit about yourself!"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>