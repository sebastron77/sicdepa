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
$acuse = find_by_id_acuse((int)$_GET['id']);
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

    .titulo {
        font-size: 30px;
        margin-left: 250px;
        font-weight: bold;
    }

    .rfc {
        font-size: 18px;
        margin-top: 120px;
        margin-left: 400px;
    }

    .linea1 {
        border-top: 0.5px solid black;
        width: 30%;
        margin-left: 630px;
        margin-top: -7px;
    }

    .linea2 {
        border-top: 0.5px solid black;
        width: 40%;
        margin-left: 530px;
        margin-top: 50px;
    }

    .contenedor {
        font-family: 'Montserrat', sans-serif;
        color: black;
        margin-top: 10%;
    }

    .rectangulo {
        display: inline-block;
        /* Coloca los rectángulos horizontalmente */
        width: 48%;
        height: 120px;
        /* background-color: lightblue; */
        border-radius: 20px;
        /* Define las esquinas redondeadas */
        border: 1px solid black;
        margin-right: 10px;
        /* Espacio entre los rectángulos */
        text-align: center;
        /* Centra el texto dentro del rectángulo */
        line-height: 50px;
        /* Ajusta la altura de la línea para centrar verticalmente */
    }
</style>

<body>
    <?php if ($results) : ?>
        <img src="http://localhost/sicdepa/administrador/medios/logo-cedh.jpg" width="140px" height="210px">
        <div style="margin-top: -220px">
            <p class="titulo">PARA SER LLENADO POR EL RECEPTOR</p>
            <p class="rfc">RFC CON HOMOCLAVE:
                <?php
                echo '<strong>' . str_repeat("&nbsp;", 17);
                echo $acuse['rfc'] . '</strong>'
                ?>
            <div class="linea1"></div>
            </p>
        </div>
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
        <p style="font-weight: bold; font-size: 18px; margin-top: 5%; margin-left: 70%;">A <?php echo $dia; ?> DE <?php echo upper_case($mes); ?> DE <?php echo $anio; ?></p>

        <div class="contenedor">
            <div class="rectangulo">
                <p style="font-size: 18px; margin-left: -85%; margin-top: -20px;">C. </p>
                <p style="font-size: 18px; font-weight: bold; margin-left: -40%; margin-top: -20px;"><?php echo $acuse['nombre'] . " " . $acuse['apellido_paterno'] . " " . $acuse['apellido_materno'] ?></p>
                <p style="font-size: 18px; margin-left: -73%; margin-top: -5%">Presente.</p>
            </div>
            <div class="rectangulo">
                <p style="font-size: 18px; text-align:center; color:dimgrey"><?php echo $acuse['periodo'] ?></p>
                <p style="font-size: 18px; text-align:center; margin-top: -25px; color:dimgrey">Declaración Patrimonial</p>
                <p style="font-size: 18px; text-align:center; margin-top: -40px; color:dimgrey">
                    y de Intereses -
                    <?php if ($acuse['tipo_declaracion'] == 1) {
                        echo 'Inicial';
                    } elseif ($acuse['tipo_declaracion'] == 2) {
                        echo 'Modificación';
                    } elseif ($acuse['tipo_declaracion'] == 3) {
                        echo 'Conclusión';
                    } ?>
                </p>
            </div>
        </div>
        <p style="font-size: 16px;">POR ACUERDO DEL TITULAR DEL ÓRGANO INTERNO DE CONTROL DE LA COMISIÓN ESTATAL DE LOS DERECHOS HUMANOS, ACUSAMOS RECIBO DE SU DECLARACIÓN PRESENTADA EN ESTA FECHA, PARA INCORPORARLA A SU EXPEDIENTE.</p>
        <p style="font-size: 18px; width: 40%; margin-top: 30px; text-align:justify;">Este acuse de recibo será válido cuando tenga el sello y la firma del responsable del centro de recepción autorizado por el titular del Órgano Interno de Control.</p>
        <p style="font-size: 18px; width: 40%; margin-top: -350px; margin-left: 650px;"> A t e n t a m e n t e. </p>
        <div class="linea2"></div>
        <p style="font-size: 18px; width: 40%; margin-top: -360px; margin-left: 620px;">Nombre y firma del receptor</p>

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