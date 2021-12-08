<?php
include 'only-not-user.php';

if ($_POST['signup']) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, password_hash($_POST['password'], PASSWORD_BCRYPT));
    $role = mysqli_real_escape_string($db, $_POST['role']);

    if (!$username || !$password || !$role) {
        $_SESSION['msg'] = 'INVALID DATA';
        header('Location: signup.php');
        exit;
    } else {
        if (!mysqli_query($db, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')")) {
            $_SESSION['msg'] = mysqli_error($db);
            header('Location: signup.php');
            exit;
        } else {
            $_SESSION['user_id'] = mysqli_insert_id($db);
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

    <form action="signup.php" method="post">
        <?php

        if ($_SESSION['msg']) {
            echo "<div class='alert alert-light' role='alert'>{$_SESSION['msg']}</div>";
        }

        ?>
        <input type="text" class="form-control" name="username" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <select name="rol" class="form-select" required>
            <option value="">Select role</option>
            <option value="nurse">Nurse</option>
            <option value="doctor">Doctor</option>
        </select>
        <input type="submit" class="btn btn-danger" name="signup" value="Enviar">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>