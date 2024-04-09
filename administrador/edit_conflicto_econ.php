<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Conflicto de interés';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_conflicto_part_econom', (int)$_GET['id'], 'id_rel_detalle_conflicto_part_econom');
$operaciones = find_all('cat_tipo_op_conf');
$naturs_vinc = find_all('cat_natur_vinc');
$sociedades = find_all('cat_soc_part');
$resps_conf = find_all('cat_tipo_resp_conf');
$parti_direc = find_all('cat_particip_direc');
$soc_part = find_all('cat_soc_part');
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
        $id = (int)$detalles['id_rel_detalle_conflicto_part_econom'];
        $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
        $nombre_empresa = $_POST['nombre_empresa'];
        $insc_reg_publ = $_POST['insc_reg_publ'];
        $id_cat_tipo_soc = $_POST['id_cat_tipo_soc'];
        $otra_soc = $_POST['otra_soc'];
        $antiguedad_part_anios = $_POST['antiguedad_part_anios'];
        $id_cat_resp_conf = $_POST['id_cat_resp_conf'];
        $fecha_const_soc = $_POST['fecha_const_soc'];
        $ubicacion = $_POST['ubicacion'];
        $sector_indust = $_POST['sector_indust'];
        $tipo_particip = $_POST['tipo_particip'];
        $especi_otro_particip = $_POST['especi_otro_particip'];
        $id_cat_particip_direc = $_POST['id_cat_particip_direc'];
        $observaciones = $_POST['observaciones'];

        $sql = "UPDATE rel_detalle_conflicto_part_econom SET id_cat_tipo_operacion='{$id_cat_tipo_operacion}', 
                id_cat_tipo_operacion='{$id_cat_tipo_operacion}', nombre_empresa='{$nombre_empresa}', insc_reg_publ='{$insc_reg_publ}', 
                id_cat_tipo_soc='{$id_cat_tipo_soc}', otra_soc='{$otra_soc}', antiguedad_part_anios='{$antiguedad_part_anios}', 
                id_cat_resp_conf='{$id_cat_resp_conf}', fecha_const_soc='{$fecha_const_soc}', ubicacion='{$ubicacion}', sector_indust='{$sector_indust}',
                tipo_particip='{$tipo_particip}', especi_otro_particip='{$especi_otro_particip}', id_cat_particip_direc='{$id_cat_particip_direc}', observaciones='{$observaciones}'
                WHERE id_rel_detalle_conflicto_part_econom ='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "La información del conflicto de interés por participaciones económicas ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó inf. cuenta banc.' . $detalles['id_rel_detalle_conflicto_part_econom'] . '.', 1);
            redirect('edit_conflicto_econ.php?id=' . (int)$detalles['id_rel_detalle_conflicto_part_econom'], false);
        } else {
            $session->msg('d', ' No se pudo editar la información del conflicto de interés por participaciones económicas.');
            redirect('edit_conflicto_econ.php?id=' . (int)$detalles['id_rel_detalle_conflicto_part_econom'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_conflicto_econ.php?id=' . (int)$detalles['id_rel_detalle_conflicto_part_econom'], false);
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
                <form method="post" action="edit_conflicto_econ.php?id=<?php echo (int)$detalles['id_rel_detalle_conflicto_part_econom']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                    <select class="form-control" name="id_cat_tipo_operacion">
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
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Nombre de la empresa o sociedad o persona física</label>
                                    <input class="form-control" type="text" name="nombre_empresa" value="<?php echo $detalles['nombre_empresa']?>">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Inscripción en el registro público u otro dato que permita su identificación (en su caso)</label>
                                    <input class="form-control" type="text" name="insc_reg_publ" value="<?php echo $detalles['insc_reg_publ']?>">
                                </div>
                            </div>
                            <div class="col-md-3  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_tipo_soc">Tipo de sociedad en la que se participa o con la que se contrata (en su caso)</label>
                                    <select class="form-control" name="id_cat_tipo_soc">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($soc_part as $part) : ?>
                                            <option <?php if ($part['id_cat_soc_part'] === $detalles['id_cat_tipo_soc'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $part['id_cat_soc_part']; ?>">
                                                <?php echo ucwords($part['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Otro tipo de sociedad en que participa</label>
                                    <input class="form-control" type="text" name="otra_soc" value="<?php echo $detalles['otra_soc']?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Antigüedad de la participación o convenio (años)</label>
                                    <input class="form-control" type="text" name="antiguedad_part_anios" value="<?php echo $detalles['antiguedad_part_anios']?>">
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
                                    <label for="fecha_const_soc">Fecha de constitución de la sociedad (en su caso)</label>
                                    <input class="form-control" type="date" name="fecha_const_soc" value="<?php echo $detalles['fecha_const_soc']?>">
                                </div>
                            </div>
                            <div class="col-md-3  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="ubicacion">Ubicación (ciudad o población, entidad federativa y país)</label>
                                    <textarea class="form-control" name="ubicacion" cols="30" rows="2"><?php echo $detalles['ubicacion']?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Sector o industria (en su caso)</label>
                                    <input class="form-control" type="text" name="sector_indust" value="<?php echo $detalles['sector_indust']?>">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Tipo de participación o contrato (porcentaje de participación en el capital,
                                        partes sociales, trabajo) Especificar </label>
                                    <textarea class="form-control" name="tipo_particip" cols="30" rows="2"><?php echo $detalles['tipo_particip']?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Otro tipo de participación o contrato</label>
                                    <textarea class="form-control" name="especi_otro_particip" cols="30" rows="2"><?php echo $detalles['especi_otro_particip']?></textarea>
                                </div>
                            </div>
                            <div class="col-md-2  d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_particip_direc">Inicio de participación o contrato</label>
                                    <select class="form-control" name="id_cat_particip_direc">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($parti_direc as $part) : ?>
                                            <option <?php if ($part['id_cat_particip_direc'] === $detalles['id_cat_particip_direc'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $part['id_cat_particip_direc']; ?>">
                                                <?php echo ucwords($part['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label style="font-size: 12px;">Observaciones o aclaraciones</label>
                                    <textarea class="form-control" name="observaciones" cols="10" rows="3"><?php echo $detalles['observaciones']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="conflicto_econ.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>