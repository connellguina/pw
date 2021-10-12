<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triángulos</title>
</head>
<body>
    <?php 
    $html = null;
    $a = 3;
    $b = 4;

    if (isset($_POST['calcular-hipotenusa'])) {
        $hipotenusa = sqrt(pow($a, 2) + pow($b, 2));

        $html = "<p>Un triángulo rectángulo con catetos de $a cm y $b cm tiene una hipotenusa de $hipotenusa cm</p>";
        $html .= '<a href="">Regresar</a>';
    } else {
        $html = '<form method="post">';
        $html .= '<p>Dale click a "Calcular" para saber cuánto mide la hipotenusa de un triángulo rectángulo';
        $html .= " con catetos de $a cm y $b cm</p>";
        $html .= '<br><button type="submit" name="calcular-hipotenusa">Calcular</button>';
        $html .= '</form>';
    }

    echo $html;
    ?>
</body>
</html>