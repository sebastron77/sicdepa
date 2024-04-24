<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos Patrimoniales Públicos';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$id_rel_declaracion = find_by_id_dec((int)$id_detalle_usuario);
page_require_level(3);
?>

<style>
    .modal {
        /* Mostrar modal por defecto */
        display: block !important;
        /* Posición fija */
        position: fixed;
        /* Hacer que el modal esté encima del contenido */
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        /* Habilitar desplazamiento si es necesario */
        overflow: auto;
        /* Fondo oscuro semi-transparente */
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* Estilos para el switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 34px;
        /* Bordes redondeados */
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
        /* Bordes redondeados */
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Estilos opcionales para personalizar el aspecto del texto alrededor del switch */
    .switch-container {
        display: inline-flex;
        align-items: center;
    }

    .switch-label {
        margin-right: 8px;
    }
</style>

<?php
if (isset($_POST['add_rel_datos_pub_dec'])) {
    if (empty($errors)) {
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];
        $ingresos_netos = $db->escape($_POST['ingresos_netos']);
        $bienes_inmuebles = $db->escape($_POST['bienes_inmuebles']);
        $bienes_muebles = $db->escape($_POST['bienes_muebles']);
        $vehiculos = $db->escape($_POST['vehiculos']);
        $inversiones = $db->escape($_POST['inversiones']);
        $adeudos = $db->escape($_POST['adeudos']);
        $de_acuerdo = remove_junk($db->escape($_POST['de_acuerdo']));
        if ($de_acuerdo == 'on') {
            $bool = '1';
        } else {
            $bool = '0';
        }

        $query1 = "INSERT INTO rel_datos_pub_dec (";
        $query1 .= "id_detalle_usuario, id_rel_declaracion, de_acuerdo, ingresos_netos, bienes_inmuebles, bienes_muebles, vehiculos, inversiones, adeudos,
                    fecha_creacion, estatus_detalle";
        $query1 .= ") VALUES (";
        $query1 .= "'{$id_detalle_usuario}', '{$declaracion}', '{$bool}', '{$ingresos_netos}', '{$bienes_inmuebles}', '{$bienes_muebles}', '{$vehiculos}', 
                    '{$inversiones}', '{$adeudos}', NOW(), 1";
        $query1 .= ")";
        $db->query($query1);

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);

        if (($db->query($query)) && ($result2)) {
            $session->msg('s', "La información de hacer datos patrimoniales públicos ha sido agregada con éxito. Continúa con Datos del cónyuge.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó datos_pub_dec.', 1);
            updateLastArchivo('datos_conyuge.php', $declaracion);
            redirect('datos_conyuge.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('rel_datos_pub_dec.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('rel_datos_pub_dec.php', false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="modal">
    <div class="modal-content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group clearfix">
                </div>
            </div>
            <div class="panel-body">
                <form method="post" action="add_rel_datos_pub_dec.php">
                    <div class="row" style="margin-top: -40px">
                        <label style="font-size: 17px;">¿Está de acuerdo en hacer públicos sus datos patrimoniales?</label>
                    </div>
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="col-md-1">
                            <div class="switch-container" style="margin-top: 2px;">
                                <span class="switch-label" style="font-size: 12px;">No</span>
                                <label class="switch" style="margin-top: 5px;">
                                    <input type="checkbox" name="de_acuerdo">
                                    <span class="slider"></span>
                                </label>
                                <span class="switch-label" style="margin-left: 8px; font-size: 12px;">Sí</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <p style="font-size: 15px;">En caso de que su respuesta sea afirmativa, pero usted desea que sea parcialmente pública, deberá seleccionar la información que se excepcione de las siguientes opciones:</p>
                    </div>
                    <div class="row" style="margin-left:30%">
                        <div class="col-md-6">
                            <input type="checkbox" name="ingresos_netos" value="1"> En ingresos netos, los correspondientes a los recibidos por actividad industrial y/o comercial, financiera y otros, así como el monto total de los ingresos considerados a los antes citados.<br><br>
                            <input type="checkbox" name="bienes_inmuebles" value="1"> En bienes inmuebles, el valor de la contra prestación y moneda.<br><br>
                            <input type="checkbox" name="bienes_muebles" value="1"> En bienes muebles, el valor de la contraprestación y moneda.<br><br>
                            <input type="checkbox" name="vehiculos" value="1"> En vehículos, el valor de la contraprestación y moneda. <br><br>
                            <input type="checkbox" name="inversiones" value="1"> En inversiones, cuentas bancarias y otro tipo de valores, el saldo.<br><br>
                            <input type="checkbox" name="adeudos" value="1"> En adeudos, el monto original, el saldo y el monto de los pagos realizados.<br>
                        </div>
                    </div><br>

                    <a href="rel_datos_pub_dec.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_rel_datos_pub_dec" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>