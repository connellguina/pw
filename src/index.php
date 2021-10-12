<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programación Web</title>
</head>
<body>
    <?php 
    $sitios = ['triangulitos/', 'octagono/', 'arreglos-asociativos/' ];
    $sitiosName = ['Calcular la hipotenusa del triangulo rectangulo', 'Calcular el área de un octágono regular', 
        'Programa de Arreglos Asociativos'];
    $lensitios = count($sitios);
    
    $list = '<ol>';
    for ($i=0; $i < $lensitios; $i++) { 
        $list .= '<li><a href="'.$sitios[$i].'">'.$sitiosName[$i].'</a></li>';  
    }
    $list .= '</ol>';

    echo $list;
    ?>
</body>
</html>