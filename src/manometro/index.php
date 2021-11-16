<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}


include('db.php');

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
    <div class="justify-content-between d-flex">
        <h3>Pozos</h3>
        <div>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#agregar-pozo" aria-expanded="false" aria-controls="agregar-pozo">
                Agregar pozo
            </a>
        </div>
    </div>
    <div class="collapse p-2" id="agregar-pozo">
        <form action="crear_pozo.php" method="POST">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" />
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" name="descripcion"></textarea>
            <input type="submit" value="REGISTRAR" name="crear-pozo" class="btn btn-success mt-1" />
        </form>
    </div>
    <ul class="list-group">
        <?php
            $result = pg_query($con, "SELECT * FROM manometro_pozos");

            if ($result) {
                $pozos = pg_fetch_all($result, MYSQLI_ASSOC);


                if (count($pozos) === 0) {
                    echo '<li class="list-group-item">No se han registrado pozos</li>';
                } else {
                    foreach ($pozos as $pozo) {
                        echo '<li class="list-group-item d-flex justify-content-between">';
                        echo '<a data-bs-toggle="collapse" href="#editar-pozo-'.$pozo['id'].'" aria-expanded="false" aria-controls="agregar-pozo">'.$pozo['name'].'</a>';
                        echo '<a href="medidas.php?pozo='.$pozo['id'].'" class="btn btn-primary">Medidas</a>';
                        echo '<a href="eliminar_pozo.php?pozo='.$pozo['id'].'" class="btn btn-danger">Eliminar</a>';
                        echo '</li>';
                        echo '<div class="collapse p-4 collapse-pozo" id="editar-pozo-'.$pozo['id'].'">';
                        echo '<form action="editar_pozo.php" method="POST">';
                        echo '<input type="hidden" name="id" value="'.$pozo['id'].'" disabled>';
                        echo '<label for="nombre" class="form-label">Nombre:</label>';
                        echo '<input type="text" class="form-control" name="nombre" value="'.$pozo['name'].'" disabled />';
                        echo '<label for="descripcion" class="form-label">Descripción:</label>';
                        echo ' <textarea class="form-control" name="descripcion" disabled >'.$pozo['descripcion'].'</textarea>';
                        echo '<input type="submit" value="EDITAR" name="editar-pozo" class="btn btn-success mt-1" disabled  />';
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