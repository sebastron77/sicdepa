<?php
include("includes/config.php");
$page_title = 'Constancia';
$results = '';
require_once('includes/load.php');
require_once('dompdf/autoload.inc.php');


use Dompdf\Dompdf;
use Dompdf\Options;

ob_start(); //Linea para que deje descargar el PDF
$user = current_user();
$nivel_user = $user['user_level'];
$id_detalle_usuario = $user['id_detalle_user'];
$results = find_by_id('rel_declaracion', (int)$_GET['id'], 'id_rel_declaracion');
// $declaracion_terminada = find_all_dec_conc((int)$id_detalle_usuario);
// $last_dec = $verficia_dec_ant['concluida'];
echo (int)$_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>Reporte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php if ($results) : ?>
        <div class="page-break">
            <center>
                <div class="row" style="display: flex; justify-content: center; align-items: center;">
                    <h1><?php echo $results['fecha_actualizacion']; ?></h1>
                </div>
            </center>
        </div>

    <?php else :
        $session->msg("d", "No se encontraron datos. ");
    endif;
    ?>
</body>

</html>
<?php if (isset($db)) {
    $db->db_disconnect();
} ?>

<?php
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new DOMPDF($options);
$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper("letter");
$dompdf->render();
//$pdf->image();
$pdf = $dompdf->output();
$filename = "datos.pdf";
file_put_contents($filename, $pdf);
$dompdf->stream($filename);

?>