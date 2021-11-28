<?php
session_start();

if (!$_SESSION['usuario']) {
    header('Location: login.php');
    exit();
}

$pozo = null;

include('db.php');

if ($_POST['agregar_medida']) {
    if (!$_POST['lectura'] || !$_POST['id_pozo'] || !$_POST['fecha'] || !$_POST['hora']) {
        $_SESSION['error'] = 'Se necesita lectura, fecha y hora';
        header('Location: index.php');
        exit();
    }

    if (!floatval($_POST['lectura']) || $_POST['lectura'] <= 0) {
        $_SESSION['error'] = 'Medida inválida';
        header('Location: index.php');
        exit();
    }

    $id_pozo = pg_escape_string($con, $_POST['id_pozo']);

    $result = pg_query($con, "SELECT * FROM manometro_pozos WHERE id = '{$id_pozo}'");

    if ($result) {
        $lectura = pg_escape_string($con, $_POST['lectura']);
        $tiempo = pg_escape_string($con, "{$_POST['fecha']} {$_POST['hora']}");
        $pozo = pg_fetch_assoc($result);

        if (!$pozo) {
            $_SESSION['error'] = 'El pozo no existe';
            header('Location: index.php');
            exit();
        }

        $result = pg_query($con, "SELECT * FROM manometro_medidas WHERE tiempo = '{$tiempo}'");

        if (pg_fetch_row($result)) {
            $_SESSION['error'] = "Ya hay una medida para $tiempo";
            header('Location: index.php');
            exit();
        }

        $result = pg_query($con, "INSERT INTO manometro_medidas (lectura, tiempo, id_pozo) VALUES ('{$lectura}', '{$tiempo}', '{$pozo['id']}')");

        if (!$result) {
            $_SESSION['error'] = pg_last_error($con);
            header('Location: index.php');
            exit();
        }

        $_SESSION['error'] = pg_last_error($con);
        header("Location: medidas.php?pozo={$pozo['id']}");
        exit();
    } else {
        $_SESSION['error'] = pg_last_error($con);
        header('Location: index.php');
        exit();
    }
} else {

    $pozo_id = pg_escape_string($con, $_GET['pozo']);

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

    $pozo = pg_fetch_assoc($result);

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
        <h3>Medidas del pozo "<?php echo $pozo['name']; ?>"</h3>
        <div>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="#agregar-medida" aria-expanded="false" aria-controls="agregar-pozo">
                Agregar medida
            </a>
        </div>
    </div>
    <div class="collapse p-2" id="agregar-medida">
        <form action="medidas.php" method="POST">
            <input type="hidden" name="id_pozo" value="<?php echo $pozo_id; ?>">
            <label for="lectura" class="form-label">Lectura:</label>
            <input type="number" min="0" class="form-control" name="lectura" />
            <label for="fecha" class="form-label">Fecha:</label>
            <input type="date" class="form-control" name="fecha">
            <label for="hora" class="form-label">Hora:</label>
            <input type="time" class="form-control" name="hora">
            <input type="submit" value="AGREGAR MEDIDA" name="agregar_medida" class="btn btn-success mt-1" />
        </form>
    </div>
    <canvas id="medidas_graph">

    </canvas>
    <div class="accordion" id="accordionExample">
        <?php
        $result = pg_query($con, "SELECT * FROM manometro_medidas WHERE id_pozo = '$pozo_id' ORDER BY tiempo ASC");

        if ($result) {
            $medidas = pg_fetch_all($result, MYSQLI_ASSOC);


            if (!$medidas) {
                echo '<li class="list-group-item">No se han registrado medidas</li>';
            } else {
                foreach ($medidas as $medida) {
                    $tiempo_arr = explode(' ', $medida['tiempo']);
                    echo '<div class="accordion-item"><h5 class="list-group-item d-flex justify-content-between">';
                    echo '<a data-bs-toggle="collapse" href="#editar-medida-' . $medida['id'] . '" aria-expanded="false" aria-controls="agregar-medida">' . $medida['lectura'] . ' bar (' . $medida['tiempo'] . ')</a>';
                    echo '<a href="eliminar_medida.php?medida=' . $medida['id'] . '" class="btn btn-danger">Eliminar</a>';
                    echo '</h5>';
                    echo '<div class="collapse p-4 collapse-medida" id="editar-medida-' . $medida['id'] . '" data-bs-parent="#accordionExample">';
                    echo '<form action="editar_medida.php" method="POST">';
                    echo '<input type="hidden" name="id" value="' . $medida['id'] . '" disabled>';
                    echo '<label for="lectura" class="form-label">Lectura:</label>';
                    echo '<input type="number" class="form-control" name="lectura" step="0.01" value="' . $medida['lectura'] . '" disabled />';
                    echo '<label for="fecha" class="form-label">Fecha:</label>';
                    echo ' <input class="form-control" type="date" name="fecha" disabled value="' . $tiempo_arr[0] . '">';
                    echo '<label for="hora" class="form-label">Hora:</label>';
                    echo ' <input class="form-control" type="time" name="hora" disabled value="' . $tiempo_arr[1] . '">';
                    echo '<input type="submit" value="EDITAR" name="editar_medida" class="btn btn-success mt-1" disabled  />';
                    echo '</div></div>';
                }
            }
        } else {
            echo '<li class="list-group-item">' . pg_last_error($con) . '</li>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js" integrity="sha256-7lWo7cjrrponRJcS6bc8isfsPDwSKoaYfGIHgSheQkk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@^2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@^1"></script>
    
</div>
<script>
        document.onreadystatechange = function() {
            if (document.readyState == 'complete') {
                var ctx = document.getElementById('medidas_graph').getContext('2d')
    
                var data = {
                    datasets: [{
                        label: "<?php echo $pozo['name']; ?>",
                        data: [
                            <?php
                            echo implode(', ', array_map(function ($medida) {
                                return json_encode([
                                    'y' => (float)$medida['lectura'],
                                    'x' => $medida['tiempo']
                                ]);
                            }, $medidas));
                            ?>
                        ].map(function(value) {
                            return {
                                y: value.y,
                                x: Date.parse(value.x)
                            }
                        }),
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                };
    
                var config = {
                    type: 'line',
                    data: data,
                    options: {
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'day'
                                }
                            }
                        },
                        zone: "Venezuela/Caracas"
                    }
                };
    
                var chart = new Chart(ctx, config);
            }
        }
    </script>
<?php include('footer.php'); ?>