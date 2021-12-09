<?php
include_once 'only-user.php';

if (isset($_POST['add-patient'])) {
    $_POST['name'] = mysqli_real_escape_string($db, $_POST['name']);
    $_POST['dni'] = mysqli_real_escape_string($db, $_POST['dni']);
    $_POST['email'] = mysqli_real_escape_string($db, $_POST['email']);

    $result = mysqli_query(
        $db,
        "INSERT INTO patients (name, dni, email) VALUES ('{$_POST['name']}', '{$_POST['dni']}', '{$_POST['email']}')"
    );

    if (!$result) {
        $_SESSION['msg'] = mysqli_error($db);
    }

    header('Location: index.php');
    exit;
} else if (isset($_POST['add-exam'])) {

    $type = mysqli_real_escape_string($db, $_POST['type']);
    $date = mysqli_real_escape_string($db, $_POST['date']);
    $patient = mysqli_real_escape_string($db, $_POST['patient']);
    $procedure = mysqli_real_escape_string($db, $_POST['procedure']);

    $result = mysqli_query(
        $db,
        "INSERT INTO exams (`type`, `date`, `procedure`, `patient`) VALUES ('$type', '$date', '$procedure', '$patient')"
    );

    if (!$result) {
        $_SESSION['msg'] = mysqli_error($db);
    }

    header('Location: index.php');
    exit;
} else if (isset($_POST['add-results'])) {
    $_POST['exam_id'] = mysqli_real_escape_string($db, $_POST['exam_id']);
    $_POST['results'] = mysqli_real_escape_string($db, $_POST['results']);

    $result = mysqli_query(
        $db,
        "UPDATE exams SET results = '{$_POST['results']}', status = 'done' WHERE id = '{$_POST['exam_id']}'"
    );

    if (!$result) {
        $_SESSION['msg'] = mysqli_error($db);
        header('Location: index.php');
        exit;
    }

    header('Location: send_mail.php?exam='.$_POST['exam_id']);
    exit;
} else if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
</head>

<body>
    <?php include 'navbar.php'; ?>

    <?php
    $exams = null;

    if ($resultado = mysqli_query($db, "SELECT e.*, p.name AS patient_name, p.dni AS patient_dni FROM exams e JOIN patients p ON e.patient = p.id")) {
        $exams = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    } else {
        exit(mysqli_error($db));
    }
    ?>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalExam">
        New exam
    </button>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalpatient">
        New patient
    </button>
    
    <form action="index.php" method="post">
        <input name="logout" class="btn btn-danger" type="submit" value="Log out">
    </form>

    <h3>Examenes:</h3>

    <?php

    if (isset($_SESSION['msg'])) {
        echo "<div class='alert alert-light' role='alert'>{$_SESSION['msg']}</div>";
        unset($_SESSION['msg']);
    }

    ?>
    <ul class="list-group">
        <?php foreach ($exams as $exam) {
            echo "<li class='list-group-item'>{$exam['patient_dni']} {$exam['patient_name']}({$exam['date']})<br>";

            if ($exam['status'] === 'done') {
                echo "<a href='send_mail.php?exam={$exam['id']}'>Send results</a>";
            } else if ($user['role'] === 'doctor'){
                echo "<a href='#' data-bs-toggle='modal' data-bs-src='{$exam['id']}' data-bs-target='#modalExamResults'>Register results</a>";
            }

            echo '</li>';
        }
        ?>
    </ul>

    <div class="modal fade" id="modalpatient" tabindex="-1" role="dialog" aria-labelledby="modalpatientId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post">
                        <label for="">Patient name</label>
                        <input type="text" name="name" class="form-control" required>
                        <label for="">DNI</label>
                        <input type="number" name="dni" class="form-control" required>
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <input type="submit" class="btn btn-primary" name="add-patient" value="Add patient">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalExam" tabindex="-1" role="dialog" aria-labelledby="modalExamId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New exam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post">
                        <label for="">Exam type</label>
                        <input type="text" name="type" class="form-control" required>
                        <label for="">Date:</label>
                        <input type="datetime-local" name="date" class="form-control" required>
                        <label>Patient</label>
                        <select name="patient" class="form-select" required>
                            <?php
                            $result = mysqli_query($db, 'SELECT * FROM patients');
                            
                            $patients = mysqli_fetch_all($result, MYSQLI_ASSOC)
                            ?>
                            <option value="">Select patient</option>
                            <?php foreach ($patients as $patient) {
                                echo "<option value='{$patient['id']}'>{$patient['name']} DNI. {$patient['dni']}</option>";
                            } ?>
                        </select>
                        <label for="">Procedure</label>
                        <textarea name="procedure" cols="30" rows="10" class="form-control" required></textarea>
                        <input type="submit" class="btn btn-primary" name="add-exam" value="Add exam">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalExamResults" tabindex="-1" role="dialog" aria-labelledby="modalExamResultsId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exam # <span role="exam_id"></span> results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post">
                        <input type="hidden" name="exam_id">
                        <textarea name="results" class="form-control" cols="30" rows="10" required></textarea>
                        <input type="submit" class="btn btn-primary" name="add-results" value="Add results">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>

</body>

</html>