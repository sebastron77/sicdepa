<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Adeudos del declarante';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_adeudos', (int)$_GET['id'], 'id_cat_rel_detalle_adeudos');
$operaciones = find_all('cat_tipo_operacion');
$titulares = find_all('cat_titular');
$adeudos = find_all('cat_tipo_adeudo');
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
</style>
<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$detalles['id_cat_rel_detalle_adeudos'];
        $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
        $id_cat_tipo_adeudo = $_POST['id_cat_tipo_adeudo'];
        $num_cuenta = $_POST['num_cuenta'];
        $mexico = $_POST['mexico'];
        $inst_razon_soc = $_POST['inst_razon_soc'];
        $extranjero = $_POST['extranjero'];
        $pais_inst_razon_soc = $_POST['pais_inst_razon_soc'];
        $fecha_otorgamiento = $_POST['fecha_otorgamiento'];
        $monto_orig_adeudo = $_POST['monto_orig_adeudo'];
        $tipo_moneda_or = $_POST['tipo_moneda_or'];
        $saldo_inso = $_POST['saldo_inso'];
        $tipo_moneda_ins = $_POST['tipo_moneda_ins'];
        $id_cat_plazo_adeudo = $_POST['id_cat_plazo_adeudo'];
        $id_cat_titular = $_POST['id_cat_titular'];
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        $sql = "UPDATE rel_detalle_adeudos SET id_rel_declaracion='{$declaracion}', id_cat_tipo_operacion='{$id_cat_tipo_operacion}', 
                id_cat_tipo_adeudo='{$id_cat_tipo_adeudo}', num_cuenta='{$num_cuenta}', mexico='{$mexico}', inst_razon_soc='{$inst_razon_soc}', 
                extranjero='{$extranjero}', pais_inst_razon_soc='{$pais_inst_razon_soc}', fecha_otorgamiento='{$fecha_otorgamiento}', 
                monto_orig_adeudo='{$monto_orig_adeudo}', tipo_moneda_or='{$tipo_moneda_or}', saldo_inso='{$saldo_inso}', tipo_moneda_ins='{$tipo_moneda_ins}', 
                id_cat_plazo_adeudo='{$id_cat_plazo_adeudo}', id_cat_titular='{$id_cat_titular}'  
                WHERE id_cat_rel_detalle_adeudos ='{$db->escape($id)}'";

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";

        $result = $db->query($sql);
        $result2 = $db->query($sql2);
        
        if (($result && $db->affected_rows() === 1) && ($result2 && $db->affected_rows() === 1)) {
            $session->msg('s', "La información de la(s) cuenta(s) bancarias ha sido editada con éxito. Continúa con los posibles conflictos de interés (si los hay).");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó inf. cuenta banc.' . $detalles['id_cat_rel_detalle_adeudos'] . '.', 1);
            redirect('conflicto.php', false);
        } else {
            $session->msg('d', ' No se pudo editar la información de la(s) cuenta(s).');
            redirect('edit_adeudos.php?id=' . (int)$detalles['id_cat_rel_detalle_adeudos'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_adeudos.php?id=' . (int)$detalles['id_cat_rel_detalle_adeudos'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<div class="modal">
    <div class="modal-content">
        <div class="row">
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group clearfix">
                </div>
            </div>
            <div class="panel-body">
                <strong>
                    <span class="material-symbols-outlined" style="margin-top: -40px; color: #3a3d44; font-size: 35px;">
                        credit_card
                    </span>
                    <p style="margin-top: -38px; margin-left: 40px; font-size: 20px;">Inversiones, cuentas bancarias y otro tipo de valores (situación actual)</p>
                </strong>
                <form method="post" action="edit_adeudos.php?id=<?php echo (int)$detalles['id_cat_rel_detalle_adeudos']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                    <select class="form-control" id="id_cat_tipo_operacion" name="id_cat_tipo_operacion">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($operaciones as $operacion) : ?>
                                            <?php $x = $operacion['id_cat_tipo_operacion'];
                                            if ($x != 2 && $x != 3 && $x != 5 && $x != 8) : ?>
                                                <option <?php if ($operacion['id_cat_tipo_operacion'] === $detalles['id_cat_tipo_operacion'])
                                                            echo 'selected="selected"'; ?> value="<?php echo $operacion['id_cat_tipo_operacion']; ?>">
                                                    <?php echo ucwords($operacion['descripcion']) ?>
                                                <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_tipo_adeudo">Tipo de adeudo</label>
                                    <select class="form-control" id="id_cat_tipo_adeudo" name="id_cat_tipo_adeudo">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($adeudos as $adeudo) : ?>
                                            <option <?php if ($adeudo['id_cat_tipo_adeudo'] === $detalles['id_cat_tipo_adeudo'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $adeudo['id_cat_tipo_adeudo']; ?>">
                                                <?php echo ucwords($adeudo['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="num_cuenta">Número de cuenta o contrato</label>
                                    <input class="form-control" type="text" name="num_cuenta" id="num_cuenta" value="<?php echo $detalles['num_cuenta'] ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="mexico">México</label>
                                    <select class="form-control" name="mexico" id="mexico">
                                        <option value="">Opciones</option>
                                        <option <?php if ($detalles['mexico'] == '0') echo 'selected="selected"'; ?> value="0">No</option>
                                        <option <?php if ($detalles['mexico'] == '1') echo 'selected="selected"'; ?> value="1">Sí</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inst_razon_soc">Institución o razón social</label>
                                    <input class="form-control" type="text" name="inst_razon_soc" id="inst_razon_soc" value="<?php echo $detalles['inst_razon_soc'] ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="extranjero">Extranjero</label>
                                    <select class="form-control" name="extranjero" id="extranjero">
                                        <option value="">Opciones</option>
                                        <option <?php if ($detalles['extranjero'] == '0') echo 'selected="selected"'; ?> value="0">No</option>
                                        <option <?php if ($detalles['extranjero'] == '1') echo 'selected="selected"'; ?> value="1">Sí</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="pais_inst_razon_soc">País e institución o razón social</label>
                                    <input class="form-control" type="text" name="pais_inst_razon_soc" id="pais_inst_razon_soc" value="<?php echo $detalles['pais_inst_razon_soc'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="fecha_otorgamiento">Fecha del otorgamiento</label>
                                    <input class="form-control" type="date" name="fecha_otorgamiento" id="fecha_otorgamiento" value="<?php echo $detalles['fecha_otorgamiento'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="monto_orig_adeudo">Monto original del adeudo SIN CENTAVOS</label>
                                    <input class="form-control" type="text" name="monto_orig_adeudo" id="monto_orig_adeudo" value="<?php echo $detalles['monto_orig_adeudo'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="tipo_moneda_or">Tipo moneda (especificar)</label>
                                    <input class="form-control" type="text" name="tipo_moneda_or" id="tipo_moneda_or" value="<?php echo $detalles['tipo_moneda_or'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="saldo_inso">Saldo Insoluto a la fecha del encargo que inicia SIN CENTAVOS </label>
                                    <input class="form-control" type="text" name="saldo_inso" id="saldo_inso" value="<?php echo $detalles['saldo_inso'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="tipo_moneda_ins">Tipo de moneda (especificar)</label>
                                    <input class="form-control" type="text" name="tipo_moneda_ins" id="tipo_moneda_ins" value="<?php echo $detalles['tipo_moneda_ins'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_plazo_adeudo">Plazo del adeudo<p style="font-size:9px">-Vehículos (meses)</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Crédito Hipotecario (años)</p>
                                    </label>
                                    <input class="form-control" type="text" name="id_cat_plazo_adeudo" id="id_cat_plazo_adeudo" value="<?php echo $detalles['id_cat_plazo_adeudo'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_titular">Titular</label>
                                    <select class="form-control" id="id_cat_titular" name="id_cat_titular">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($titulares as $titular) : ?>
                                            <option <?php if ($titular['id_cat_titular'] === $detalles['id_cat_titular'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $titular['id_cat_titular']; ?>">
                                                <?php echo ucwords($titular['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="adeudos.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>