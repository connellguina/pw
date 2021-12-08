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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Textpad</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#create-file-modal">Create file</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#create-dir-modal">Create directory</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
        <h4><?php $_SESSION['mensaje'] ?  $_SESSION['mensaje'] : '' ?> </h4>
            <div class="col-sm-12 col-md-4">
                <ul>
                    <?php

                    // Does not support flag GLOB_BRACE
                    function rglob($pattern, $flags = 0)
                    {
                        $files = glob($pattern, $flags);
                        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
                            $files = array_merge($files, rglob($dir . '/' . basename($pattern), $flags));
                        }
                        return $files;
                    }

                    $files = rglob(__DIR__ . "/archivos/*");
                    ?>
                    <?php
                    foreach ($files as $file) {
                        if (is_dir($file)) {
                            echo "<li>" . str_replace(__DIR__ . "/archivos/", '', $file) .
                                "<a href=\"del-dir.php?dir=" . str_replace(__DIR__ . '/archivos/', '', $file) . "\">DELETE</a>" .
                                "</li>";
                        } else {
                            echo "<li>" . "<a href=\"index.php?filename=" . urlencode(str_replace(__DIR__ . '/archivos/', '', 
                                str_replace('.txt', '', $file))) .
                                "\">" . str_replace(__DIR__ . "/archivos/", '', $file) . "</a>" .
                                "<a href=\"del-file.php?filename=" . urlencode(str_replace(__DIR__ . '/archivos/', '', str_replace('.txt', '', $file))) . "\">DELETE</a>" .
                                "</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="col-sm-12 col-md-8">
                <form method="post" action="save-file.php">
                    <?php
                    if ($filename = urldecode($_GET['filename'])) {
                        $filename_normal = str_replace('.txt', '', $filename);
                        $file_contents = file_get_contents(__DIR__ . "/archivos/$filename_normal.txt");
                    }
                    ?>
                    <label for="filename">Filename</label>
                    <input type="text" name="filename" placeholder="Filename" class="form-control" value="<?= $filename ?>" readonly>
                    <textarea class="form-control" rows="10" cols="20" name="contents"><?= $file_contents ?></textarea>
                    <input type="submit" name="save" class="btn btn-primary" value="Save file">
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="create-file-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New file</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="save-file.php">
                        <label for="filename" class="form-label">Filename</label>
                        <input type="text" name="filename" placeholder="Filename" class="form-control">
                        <input type="hidden" name="contents">
                        <br>
                        <input type="submit" name="save-file" class="btn btn-primary" value="Save file">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="create-dir-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New directory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="save-dir.php">
                        <label for="dirname" class="form-label">Dirname</label>
                        <input type="text" name="dirname" placeholder="dirname" class="form-control">
                        <input type="submit" name="save-dir" class="btn btn-primary" value="Save directory">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
<?php

session_destroy();
?>