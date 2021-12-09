<?php
require_once 'db.php';
require_once 'only-user.php';

$exam_id = mysqli_real_escape_string($db, $_GET['exam']);

$result = mysqli_query($db,
    "SELECT e.*, p.email, p.dni, p.name FROM exams e JOIN patients p ON e.pacient = p.id WHERE e.id = '$exam_id'");

if (!$result) {
    $_SESSION['mensaje'] = mysqli_error($db);
    header('Location: index.php');
    die();
}

$exam = mysqli_fetch_assoc($result);

if (!$exam) {
    header('Location: index.php');
    die();
}

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

// attachment name
$filename = "{$exam['name']}-{$exam['dni']}.pdf";

// a random hash will be necessary to send mixed content
$separator = md5(time());


require_once 'fpdf184/fpdf.php';

$fpdf = new FPDF();
$fpdf->AddPage();
$fpdf->SetFont('Arial','B', 12);
$y = $fpdf->GetY();
$x = $fpdf->GetX();
$fpdf->Cell(160, 25, utf8_decode("Mr. , Ms. {$exam['name']} (ID, {$exam['dni']})"));
$fpdf->SetXY($x, $y +5);
$fpdf->Cell(85, 25, "{$exam['date']}");
$fpdf->SetFont('Arial','B', 12);
$fpdf->SetXY(0, $y+10);
$fpdf->Cell(210, 25, "EXAM RESULTS",
    0, 0, 'C');
$fpdf->SetXY(0, $y+20);
$fpdf->SetFont('Arial','B', 10);
$fpdf->Cell(10, 25);
$fpdf->Cell(80, 25, utf8_decode('DESCRIPTION'));
$fpdf->SetXY(0, $y+35);
$fpdf->SetFont('Arial','', 10);
$fpdf->Cell(10, 25);
$fpdf->MultiCell(200, 25, utf8_decode($exam['description']), 'R');
$fpdf->SetFont('Arial','B', 10);
$fpdf->Cell(0, 10);
$fpdf->Cell(110, 25, utf8_decode('RESULTS'));
$y = $fpdf->GetY();
$fpdf->SetFont('Arial','', 10);
$fpdf->SetXY(0, $y+25);
$fpdf->Cell(0, 25);
$fpdf->MultiCell(200, 25, utf8_decode($exam['results']), 0, 'J', true);
$pdf = $fpdf->Output('', 'S');

$attachment = chunk_split(base64_encode($pdf));

$message = '';

$from = '';

$body = "--" . $separator . $eol;
$body .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
$body .= "Hola, {$exam['nombre']} {$exam['apellido']} ! Estos son los resultados de su exam." . $eol;

// message
$body .= "--" . $separator . $eol;
$body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
$body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
$body .= $message . $eol;

// attachment
$body .= "--" . $separator . $eol;
$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
$body .= "Content-Transfer-Encoding: base64" . $eol;
$body .= "Content-Disposition: attachment" . $eol . $eol;
$body .= $attachment . $eol;
$body .= "--" . $separator . "--";

// main header
$headers  = "From: " . $from . $eol;
$headers .= "MIME-Version: 1.0" . $eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";

$resultado = mail(
    $exam['email'],
    "RESULTADOS DE exam DEL {$exam['fecha']}",
    $body,
    $headers
);

if ($resultado) {
    $_SESSION['mensaje'] = "Unable to send results";
    header('Location: index.php');
    die();
} else {

    $_SESSION['mensaje'] = "Results sent";
}

header('Location: index.php');
die();