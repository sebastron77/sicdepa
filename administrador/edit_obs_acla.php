<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Experiencia Laboral';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$obs_acla = find_by_id('rel_declaracion', $_GET['id'], 'id_rel_declaracion');
$id_rel_declaracion = find_by_id_dec((int)$id_detalle_usuario);
page_require_level(3);
?>

<?php
if (isset($_POST['update'])) {
    if (empty($errors)) {
        $id = (int)$obs_acla['id_rel_declaracion'];
        $observaciones = remove_junk($db->escape($_POST['observaciones']));
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        $sql = "UPDATE rel_declaracion SET observaciones='{$observaciones}' WHERE id_rel_declaracion ='{$db->escape($id)}'";
        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";

        $result = $db->query($sql);
        $result2 = $db->query($sql2);

        if (($db->query($query)) && ($result2 && $db->affected_rows() === 1)) {
            //sucess
            $session->msg('s', "La información de las observaciones y aclaraciones ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó obs. acla.: ' . $id . '.', 2);
            redirect('obs_acla.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la información.');
            redirect('edit_obs_acla.php?id=' . (int)$obs_acla['id_rel_declaracion'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_obs_acla.php?id=' . (int)$obs_acla['id_rel_declaracion'], false);
    }
}
?>

<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="form-group clearfix">
    <a href="obs_acla.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
    </a>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>
            <span class="material-symbols-outlined" style="color: #3a3d44">
                work
            </span>
            <p style="margin-top: -22px; margin-left: 32px;">Observaciones y aclaraciones</p>
        </strong>
    </div>
    <div class="panel-body">
        <form method="post" action="edit_obs_acla.php?id=<?php echo (int)$obs_acla['id_rel_declaracion']; ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Observaciones y/o aclaraciones</label>
                        <textarea class="form-control" name="observaciones" cols="150" rows="10"><?php echo $obs_acla['observaciones']?></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>