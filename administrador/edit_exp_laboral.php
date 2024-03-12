<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Experiencia Laboral';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$cat_sector = find_all('cat_sector');
$cat_poder = find_all('cat_poder');
$cat_ambito = find_all('cat_ambito');
$exp_lab = find_by_id('rel_exp_laboral', $_GET['id'], 'id_rel_exp_lab');
?>

<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$exp_lab['id_rel_exp_lab'];
        $id_cat_sector = remove_junk($db->escape($_POST['id_cat_sector']));
        $id_cat_poder = remove_junk($db->escape($_POST['id_cat_poder']));
        $id_cat_ambito = remove_junk($db->escape($_POST['id_cat_ambito']));
        $nombre_inst_empresa = remove_junk($db->escape($_POST['nombre_inst_empresa']));
        $unidad_admin_area = remove_junk($db->escape($_POST['unidad_admin_area']));
        $puesto_cargo = remove_junk($db->escape($_POST['puesto_cargo']));
        $funcion_principal = remove_junk($db->escape($_POST['funcion_principal']));
        $ingreso = remove_junk($db->escape($_POST['ingreso']));
        $egreso = remove_junk($db->escape($_POST['egreso']));

        $sql = "UPDATE rel_exp_laboral SET id_cat_sector='{$id_cat_sector}', id_cat_poder='{$id_cat_poder}', id_cat_ambito='{$id_cat_ambito}', 
                nombre_inst_empresa='{$nombre_inst_empresa}', unidad_admin_area='{$unidad_admin_area}', puesto_cargo='{$puesto_cargo}', 
                funcion_principal='{$funcion_principal}', ingreso='{$ingreso}', egreso='{$egreso}'
                WHERE id_rel_exp_lab ='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($db->query($query)) {
            //sucess
            $session->msg('s', "La información de experiencia laboral ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó su experiencia laboral: ' . $id . '.', 2);
            redirect('edit_exp_laboral.php?id=' . (int)$exp_lab['id_rel_exp_lab'], false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la información.');
            redirect('edit_exp_laboral.php?id=' . (int)$exp_lab['id_rel_exp_lab'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_exp_laboral.php?id=' . (int)$exp_lab['id_rel_exp_lab'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="form-group clearfix">
    <a href="exp_laboral.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
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
        <form method="post" action="edit_exp_laboral.php?id=<?php echo (int)$exp_lab['id_rel_exp_lab']; ?>">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id_cat_sector">Sector</label>
                        <select class="form-control" id="id_cat_sector" name="id_cat_sector">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($cat_sector as $sector) : ?>
                                <option <?php if ($sector['id_cat_sector'] === $exp_lab['id_cat_sector'])
                                            echo 'selected="selected"'; ?> value="<?php echo $sector['id_cat_sector']; ?>">
                                    <?php echo ucwords($sector['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="id_cat_poder">Poder</label>
                        <select class="form-control" id="id_cat_poder" name="id_cat_poder">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($cat_poder as $poder) : ?>
                                <option <?php if ($poder['id_cat_poder'] === $exp_lab['id_cat_poder'])
                                            echo 'selected="selected"'; ?> value="<?php echo $poder['id_cat_poder']; ?>">
                                    <?php echo ucwords($poder['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id_cat_ambito">Ámbito</label>
                        <select class="form-control" id="id_cat_ambito" name="id_cat_ambito">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($cat_ambito as $ambito) : ?>
                                <option <?php if ($ambito['id_cat_ambito'] === $exp_lab['id_cat_ambito'])
                                            echo 'selected="selected"'; ?> value="<?php echo $ambito['id_cat_ambito']; ?>">
                                    <?php echo ucwords($ambito['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="nombre_inst_empresa">Institución/Empresa/Nombre, denominación o razón social</label>
                        <input type="text" class="form-control" id="nombre_inst_empresa" name="nombre_inst_empresa" value="<?php echo $exp_lab['nombre_inst_empresa'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="unidad_admin_area">Unidad Administrativa/Área</label>
                        <input type="text" class="form-control" id="unidad_admin_area" name="unidad_admin_area" value="<?php echo $exp_lab['unidad_admin_area']?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="puesto_cargo">Puesto o cargo desempeñado</label>
                        <input type="text" class="form-control" id="puesto_cargo" name="puesto_cargo" value="<?php echo $exp_lab['puesto_cargo']?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="ingreso">Ingreso</label>
                        <input type="date" class="form-control" name="ingreso" id="ingreso" value="<?php echo $exp_lab['ingreso']?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="egreso">Egreso</label>
                        <input type="date" class="form-control" name="egreso" id="egreso" value="<?php echo $exp_lab['egreso']?>">
                    </div>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>