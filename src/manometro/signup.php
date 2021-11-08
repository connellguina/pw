<?php
session_start();

if ($_SESSION['usuario']) {
    header('Location: index.php');
    exit();
}

if ($_POST['registrar']) {
    if (!$_POST['username'] || !$_POST['password']) {
        $_SESSION['error'] = 'Se necesita usuario y contraseña';
        header('Location: signup.php');
        exit();
    }

    include('db.php');

    $username = mysqli_real_escape_string($con, $_POST['username']);

    $result = mysqli_query($con, "SELECT * FROM usuarios WHERE username = '{$username}'");

    if ($result) {
        $usuarios = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (count($usuarios) === 0) {
            $hash = md5($_POST['password']);

            $result = mysqli_query($con, "INSERT INTO usuarios (username, password) VALUES ('{$username}', '{$hash}')");

            if ($result) {
                $_SESSION['usuario'] = $username;
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = mysqli_error($con);
                header('Location: signup.php');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Usuario ya existe';
            header('Location: signup.php');
            exit();
        }
    } else {
        $_SESSION['error'] = mysqli_error($con);
        header('Location: signup.php');
        exit();
    }
}

include('header.php'); ?>

<div class="container">
    <?php
    if ($_SESSION['error']) {
    ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong> ERROR ! </strong> <?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php
    unset($_SESSION['error']);
    }

    ?>
    <form method="POST" action="signup.php">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" />
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" />
        <input type="submit" value="REGISTRAR" name="registrar" class="btn btn-primary mt-1" />
    </form>
</div>

<?php include('footer.php'); 
session_destroy();
?>