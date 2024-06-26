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

ob_start();
$user = current_user();
$nivel_user = $user['user_level'];
$id_detalle_usuario = $user['id_detalle_user'];
$results = find_by_id('rel_declaracion', (int)$_GET['id'], 'id_rel_declaracion');
$estudios = find_by_estudios($id_detalle_usuario);
$acuse = find_by_id_dec_comp((int)$_GET['id']);
$encargo = find_by_id_dec_enc((int)$_GET['id']);
$rem = find_by_id_dec_rem((int)$_GET['id']);
$de_acuerdo = find_de_acuerdo((int)$_GET['id']);
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

    .background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('http://localhost/sicdepa/administrador/medios/logotr.png');
        background-size: 33% 60%;
        /* Cambia el tamaño de la imagen de fondo */
        background-repeat: no-repeat;
        background-position: center;
        z-index: -1;
    }

    .infoDec {
        font-size: 12px;
        text-align: left;
        line-height: 0.5;
    }

    .infoDecEc {
        font-size: 10px;
        line-height: 1;
        font-weight: lighter;
    }

    .cont {
        height: 200px;
        /* Altura del contenedor */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .titulos {
        font-size: 14px;
        margin-top: 0%;
        text-align: center;
        line-height: 1.5;
        font-weight: bold;
    }
</style>

<body>
    <div class="background">
        <?php if ($results) : ?>
            <!-- <img src="http://localhost/sicdepa/administrador/medios/logo-cedh.jpg" width="140px" height="210px"> -->
            <div style="margin-top: -30px">
                <strong>
                    <p style="font-size: 16px; text-align: center;">ÓRGANO INTERNO DE CONTROL</p>
                    <p style="font-size: 16px; margin-top: -15px; text-align: center;">DECLARACIÓN DE SITUACIÓN PATRIMONIAL Y DE INTERESES DE LOS SERVIDORES PÚBLICOS</p>
                    <p style="font-size: 16px; margin-top: -15px; text-align: center;">- DECLARACIÓN
                        <?php if ($acuse['tipo_declaracion'] == 1) {
                            echo 'INICIAL';
                        } elseif ($acuse['tipo_declaracion'] == 2) {
                            echo 'MODIFICACIÓN';
                        } elseif ($acuse['tipo_declaracion'] == 3) {
                            echo 'CONCLUSIÓN';
                        } ?> -
                    </p>
                </strong>
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
                <p style="font-size: 15px; text-align: left; font-weight:lighter;">ÓRGANO INTERNO DE CONTROL</p>
                <p style="font-size: 12px; margin-top: -1%; text-align: left; line-height: 1;">BAJO PROTESTA DE DECIR VERDAD PRESENTO A USTED MI DECLARACIÓN
                    PATRIMONIAL Y DE INTERESES, CONFORME A LO DISPUESTO EN LOS ARTÍCULOS 32 Y 33 DE LA LEY GENERAL DE RESPONSABILIDADES ADMINISTRATIVAS.
                </p>
                <p class="titulos">DATOS GENERALES DEL SERVIDOR PUBLICO</p>
                <p class="infoDec"><strong>NOMBRE(S): </strong><?php echo $acuse['nombre'] . " " . $acuse['apellido_paterno'] . " " . $acuse['apellido_materno']; ?></p>
                <p class="infoDec"><strong>CURP: </strong><?php echo $acuse['curp']; ?></p>
                <p class="infoDec"><strong>RFC: </strong><?php echo $acuse['rfc']; ?></p>
                <p class="infoDec"><strong>HOMOCLAVE: </strong><?php substr($acuse['rfc'], -3); ?></p>
                <p class="infoDec"><strong>CORREO ELECTRÓNICO INSTITUCIONAL: </strong><?php echo $acuse['correo_laboral']; ?></p>
                <p class="infoDec"><strong>CORREO ELECTRÓNICO PERSONAL: </strong><?php echo $acuse['correo_personal']; ?></p>
                <p class="infoDec"><strong>NÚMERO TELEFÓNICO DE CASA: </strong><?php echo $acuse['telefono']; ?></p>
                <p class="infoDec"><strong>NÚMERO TELEFÓNICO PERSONAL: </strong><?php echo $acuse['tel_part']; ?></p>
                <p class="infoDec"><strong>SITUACIÓN PERSONAL/ESTADO CIVIL: </strong><?php echo $acuse['eciv']; ?></p>
                <p class="infoDec"><strong>PAÍS DE NACIMIENTO: </strong><?php echo $acuse['pais_nac']; ?></p>
                <p class="infoDec"><strong>NACIONALIDAD: </strong><?php echo $acuse['nac']; ?></p>
                <p style="font-size: 14px; margin-top: 0%; text-align: center; line-height: 1.5; font-weight: bold;">DOMICILIO DEL DECLARANTE</p>
                <p class="infoDec" style="text-align: left;  margin-left: 50px;"><strong>CALLE: </strong><?php echo $acuse['calle_num']; ?></p>
                <p class="infoDec" style="text-align: right; margin-top: -200px; margin-right: 50px;"><strong>COLONIA/LOCALIDAD: </strong><?php echo $acuse['colonia']; ?></p>
                <p class="infoDec" style="text-align: left;  margin-left: 50px;"><strong>MUNICIPIO/ALCALDÍA: </strong><?php echo $acuse['mun']; ?></p>
                <p class="infoDec" style="text-align: right; margin-top: -200px; margin-right: 50px;"><strong>ENTIDAD FEDERATIVA: </strong><?php echo $acuse['ent']; ?></p>
                <p class="infoDec" style="text-align: left;  margin-left: 50px;"><strong>CÓDIGO POSTAL: </strong><?php echo $acuse['cod_post']; ?></p>
                <p class="titulos"><strong>DATOS CURRICULARES DEL DECLARANTE</strong></p>
                <table class=" table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style=" width: 5%;" class="text-center">ESCOLARIDAD</th>
                            <th style=" width: 10%;" class="text-center">INST. EDUCATIVA</th>
                            <th style=" width: 10%;" class="text-center">UBICACIÓN</th>
                            <th style=" width: 10%;" class="text-center">CARRERA/ÁREA DEL CONOCIMIENTO</th>
                            <th style=" width: 5%;" class="text-center">ESTATUS</th>
                            <th style=" width: 5%;" class="text-center">DOC. OBTENIDO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudios as $est) : ?>
                            <tr>
                                <td><?php echo $est['escolaridad']; ?></td>
                                <td><?php echo $est['inst_educativa']; ?></td>
                                <td><?php echo $est['ubic_inst']; ?></td>
                                <td><?php echo $est['carrera_area_con']; ?></td>
                                <td><?php echo $est['estatus_estudios']; ?></td>
                                <td><?php echo $est['doc_obt']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="page-break-after:always;"></div>
                <p class="titulos"><strong>DATOS DEL EMPLEO, CARGO O COMISIÓN</strong></p>
                <p class="infoDec"><strong>DEPENDENCIA O ENTIDAD: </strong><?php echo $encargo['dependencia_entidad']; ?></p>
                <p class="infoDec"><strong>NOMBRE DEL EMPLEO, CARGO O COMISIÓN: </strong><?php $encargo['nombre_emp_car_com']; ?></p>
                <p class="infoDec"><strong>¿ESTÁ CONTRATADO POR HONORARIOS?: </strong> <?php echo ($encargo['honorarios'] == 0) ? "No" : "Sí"; ?></p>
                <p class="infoDec"><strong>EMPLEO, CARGO O COMISIÓN: </strong><?php echo $encargo['no_hono_niv_encargo']; ?></p>
                <p class="infoDec"><strong>ÁREA DE ADSCRIPCIÓN: </strong><?php echo $encargo['nombre_area']; ?></p>
                <p class="infoDec"><strong>FECHA DE TOMA POSECIÓN DEL ENCARGO: </strong><?php echo $encargo['fecha_toma_pos_enc']; ?></p>
                <p class="infoDec"><strong>TELÉFONO DE OFICINA: </strong><?php echo $encargo['tel_oficina']; ?></p>
                <p class="infoDec"><strong>EXT.: </strong><?php echo $encargo['extension']; ?></p>
                <p class="titulos">DOMICILIO DEL EMPLEO, CARGO O COMISIÓN</p>
                <p class="infoDec" style="text-align: left;  margin-left: 50px;"><strong>CALLE: </strong><?php echo $encargo['calle_num']; ?></p>
                <p class="infoDec" style="text-align: right; margin-top: -200px; margin-right: 50px;"><strong>COLONIA/LOCALIDAD: </strong><?php echo $encargo['localidad_colonia']; ?></p>
                <p class="infoDec" style="text-align: left;  margin-left: 50px;"><strong>MUNICIPIO/ALCALDÍA: </strong><?php echo $encargo['municipio']; ?></p>
                <p class="infoDec" style="text-align: right; margin-top: -200px; margin-right: 50px;"><strong>ENTIDAD FEDERATIVA: </strong><?php echo $encargo['ent_fed']; ?></p>
                <p class="infoDec" style="text-align: left;  margin-left: 50px;"><strong>CÓDIGO POSTAL: </strong><?php echo $encargo['cod_post']; ?></p>

                <p class="titulos" style="margin-top: 30px;"><strong>INGRESOS DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN NETOS DEL DECLARANTE, PAREJA Y/O DEPENDIENTES ECONÓMICOS</srtong>
                </p>
                <p class="infoDecEc" style="text-align: left; width: 85%; margin-top: 10%;">I. REMUNERACIÓN ANUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO EN EL AÑO ANTERIOR (DEDUCE IMPUESTOS) (POR CONCEPTO DE SUELDOS, HONORARIOS, COMPENSACIONES, BONOS Y OTRAS PRESTACIONES): </p>
                <!-- echo ($encargo['honorarios'] == 0) ? "No" : "Sí"; -->
                <p class="infoDecEc" style="text-align: right; margin-top: -5%;"><?php echo ($rem['renum_anual_neta'] == '' || $rem['renum_anual_neta'] == '0') ? "0" : $rem['renum_anual_neta']; ?>
                </p>
                <p class="infoDecEc" style="text-align: left; width: 85%; margin-top: 3%;">II. OTROS INGRESOS DEL DECLARANTE EN EL AÑO ANTERIOR (SUMA DEL II.1 AL II.4):
                </p>
                <p class="infoDecEc" style="text-align: left; width: 85%;">II.1 POR ACTIVIDAD INDUSTRIAL Y/O COMERCIAL (DEDUCE IMPUESTOS) ESPECIFICA NOMBRE O RAZÓN SOCIAL Y TIPO DE NEGOCIO:
                </p>
                <p class="infoDecEc" style="text-align: right; margin-top: -10%;">
                    <?php echo ($rem['act_indus'] == '' || $rem['act_indus'] == '0') ? "0" : $rem['act_indus']; ?>
                </p>
                <p class="infoDecEc" style="text-align: left; width: 85%;">II.2 POR ACTIVIDAD FINANCIERA (RENDIMIENTOS DE CONTRATOS BANCARIOS O DE VALORES)(DEDUCE IMPUESTOS):
                </p>
                <p class="infoDecEc" style="text-align: right; margin-top: -10%;">
                    <?php echo ($rem['act_finan'] == '' || $rem['act_finan'] == '0') ? "0" : $rem['act_finan']; ?></p>
                <p class="infoDecEc" style="text-align: left; width: 85%;">II.3 POR SERVICIOS PROFESIONALES, PARTICIPACIÓN EN CONSEJOS, CONSULTORÍAS O ASESORÍAS ESPECIFICA EL TIPO DE SERVICIO (DEDUCE IMPUESTOS) :
                </p>
                <p class="infoDecEc" style="text-align: right; margin-top: -10%;">
                    <?php echo ($rem['serv_prof'] == '' || $rem['serv_prof'] == '0') ? "0" : $rem['serv_prof']; ?></p>
                <p class="infoDecEc" style="text-align: left; width: 85%;">II.4 OTROS (ARRENDAMIENTOS, REGALÍAS, SORTEOS, CONCURSOS, DONACIONES, ETC.)(DEDUCE IMPUESTOS):
                </p>
                <p class="infoDecEc" style="text-align: right; margin-top: -10%;">
                    <?php echo ($rem['otros'] == '' || $rem['otros'] == '0') ? "0" : $rem['otros']; ?></p>
                <p class="infoDecEc" style="text-align: left; width: 85%;">
                    <?php
                    if ($acuse['tipo_declaracion'] == 1) {
                        echo 'A. INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II):';
                    } elseif ($acuse['tipo_declaracion'] == 2) {
                        echo 'A. INGRESO ANUAL NETO DEL DECLARANTE (SUMA DEL NUMERAL I Y II):';
                    } elseif ($acuse['tipo_declaracion'] == 3) {
                        echo 'A. INGRESOS DEL DECLARANTE DEL AÑO EN CURSO A LA FECHA DE CONCLUSIÓN DEL EMPLEO, CARGO O COMISIÓN (SUMA DEL NUMERAL I Y II):';
                    }
                    ?>
                </p>
                <p class="infoDecEc" style="text-align: right; margin-top: -10%;"><?php echo $rem['renum_anual_neta']; ?></p>

                <p class="titulos">ACLARACIONES/OBSERVACIONES DE INGRESOS NETOS DEL DECLARANTE:</p>
                <p class="infoDecEc" style="text-align: left;"><?php echo $acuse['observaciones']; ?></p>
            </div>
        <?php else :
            $session->msg("d", "No se encontraron datos. ");
        endif;
        ?>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </div>
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
$filename = "declaracion.pdf";
file_put_contents($filename, $pdf);
$dompdf->stream($filename);
?>