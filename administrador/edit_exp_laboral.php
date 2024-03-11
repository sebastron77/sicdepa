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
$estudios = find_by_id('rel_detalle_estudios', $_GET['id'], 'id_rel_detalle_estudios')
?>

<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$estudios['id_rel_detalle_estudios'];
        if ($estudios['id_cat_escolaridad'] <= 3) {
            $inst_educativa1 = remove_junk($db->escape($_POST['inst_educativa1']));
            $id_cat_periodo_cursado1 = remove_junk($db->escape($_POST['id_cat_periodo_cursado1']));
            $id_cat_documento_obtenido1 = remove_junk($db->escape($_POST['id_cat_documento_obtenido1']));
            $id_cat_estatus_estudios1 = remove_junk($db->escape($_POST['id_cat_estatus_estudios1']));

            $sql = "UPDATE rel_detalle_estudios SET inst_educativa='{$inst_educativa1}', id_cat_estatus_estudios='{$id_cat_estatus_estudios1}', id_cat_periodo_cursado='{$id_cat_periodo_cursado1}', id_cat_documento_obtenido='{$id_cat_documento_obtenido1}' 
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

            $sql = "UPDATE rel_detalle_estudios SET inst_educativa='{$inst_educativa}', id_cat_periodo_cursado='{$id_cat_periodo_cursado}', id_cat_documento_obtenido='{$id_cat_documento_obtenido}', ubic_inst='{$ubic_inst}', id_cat_ent_fed='{$id_cat_ent_fed}', id_cat_mun='{$id_cat_mun}', carrera_area_con='{$carrera_area_con}', id_cat_estatus_estudios='{$id_cat_estatus_estudios}', num_ced_prof='{$num_ced_prof}' WHERE id_rel_detalle_estudios ='{$db->escape($id)}'";
        }
        $result = $db->query($sql);

        if ($db->query($query)) {
            //sucess
            $session->msg('s', "La información de experiencia laboral ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó su escolaridad: ' . $id_cat_esc . '.', 2);
            redirect('edit_datos_curri_declarante.php?id=' . (int)$estudios['id_rel_detalle_estudios'], false);
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
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="form-group clearfix">
            <a href="datos_curri_declarante.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
        </div>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="puesto">Puesto</label>
                        <input type="text" class="form-control" name="puesto" value="<?php echo remove_junk($e_laboral['puesto']); ?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="institucion">Institución</label>
                        <input type="text" class="form-control" name="institucion" value="<?php echo remove_junk($e_laboral['institucion']); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inicio">Fecha de inicio</label>
                        <input type="date" class="form-control" name="inicio" value="<?php echo remove_junk($e_laboral['inicio']); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="conclusion">Fecha de Conclusión</label>
                        <input type="date" class="form-control" name="conclusion" value="<?php echo remove_junk($e_laboral['conclusion']); ?>">
                    </div>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>