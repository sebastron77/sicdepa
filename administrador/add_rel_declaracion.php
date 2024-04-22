<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Tipo de declaración';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
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
        width: 30%;
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
        width: 34% !important;
    }
</style>

<?php

// Obtener la fecha actual
$fecha_actual = date('Y-m-d');
// Calcular la fecha del año anterior utilizando strtotime
$fecha_ano_anterior = strtotime('-1 year', strtotime($fecha_actual));
// Formatear la fecha al formato de año (Y)
$ano_anterior = date('Y', $fecha_ano_anterior);

if (isset($_POST['add_rel_declaracion'])) {
    if (empty($errors)) {

        $tipo_declaracion = $_POST['tipo_declaracion'];
        $periodo = $_POST['periodo'];

        $dbh = new PDO('mysql:host=localhost;dbname=sicdepa', 'suigcedh', '9DvkVuZ915H!');

        $query1 = "INSERT INTO rel_declaracion (";
        $query1 .= "id_detalle_usuario, concluida, tipo_declaracion, periodo, fecha_actualizacion, fecha_creacion";
        $query1 .= ") VALUES (";
        $query1 .= "'{$id_detalle_usuario}', 0, '{$tipo_declaracion}', '{$periodo}', NOW(), NOW()";
        $query1 .= ")";

        $dbh->exec($query1);
        $id_declaracion = $dbh->lastInsertId();

        $query2 = "INSERT INTO bandera_continuacion (";
        $query2 .= "id_rel_declaracion, ultimo_archivo, fecha_actualizacion, fecha_creacion";
        $query2 .= ") VALUES (";
        $query2 .= "'{$id_declaracion}', '', NOW(), NOW()";
        $query2 .= ")";

        if ($db->query($query2)) {
            $session->msg('s', "Ya puedes continuar con tu declaración patrimonial.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" empezó declaración.', 1);
            redirect('detalles_usuario.php', false);
        } else {
            $session->msg('d', ' No se pudo iniciar la información.');
            redirect('admin.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('admin.php', false);
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
                <strong>
                    <span class="material-symbols-outlined" style="margin-top: -40px; color: #3a3d44; font-size: 35px;">
                        history_edu
                    </span>
                    <p style="margin-top: -37px; margin-left: 40px; font-size: 20px;">Tipo de declaración</p>
                </strong>
                <form method="post" action="add_rel_declaracion.php">
                    <div id="inputsContainer" style="display:block;">
                        <div id="inputFormRow">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="font-size: 14px;">¿Qué tipo de Declaración Patrimonial y de Intereses realizarás?</label>
                                        <select class="form-control" name="tipo_declaracion" style="width: 40%; margin-left:30%; margin-top:3%;">
                                            <option value="1" style="text-align: center;">Inicial</option>
                                            <option value="2" style="text-align: center;">Modificación</option>
                                            <option value="3" style="text-align: center;">Conclusión</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="font-size: 14px;">Fecha de encargo/ejercicio</label>
                                        <select class="form-control" name="periodo" style="width: 40%; margin-left:30%; margin-top:3%;">
                                            <option value="<?php echo $ano_anterior;?>" style="text-align: center;"><?php echo $ano_anterior;?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_rel_declaracion" class="btn btn-primary btn-md" style="margin-left:40%; margin-top:3%;">Continuar</button>
                </form>
            </div>
        </div>
        <a href="admin.php" class="btn btn-success btn-md" title="Presentar" data-toggle="tooltip" style="margin-right:85%; font-size:14px; font-weight:500;">Cerrar</a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>