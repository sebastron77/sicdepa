<?php
include("includes/config.php");
$page_title = 'Constancia';
$results = '';
require_once('includes/load.php');
require_once('dompdf/autoload.inc.php');


use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new DOMPDF($options);

ob_start(); //Linea para que deje descargar el PDF
$user = current_user();
$nivel_user = $user['user_level'];
$id_detalle_usuario = $user['id_detalle_user'];
$results = find_by_id('rel_declaracion', (int)$_GET['id'], 'id_rel_declaracion');
$acuse = find_by_id_dec_comp((int)$_GET['id']);
// $declaracion_terminada = find_all_dec_conc((int)$id_detalle_usuario);
// $last_dec = $verficia_dec_ant['concluida'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="UTF-8">
    <title>Reporte</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
</head>
<style>
    body {
        font-family: 'Montserrat', sans-serif;
        color: black;
    }

    .infoDec {
        font-size: 12px;
        text-align: left;
        line-height: 0.5;
    }
</style>

<body>
    <?php if ($results) : ?>
        <!-- <img src="http://localhost/sicdepa/administrador/medios/logo-cedh.jpg" width="140px" height="210px"> -->
        <div style="margin-top: -30px">
            <p style="font-size: 16px; text-align: center;">ÓRGANO INTERNO DE CONTROL</p>
            <p style="font-size: 16px; margin-top: -15px; text-align: center;">DECLARACIÓN DE SITUACIÓN PATRIMONIAL Y DE INTERESES DE LOS SERVIDORES PÚBLICOS</p>
            <p style="font-size: 16px; margin-top: -15px; text-align: center;">DECLARACIÓN
                <?php if ($acuse['tipo_declaracion'] == 1) {
                    echo 'INICIAL';
                } elseif ($acuse['tipo_declaracion'] == 2) {
                    echo 'MODIFICACIÓN';
                } elseif ($acuse['tipo_declaracion'] == 3) {
                    echo 'CONCLUSIÓN';
                } ?></p>
            <?php
            $meses_espanol = array(
                "January" => "enero",
                "February" => "febrero",
                "March" => "marzo",
                "April" => "abril",
                "May" => "mayo",
                "June" => "junio",
                "July" => "julio",
                "August" => "agosto",
                "September" => "septiembre",
                "October" => "octubre",
                "November" => "noviembre",
                "December" => "diciembre"
            );
            $dia = date("d", strtotime($acuse['fecha_conclusion']));
            $mes = $meses_espanol[date("F", strtotime($acuse['fecha_conclusion']))];
            $anio = date("Y", strtotime($acuse['fecha_conclusion']));
            ?>
            <?php
            echo '<strong><p style="font-size: 16px; text-align: right;">' . str_repeat("&nbsp;", 17);
            echo $acuse['rfc'] . '</p></strong>'
            ?>
            <p style="font-size: 16px; margin-top: -2%; text-align: right;">FECHA DE CONCLUSIÓN: <?php echo $dia; ?> DE <?php echo upper_case($mes); ?> DE <?php echo $anio; ?></p>
            <p style="font-size: 15px; text-align: left; font-weight:lighter;">ORGANO INTERNO DE CONTROL</p>
            <p style="font-size: 11px; margin-top: -1%; text-align: left; line-height: 1;">BAJO PROTESTA DE DECIR VERDAD PRESENTO A USTED MI DECLARACIÓN
                PATRIMONIAL Y DE INTERESES, CONFORME A LO DISPUESTO EN LOS ARTÍCULOS 32 Y 33 DE LA LEY GENERAL DE RESPONSABILIDADES ADMINISTRATIVAS.
            </p>
            <p style="font-size: 14px; margin-top: 0%; text-align: center; line-height: 1; font-weight: bold;">DATOS GENERALES DEL SERVIDOR PUBLICO</p>
            <p class="infoDec"><strong>NOMBRE(S):</strong><?php echo $acuse['nombre'] ." ". $acuse['apellido_paterno'] ." ". $acuse['apellido_materno']; ?></p>
            <p class="infoDec"><strong>CURP:</strong><?php echo upper_case($acuse['curp']); ?></p>
            <p class="infoDec"><strong>RFC:</strong><?php echo upper_case($acuse['rfc']); ?></p>
            <p class="infoDec"><strong>HOMOCLAVE:</strong><?php echo upper_case(substr($acuse['rfc'], -3)); ?></p>
            <p class="infoDec"><strong>CORREO ELECTRÓNICO INSTITUCIONAL:</strong><?php echo $acuse['correo_laboral']; ?></p>
            <p class="infoDec"><strong>CORREO ELECTRÓNICO PERSONAL:</strong><?php echo $acuse['correo_personal']; ?></p>
            <p class="infoDec"><strong>NÚMERO TELEFÓNICO DE CASA:</strong><?php echo $acuse['telefono']; ?></p>
            <p class="infoDec"><strong>NÚMERO TELEFÓNICO PERSONAL:</strong><?php echo $acuse['tel_part']; ?></p>
            <p class="infoDec"><strong>SITUACIÓN PERSONAL / ESTADO CIVIL:</strong><?php echo upper_case($acuse['eciv']); ?></p>
            <p class="infoDec"><strong>PAÍS DE NACIMIENTO:</strong><?php echo upper_case($acuse['pais_nac']); ?></p>
            <p class="infoDec"><strong>NACIONALIDAD:</strong><?php echo upper_case($acuse['nac']); ?></p>
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

$dompdf->loadHtml(ob_get_clean());
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();
$pdf = $dompdf->output();
$filename = "acuse.pdf";
file_put_contents($filename, $pdf);
$dompdf->stream($filename);
?>