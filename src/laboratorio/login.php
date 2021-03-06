<?php
include 'db.php';
include 'only-not-user.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    if (!$username || !$password) {
        $_SESSION['msg'] = 'INVALID DATA';
        header('Location: signup.php');
        exit;
    } else {
        if ($resultado = mysqli_query($db, "SELECT * FROM  users WHERE username = '$username'")) {
            $user = mysqli_fetch_assoc($resultado);

            if (!password_verify($password, $user['password'])) {
                $_SESSION['msg'] = 'USUARIO O CONTRASEÑA INVÁLIDA';
                header('Location: login.php');
            } else {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
            }
            exit;
        } else {
            header('Location: index.php');
            $_SESSION['msg'] = mysqli_error($db);
            header('Location: login.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <h3>Login</h3>
        <form action="login.php" method="post">
        <?php

        if (isset($_SESSION['msg'])) {
            echo "<div class='alert alert-light' role='alert'>{$_SESSION['msg']}</div>";
        }
        
        ?>
        <input type="text" class="form-control" name="username" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <a href="signup.php">Signup</a><br>
        <input type="submit" class="btn btn-danger" name="login" value="Enviar">
    </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>
