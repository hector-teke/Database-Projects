
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
            <a class="navbar-brand" href="#">Username</a>
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
                    <!-- Contenido del feed musical -->
                    <!-- Aquí puedes colocar las publicaciones, canciones, etc. -->
                </div>
            </div>
        </div>
    </div>

    <script src="searchPeople.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
