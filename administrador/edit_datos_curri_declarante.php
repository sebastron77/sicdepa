<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos Curriculares del Declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$cat_municipios = find_all_cat_municipios();
$cat_nacionalidad = find_all('cat_nacionalidades');
$cat_entidad_fed = find_all('cat_entidad_fed');
$cat_escolaridad = find_all('cat_escolaridad');
$cat_periodos = find_all('cat_periodos_cursados');
$cat_estatus = find_all('cat_estatus_estudios');
$cat_documentos = find_all('cat_documento_obtenido');
$estudios = find_by_id('rel_detalle_estudios', $_GET['id'], 'id_rel_detalle_estudios');
$id_rel_declaracion = find_by_id_dec((int)$id_detalle_usuario);
page_require_level(3);
?>

<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$estudios['id_rel_detalle_estudios'];

        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        if ($estudios['id_cat_escolaridad'] <= 3) {
            $inst_educativa1 = remove_junk($db->escape($_POST['inst_educativa1']));
            $id_cat_periodo_cursado1 = remove_junk($db->escape($_POST['id_cat_periodo_cursado1']));
            $id_cat_documento_obtenido1 = remove_junk($db->escape($_POST['id_cat_documento_obtenido1']));
            $id_cat_estatus_estudios1 = remove_junk($db->escape($_POST['id_cat_estatus_estudios1']));

            $sql = "UPDATE rel_detalle_estudios SET id_rel_declaracion='{$declaracion}', inst_educativa='{$inst_educativa1}', id_cat_estatus_estudios='{$id_cat_estatus_estudios1}', id_cat_periodo_cursado='{$id_cat_periodo_cursado1}', id_cat_documento_obtenido='{$id_cat_documento_obtenido1}' 
            WHERE id_rel_detalle_estudios ='{$db->escape($id)}'";
        }
        if ($estudios['id_cat_escolaridad'] >= 4) {
            $inst_educativa = remove_junk($db->escape($_POST['inst_educativa']));
            $id_cat_periodo_cursado = remove_junk($db->escape($_POST['id_cat_periodo_cursado']));
            $id_cat_documento_obtenido = remove_junk($db->escape($_POST['id_cat_documento_obtenido']));
            $id_cat_estatus_estudios = remove_junk($db->escape($_POST['id_cat_estatus_estudios']));
            $ubic_inst = remove_junk($db->escape($_POST['ubic_inst']));
            $id_cat_ent_fed = remove_junk($db->escape($_POST['id_cat_ent_fed']));
            $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
            $carrera_area_con = remove_junk($db->escape($_POST['carrera_area_con']));
            $num_ced_prof = remove_junk($db->escape($_POST['num_ced_prof']));

            $sql = "UPDATE rel_detalle_estudios SET id_rel_declaracion='{$declaracion}', inst_educativa='{$inst_educativa}', id_cat_periodo_cursado='{$id_cat_periodo_cursado}', id_cat_documento_obtenido='{$id_cat_documento_obtenido}', ubic_inst='{$ubic_inst}', id_cat_ent_fed='{$id_cat_ent_fed}', id_cat_mun='{$id_cat_mun}', carrera_area_con='{$carrera_area_con}', id_cat_estatus_estudios='{$id_cat_estatus_estudios}', num_ced_prof='{$num_ced_prof}' WHERE id_rel_detalle_estudios ='{$db->escape($id)}'";
        }
        $result = $db->query($sql);
        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";

        $result2 = $db->query($sql2);

        if (($db->query($query)) && ($result2 && $db->affected_rows() === 1)) {
            //sucess
            $session->msg('s', "La información de los datos curriculares del declarante ha sido editada con éxito. Continúa con la información de tu experiencia laboral.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó su escolaridad: ' . $id_cat_esc . '.', 2);
            // updateLastArchivo('edit_datos_curri_declarante.php', $declaracion);
            redirect('exp_laboral.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la información.');
            redirect('edit_datos_curri_declarante.php?id=' . (int)$e_detalle['id_rel_detalle_estudios'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_datos_curri_declarante.php?id=' . (int)$e_detalle['id_rel_detalle_estudios'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="form-group clearfix">
    <a href="datos_curri_declarante.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
    </a>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>
            <span class="material-symbols-outlined" style="color: #3a3d44">
                school
            </span>
            <p style="margin-top: -22px; margin-left: 32px;">Datos Curriculares del Declarante</p>
        </strong>
    </div>

    <div class="panel-body">
        <p style="margin-top: -10px; font-weight: bold;">ESCOLARIDAD</p>
        <form method="post" action="edit_datos_curri_declarante.php?id=<?php echo (int)$estudios['id_rel_detalle_estudios']; ?>">
            <?php if ($estudios['id_cat_escolaridad'] <= 3) : ?>
                <div class="row">
                    <p style="margin-top: -10px; font-weight: bold;">SI ES PRIMARIA, SECUNDARIA O BACHILLERATO ESPECIFIQUE:</p>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inst_educativa">Institución Educativa</label>
                            <input type="text" class="form-control" name="inst_educativa1" value="<?php echo $estudios['inst_educativa'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_estatus_estudios">Estatus</label>
                            <select class="form-control" id="id_cat_estatus_estudios" name="id_cat_estatus_estudios1">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_estatus as $estatus) : ?>
                                    <option <?php if ($estatus['id_cat_estatus_estudios'] === $estudios['id_cat_estatus_estudios'])
                                                echo 'selected="selected"'; ?> value="<?php echo $estatus['id_cat_estatus_estudios']; ?>">
                                        <?php echo ucwords($estatus['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_periodo_cursado">Periodos Cursados</label>
                            <select class="form-control" id="id_cat_periodo_cursado" name="id_cat_periodo_cursado1">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_periodos as $periodo) : ?>
                                    <option <?php if ($periodo['id_cat_periodo_cursado'] === $estudios['id_cat_periodo_cursado'])
                                                echo 'selected="selected"'; ?> value="<?php echo $periodo['id_cat_periodo_cursado']; ?>">
                                        <?php echo ucwords($periodo['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_documento_obtenido">Documento Obtenido</label>
                            <select class="form-control" id="id_cat_documento_obtenido" name="id_cat_documento_obtenido1">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_documentos as $documento) : ?>
                                    <option <?php if ($documento['id_cat_documento_obtenido'] === $estudios['id_cat_documento_obtenido'])
                                                echo 'selected="selected"'; ?> value="<?php echo $documento['id_cat_documento_obtenido']; ?>">
                                        <?php echo ucwords($documento['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($estudios['id_cat_escolaridad'] >= 4) : ?>
                <div class="row">
                    <p style="margin-top: -10px; font-weight: bold;">SI ES CARRERA TÉCNICA, LICENCIATURA, MAESTRÍA O DIPLOMADO ESPECIFIQUE:</p>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ubic_inst">Lugar donde se ubica la Institución Educativa</label>
                            <input type="text" class="form-control" name="ubic_inst" value="<?php echo $estudios['ubic_inst'] ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_ent_fed">Entidad Federativa</label>
                            <select class="form-control" id="id_cat_ent_fed" name="id_cat_ent_fed">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidad_fed as $entidad) : ?>
                                    <option <?php if ($entidad['id_cat_ent_fed'] === $estudios['id_cat_ent_fed'])
                                                echo 'selected="selected"'; ?> value="<?php echo $entidad['id_cat_ent_fed']; ?>">
                                        <?php echo ucwords($entidad['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <select class="form-control" id="id_cat_mun" name="id_cat_mun">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_municipios as $municipio) : ?>
                                    <option <?php if ($municipio['id_cat_mun'] === $estudios['id_cat_mun'])
                                                echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>">
                                        <?php echo ucwords($municipio['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inst_educativa">Institución Educativa</label>
                            <input type="text" class="form-control" name="inst_educativa" value="<?php echo $estudios['inst_educativa'] ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="carrera_area_con">Carrera o área del conocimiento</label>
                            <input type="text" class="form-control" name="carrera_area_con" value="<?php echo $estudios['carrera_area_con'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_estatus_estudios">Estatus</label>
                            <select class="form-control" id="id_cat_estatus_estudios" name="id_cat_estatus_estudios">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_estatus as $estatus) : ?>
                                    <option <?php if ($estatus['id_cat_estatus_estudios'] === $estudios['id_cat_estatus_estudios'])
                                                echo 'selected="selected"'; ?> value="<?php echo $estatus['id_cat_estatus_estudios']; ?>">
                                        <?php echo ucwords($estatus['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_periodo_cursado">Periodos Cursados</label>
                            <select class="form-control" id="id_cat_periodo_cursado" name="id_cat_periodo_cursado">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_periodos as $periodo) : ?>
                                    <option <?php if ($periodo['id_cat_periodo_cursado'] === $estudios['id_cat_periodo_cursado'])
                                                echo 'selected="selected"'; ?> value="<?php echo $periodo['id_cat_periodo_cursado']; ?>">
                                        <?php echo ucwords($periodo['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_documento_obtenido">Documento Obtenido</label>
                            <select class="form-control" id="id_cat_documento_obtenido" name="id_cat_documento_obtenido">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_documentos as $documento) : ?>
                                    <option <?php if ($documento['id_cat_documento_obtenido'] === $estudios['id_cat_documento_obtenido'])
                                                echo 'selected="selected"'; ?> value="<?php echo $documento['id_cat_documento_obtenido']; ?>">
                                        <?php echo ucwords($documento['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="num_ced_prof">Número de Cédula Profesional</label>
                            <input type="text" class="form-control" name="num_ced_prof" value="<?php echo $estudios['num_ced_prof'] ?>">
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>