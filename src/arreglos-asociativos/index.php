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
        }
    </style>
</head>
<body>
    <div>
        <?php 
        $html = null;

        if ($_POST['agregar']) {

        } else {
            $html = '<form method="post">';
            $html .= '<label>Nombre: </label>';
            $html .= '<input name="nombre"><br>';
            $html .= '<label>Apellido: </label>';
            $html .= '<input name="apellido"><br>';
            $html .= '<label>Cédula: </label>';
            $html .= '<input name="cedula"><br>';
            $html .= '<label>Salario: </label><br>';
            $html .= '<input name="salario" type="text" step=".01"><br>';
            $html .= '<label>Departamento: </label>';
            $html .= '<input name="dpto"><br>';
            $html .= '<label>Lugar de trabajo: </label>';
            $html .= '<input name="lugar"><br>';
            $html .= '</form>';
        }?>
    </div>
</body>
</html>