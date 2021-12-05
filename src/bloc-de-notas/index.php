<?php 
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloc de notas</title>
</head>
<body>
    <form method="post" action="save-file.php">
        <div class="input-group">
            <label for="filename">Filename</label>
            <input type="text" name="filename" placeholder="Filename" class="form-control">
        </div>
        <textarea name="body" cols="30" rows="10" class="form-control"></textarea>
        <input type="submit" name="save" class="btn btn-primary" value="Save file">
    </form>
</body>
</html>
<?php 

session_destroy();
?>