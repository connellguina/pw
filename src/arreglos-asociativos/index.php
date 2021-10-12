<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arreglos asociativos</title>
    <style>
        .lista-empleados {
            display: flex;
            justify-content: space-evenly;
            margin-bottom: 2%;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div>
        <?php 
        $html = '';

        if (isset($_POST['agregar'])) {
            $valid = false;

            $resultado = '';

            $html = '<div class="lista-empleados">';

            $empleados = [];

            for ($i=0; $i < 3; $i++) {
                $empleado = null;

                $nombre = filter_input(INPUT_POST, 'nombre'.$i);
                $apellido = filter_input(INPUT_POST, 'apellido'.$i);
                $cedula = filter_input(INPUT_POST, 'cedula'.$i);
                $salario = filter_input(INPUT_POST, 'salario'.$i, FILTER_VALIDATE_FLOAT);
                $dpto = filter_input(INPUT_POST, 'dpto'.$i);
                $lugar = filter_input(INPUT_POST, 'lugar'.$i);

                if (!$nombre) {
                    $resultado = '<h3>Nombre inválido (Empleado'.($i+1).')</h3>';
                    break;
                }

                if (!$apellido) {
                    $resultado = '<h3>Apellido inválido (Empleado'.($i+1).')</h3>';
                    break;
                }

                if (!$cedula || !is_numeric($cedula)) {
                    $resultado = '<h3>Cédula inválida (Empleado'.($i+1).')</h3>';
                    break;
                }

                if (!$salario || !is_numeric($salario) || $salario <= 0) {
                    $resultado = '<h3>Salario inválido (Empleado'.($i+1).')</h3>';
                    break;
                }

                if (!$dpto) {
                    $resultado = '<h3>Departamento inválido (Empleado'.($i+1).')</h3>';
                    break;
                }

                if (!$lugar) {
                    $resultado = '<h3>Lugar inválido (Empleado'.($i+1).')</h3>';
                    break;
                }

                $empleado = [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'cedula' => $cedula,
                    'salario' => $salario,
                    'dpto' => $dpto,
                    'lugar' => $lugar,
                ];

                array_push($empleados, $empleado);
            }

            if (count($empleados) === 3) {
                foreach ($empleados as $index => $empleado) {
                    $resultado .= '<div style="display: block;">';

                    foreach ($empleado as $key => $value) {
                        $campo = strtoupper($key);

                        $resultado .= "<p><strong>$key:</strong> $value</p>";
                    }

                    $resultado .= '</div>';
                }    
            }
            
            $html .= $resultado;
            $html .= '</div>';
            $html .= '<a href="">Regresar</a>';
        } else {
            $html .= '<form method="post">';
            
            $html .= '<div class="lista-empleados">';

            for ($i=0; $i < 3; $i++) { 
                $html .= '<div style="display: block;">';
                $html .= '<p>#'.($i+1).'</p>';
                $html .= '<label>Nombre: </label>';
                $html .= '<input name="nombre'.$i.'"><br>';
                $html .= '<label>Apellido: </label>';
                $html .= '<input name="apellido'.$i.'"><br>';
                $html .= '<label>Cédula: </label>';
                $html .= '<input name="cedula'.$i.'"><br>';
                $html .= '<label>Salario: </label>';
                $html .= '<input name="salario'.$i.'" type="text" step=".01"><br>';
                $html .= '<label>Departamento: </label>';
                $html .= '<input name="dpto'.$i.'"><br>';
                $html .= '<label>Lugar de trabajo: </label>';
                $html .= '<input name="lugar'.$i.'">';
                $html .= '</div>';
            }
            
            $html .= '</div>';
            $html .= '<button name="agregar" type="submit">Agregar</button>';
            $html .= '</form>';
        }
        
        echo $html;
        ?>
    </div>
</body>
</html>