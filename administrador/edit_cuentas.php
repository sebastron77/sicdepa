<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Vehículos';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_inv_cbanc', (int)$_GET['id'], 'id_rel_detal_inv_cbanc');
$operaciones = find_all('cat_tipo_operacion');
$titulares = find_all('cat_titular');
$inversiones = find_all('cat_tipo_inversion');
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
        $id = (int)$detalles['id_rel_detal_inv_cbanc'];
        $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
            $id_cat_titular = $_POST['id_cat_titular'];
            $num_cuenta = $_POST['num_cuenta'];
            $mexico = $_POST['mexico'];
            $inst_razon_soc = $_POST['inst_razon_soc'];
            $extranjero = $_POST['extranjero'];
            $inst_razon_soc_ext = $_POST['inst_razon_soc_ext'];
            $pais_localiza = $_POST['pais_localiza'];
            $saldo_fecha_toma = $_POST['saldo_fecha_toma'];
            $tipo_moneda = $_POST['tipo_moneda'];
            $id_cat_tipo_inversion = $_POST['id_cat_tipo_inversion'];

        $sql = "UPDATE rel_detalle_inv_cbanc SET id_cat_tipo_operacion='{$id_cat_tipo_operacion}', id_cat_titular='{$id_cat_titular}', 
                num_cuenta='{$num_cuenta}', mexico='{$mexico}', inst_razon_soc='{$inst_razon_soc}', extranjero='{$extranjero}', 
                inst_razon_soc_ext='{$inst_razon_soc_ext}', pais_localiza='{$pais_localiza}', saldo_fecha_toma='{$saldo_fecha_toma}', 
                tipo_moneda='{$tipo_moneda}', id_cat_tipo_inversion='{$id_cat_tipo_inversion}' 
                WHERE id_rel_detal_inv_cbanc ='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "La información de la(s) cuenta(s) ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó remun. anio anterior' . $detalles['id_rel_detal_inv_cbanc'] . '.', 1);
            redirect('edit_cuentas.php?id=' . (int)$detalles['id_rel_detal_inv_cbanc'], false);
        } else {
            $session->msg('d', ' No se pudo editar la información de la(s) cuenta(s).');
            redirect('edit_cuentas.php?id=' . (int)$detalles['id_rel_detal_inv_cbanc'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_cuentas.php?id=' . (int)$detalles['id_rel_detal_inv_cbanc'], false);
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
                <form method="post" action="edit_cuentas.php?id=<?php echo (int)$detalles['id_rel_detal_inv_cbanc']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                    <select class="form-control" id="id_cat_tipo_operacion" name="id_cat_tipo_operacion">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($operaciones as $operacion) : ?>
                                            <?php $x = $operacion['id_cat_tipo_operacion'];
                                            if ($x != 2 && $x != 5 && $x != 7 && $x != 8) : ?>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="num_cuenta">Número de Cuenta O Contrato</label>
                                    <input class="form-control" type="text" name="num_cuenta" id="num_cuenta" value="<?php echo $detalles['num_cuenta'];?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="mexico">México</label>
                                    <select class="form-control" name="mexico" id="mexico">
                                        <option value="">Opciones</option>
                                        <option <?php if ($detalles['mexico'] == '0') echo 'selected="selected"'; ?> value="0">
                                            No
                                        </option>
                                        <option <?php if ($detalles['mexico'] == '1') echo 'selected="selected"'; ?> value="1">
                                            Sí
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inst_razon_soc">Institución o Razón Social</label>
                                    <input class="form-control" type="text" name="inst_razon_soc" id="inst_razon_soc" value="<?php echo $detalles['inst_razon_soc'];?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="extranjero">Extranjero</label>
                                    <select class="form-control" name="extranjero" id="extranjero">
                                        <option value="">Opciones</option>
                                        <option <?php if ($detalles['extranjero'] == '0') echo 'selected="selected"'; ?> value="0">
                                            No
                                        </option>
                                        <option <?php if ($detalles['extranjero'] == '1') echo 'selected="selected"'; ?> value="1">
                                            Sí
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inst_razon_soc_ext">Institución o Razón Social</label>
                                    <input class="form-control" type="text" name="inst_razon_soc_ext" id="inst_razon_soc_ext" value="<?php echo $detalles['inst_razon_soc_ext'];?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="pais_localiza">País donde se localiza</label>
                                    <input class="form-control" type="text" name="pais_localiza" id="pais_localiza" value="<?php echo $detalles['pais_localiza'];?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="saldo_fecha_toma">Saldo a la fecha de toma o posesión del encargo que inicia SIN CENTAVOS</label>
                                    <input class="form-control" type="text" name="saldo_fecha_toma" id="saldo_fecha_toma" value="<?php echo $detalles['saldo_fecha_toma'];?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="tipo_moneda">Tipo de moneda (especificar)</label>
                                    <input class="form-control" type="text" name="tipo_moneda" id="tipo_moneda" value="<?php echo $detalles['tipo_moneda'];?>">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_tipo_inversion">Tipo de inversión</label>
                                    <select class="form-control" id="id_cat_tipo_inversion" name="id_cat_tipo_inversion">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($inversiones as $inversion) : ?>
                                            <option <?php if ($inversion['id_cat_tipo_inversion'] === $detalles['id_cat_tipo_inversion'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $inversion['id_cat_tipo_inversion']; ?>">
                                                <?php echo ucwords($inversion['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="cuentas.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>