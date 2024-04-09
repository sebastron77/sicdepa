<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Conflicto de interés';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_conflicto_declarante', (int)$_GET['id'], 'id_rel_detalle_conflicto_declarante');
$operaciones = find_all('cat_tipo_op_conf');
$frecs_anual = find_all('cat_frec_anual');
$tipos_pers_jur = find_all('cat_tipo_pers_jurid');
$resps_conf = find_all('cat_tipo_resp_conf');
$naturs_vinc = find_all('cat_natur_vinc');
$particips_direc = find_all('cat_particip_direc');
$tipos_colab = find_all('cat_tipo_colab');
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
        $id = (int)$detalles['id_rel_detalle_conflicto_declarante'];
        $id_cat_tipo_op_conf = $_POST['id_cat_tipo_op_conf'];
        $nombre_entidad = $_POST['nombre_entidad'];
        $id_cat_frec_anual = $_POST['id_cat_frec_anual'];
        $otra_frec_anual = $_POST['otra_frec_anual'];
        $id_cat_tipo_pers_jur = $_POST['id_cat_tipo_pers_jur'];
        $otra_pers_jurid = $_POST['otra_pers_jurid'];
        $id_cat_resp_conf = $_POST['id_cat_resp_conf'];
        $id_cat_natur_vinc = $_POST['id_cat_natur_vinc'];
        $otro_nat_vinc = $_POST['otro_nat_vinc'];
        $antiguedad_vinc_anios = $_POST['antiguedad_vinc_anios'];
        $id_cat_particip_direc = $_POST['id_cat_particip_direc'];
        $id_cat_tipo_colab = $_POST['id_cat_tipo_colab'];
        $ubicacion = $_POST['ubicacion'];
        $observaciones_aclaraciones = $_POST['observaciones_aclaraciones'];

        $sql = "UPDATE rel_detalle_conflicto_declarante SET id_cat_tipo_operacion='{$id_cat_tipo_op_conf}', nombre_entidad='{$nombre_entidad}', 
                id_cat_frec_anual='{$id_cat_frec_anual}', otra_frec_anual='{$otra_frec_anual}', id_cat_tipo_pers_jur='{$id_cat_tipo_pers_jur}', otra_pers_jurid='{$otra_pers_jurid}', id_cat_resp_conf='{$id_cat_resp_conf}', id_cat_natur_vinc='{$id_cat_natur_vinc}', 
                otro_nat_vinc='{$otro_nat_vinc}', antiguedad_vinc_anios='{$antiguedad_vinc_anios}', id_cat_particip_direc='{$id_cat_particip_direc}', id_cat_tipo_colab='{$id_cat_tipo_colab}', ubicacion='{$ubicacion}', observaciones_aclaraciones='{$observaciones_aclaraciones}'
                WHERE id_rel_detalle_conflicto_declarante ='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "La información de la declaración de posible conflicto de interés ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó inf. cuenta banc.' . $detalles['id_rel_detalle_conflicto_declarante'] . '.', 1);
            redirect('edit_conflicto.php?id=' . (int)$detalles['id_rel_detalle_conflicto_declarante'], false);
        } else {
            $session->msg('d', ' No se pudo editar la información de la declaración de posible conflicto de interés.');
            redirect('edit_conflicto.php?id=' . (int)$detalles['id_rel_detalle_conflicto_declarante'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_conflicto.php?id=' . (int)$detalles['id_rel_detalle_conflicto_declarante'], false);
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
                        person_alert
                    </span>
                    <p style="margin-top: -37px; margin-left: 40px; font-size: 20px;">Editar declaración de posible conflicto de interés</p>
                </strong>
                <form method="post" action="edit_conflicto.php?id=<?php echo (int)$detalles['id_rel_detalle_conflicto_declarante']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_tipo_op_conf">Tipo de operación</label>
                                    <select class="form-control" name="id_cat_tipo_op_conf">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($operaciones as $operacion) : ?>
                                            <option <?php if ($operacion['id_cat_tipo_op_conf'] === $detalles['id_cat_tipo_operacion'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $operacion['id_cat_tipo_op_conf']; ?>">
                                                <?php echo ucwords($operacion['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Nombre de la entidad (Empresa, asociación, sindicato, etc.)</label>
                                    <input class="form-control" type="text" name="nombre_entidad" value="<?php echo $detalles['nombre_entidad']?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_frec_anual">Frecuencia anual</label>
                                    <select class="form-control" name="id_cat_frec_anual">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($frecs_anual as $frec) : ?>
                                            <option <?php if ($frec['id_cat_frec_anual'] === $detalles['id_cat_frec_anual'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $frec['id_cat_frec_anual']; ?>">
                                                <?php echo ucwords($frec['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Otra frecuencia anual</label>
                                    <input class="form-control" type="text" name="otra_frec_anual" value="<?php echo $detalles['otra_frec_anual']?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_tipo_pers_jur">Tipo de persona jurídica</label>
                                    <select class="form-control" name="id_cat_tipo_pers_jur">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($tipos_pers_jur as $pers) : ?>
                                            <option <?php if ($pers['id_cat_tipo_pers_jurid'] === $detalles['id_cat_tipo_pers_jur'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $pers['id_cat_tipo_pers_jurid']; ?>">
                                                <?php echo ucwords($pers['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Otro tipo de persona jurídica</label>
                                    <input class="form-control" type="text" name="otra_pers_jurid" value="<?php echo $detalles['otra_pers_jurid']?>">
                                </div>
                            </div>
                            <div class="col-md-2  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_resp_conf">Responsable del posible conflicto de interés</label>
                                    <select class="form-control" name="id_cat_resp_conf">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($resps_conf as $resp) : ?>
                                            <option <?php if ($resp['id_cat_tipo_resp_conf'] === $detalles['id_cat_resp_conf'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $resp['id_cat_tipo_resp_conf']; ?>">
                                                <?php echo ucwords($resp['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_natur_vinc">Naturaleza del vínculo</label>
                                    <select class="form-control" name="id_cat_natur_vinc">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($naturs_vinc as $vinc) : ?>
                                            <option <?php if ($vinc['id_cat_natur_vinc'] === $detalles['id_cat_natur_vinc'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $vinc['id_cat_natur_vinc']; ?>">
                                                <?php echo ucwords($vinc['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Otro tipo de vínculo</label>
                                    <input class="form-control" type="text" name="otro_nat_vinc" value="<?php echo $detalles['otro_nat_vinc']?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Antigüedad del vínculo (Años)</label>
                                    <input class="form-control" type="text" name="antiguedad_vinc_anios" value="<?php echo $detalles['antiguedad_vinc_anios']?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_particip_direc">Participación en la dirección o administración</label>
                                    <select class="form-control" name="id_cat_particip_direc">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($particips_direc as $part) : ?>
                                            <option <?php if ($part['id_cat_particip_direc'] === $detalles['id_cat_particip_direc'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $part['id_cat_particip_direc']; ?>">
                                                <?php echo ucwords($part['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_tipo_colab">Tipo de colaboración o aporte</label>
                                    <select class="form-control" name="id_cat_tipo_colab">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($tipos_colab as $colab) : ?>
                                            <option <?php if ($colab['id_cat_tipo_colab'] === $detalles['id_cat_tipo_colab'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $colab['id_cat_tipo_colab']; ?>">
                                                <?php echo ucwords($colab['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Ubicación (Ciudad o Población, Entidad Federativa y País)</label>
                                    <input class="form-control" type="text" name="ubicacion" value="<?php echo $detalles['ubicacion']?>">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Observaciones o aclaraciones</label>
                                    <textarea class="form-control" name="observaciones_aclaraciones" cols="10" rows="3"><?php echo $detalles['observaciones_aclaraciones']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="conflicto.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>