<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Manómetro</title>
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-light mb-4" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="index.php">Super Manómetro Bros</a>
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" 
            data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" 
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav me-auto mt-2 mt-lg-0 justify-content-end">
                <?php if ($_SESSION['usuario']) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Log out</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Log In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Sign Up</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>