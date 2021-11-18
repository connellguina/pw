<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

include('db.php');

if ($_POST['agregar_medida']) {
    if (!$_POST['lectura'] || !$_POST['id_pozo']) {
        $_SESSION['error'] = 'Se necesita nombre y descripción del pozo';
        header('Location: index.php');
        exit();
    }

    if (!floatval($_POST['lectura']) || $_POST['lectura'] <= 0) {
        $_SESSION['error'] = 'Medida inválida';
        header('Location: index.php');
        exit();
    }

    $id_pozo = $_POST['id_pozo'];

    $result = pg_query($con, "SELECT * FROM manometro_pozos WHERE id = '{$id_pozo}'");

    if ($result) {
        $lectura = $_POST['lectura'];
        $tiempo = date('Y-m-d hh:mm:ss');
        $pozo = pg_fetch_assoc($result, PGSQL_ASSOC);

        if (!$pozo) {
            $_SESSION['error'] = 'El pozo no existe';
            header('Location: index.php');
            exit();
        }

        $result = pg_query($con, 
            "INSERT INTO manometro_medidas (lectura, tiempo, id_pozo) VALUES ('{$lectura}', '{$tiempo}', '{$id_pozo}')"
        );

        if (!$result) {
            $_SESSION['error'] = pg_last_error($con);
            header('Location: index.php');
            exit();
        }


        
    } else {
        if (!$result) {
            $_SESSION['error'] = pg_last_error($con);
            header('Location: index.php');
            exit();
        }
    }

} else {

    $pozo_id = $_GET['pozo'];

    if (!is_numeric($pozo_id)) {
        $_SESSION['error'] = 'ID de pozo inválido';
    
        header('Location: index.php');
        exit();
    }

    $result = pg_query($con, "SELECT * FROM manometro_pozos WHERE id = '$pozo_id'");
    
    if (!$result) {
        $_SESSION['error'] = 'ID de pozo inválido';
    
        header('Location: index.php');
        exit();
    }
    
    $pozo = pg_fetch_all($result);
    
    if (!$pozo) {
        $_SESSION['error'] = 'ID de pozo inválido';
    
        header('Location: index.php');
        exit();
    }
    
    include('header.php');
}
 ?>
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
    <div class="justify-content-between d-flex">
        <h3>Medidas del pozo <?php echo $pozo['nombre']; ?></h3>
        <div>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#agregar-pozo" aria-expanded="false" aria-controls="agregar-pozo">
                Agregar medida
            </a>
        </div>
    </div>
    <div class="collapse p-2" id="agregar-medida">
        <form action="crear_pozo.php" method="POST">
            <label for="lectura" class="form-label">Lectura:</label>
            <input type="number" min="0" class="form-control" name="lectura" />
            <label for="fecha" class="form-label">Fecha:</label>
            <input type="date" class="form-control" name="fecha">
            <label for="hora" class="form-label">Hora:</label>
            <input type="time" class="form-control" name="hora">
            <input type="submit" value="AGREGAR MEDIDA" name="agregar-medida" class="btn btn-success mt-1" />
        </form>
    </div>
    <ul class="list-group">
        <?php
            $result = pg_query($con, "SELECT * FROM manometro_medidas WHERE id_pozo = '$pozo_id'");

            if ($result) {
                $pozos = pg_fetch_all($result, MYSQLI_ASSOC);


                if (count($pozos) === 0) {
                    echo '<li class="list-group-item">No se han registrado medidas</li>';
                } else {
                    foreach ($medidas as $medida) {
                        echo '<li class="list-group-item d-flex justify-content-between">';
                        echo '<a data-bs-toggle="collapse" href="#editar-medida-'.$medida['id'].'" aria-expanded="false" aria-controls="agregar-medida">'.$medida['name'].'</a>';
                        echo '<a href="medidas.php?medida='.$medida['id'].'" class="btn btn-primary">Medidas</a>';
                        echo '<a href="eliminar_medida.php?medida='.$medida['id'].'" class="btn btn-danger">Eliminar</a>';
                        echo '</li>';
                        echo '<div class="collapse p-4 collapse-medida" id="editar-medida-'.$medida['id'].'">';
                        echo '<form action="editar_medida.php" method="POST">';
                        echo '<input type="hidden" name="id" value="'.$medida['id'].'" disabled>';
                        echo '<label for="nombre" class="form-label">Nombre:</label>';
                        echo '<input type="number" class="form-control" name="nombre" value="'.$medida['lectura'].'" disabled />';
                        echo '<label for="fecha" class="form-label">Fecha:</label>';
                        echo ' <input class="form-control" type="date" name="fecha" disabled value="'.$medida['fecha'].'">';
                        echo '<label for="hora" class="form-label">Hora:</label>';
                        echo ' <input class="form-control" type="time" name="hora" disabled value="'.$medida['hora'].'">';
                        echo '<input type="submit" value="EDITAR" name="editar-medida" class="btn btn-success mt-1" disabled  />';
                        echo '</div>';
                    }
                }

                
               
            } else {
                echo '<li class="list-group-item">'.pg_last_error($con).'</li>';
            }
        ?>
    </ul>
</div>

<?php include('footer.php'); ?>