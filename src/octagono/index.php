<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Octágono</title>
</head>
<body>
<?php 
    $html = null;

    if (isset($_POST['calcular-area'])) {
        $l = filter_input(INPUT_POST, 'l', FILTER_VALIDATE_FLOAT);

        if (!$l || $l < 0) {
            $html = '<h1>La longitud de los lados no es válida.</h1>';
        } else {
            $apotema = $l/(2*tan(M_PI_4/2));
            $area = 4*$l*$apotema;
    
            $html = "<p>Un octágono regular con lados de $l tiene un área de $area</p>";
            $html .= '<a href="">Regresar</a>';
        }

    } else {
        $html = '<form method="post">';
        $html .= '<p>Área de rectángulo regular</p>';
        $html .= '<label>Lados del octágono</label><br>';
        $html .= '<input name="l" type="number" step=".01" /><br>';
        $html .= '<button type="submit" name="calcular-area">Calcular</button>';
        $html .= '</form>';
    } 

    echo $html;
    ?>
</body>
</html>