<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Datos Cónyuge';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalle_cony = find_by_id('rel_detalle_cony_dependientes', $_GET['id'], 'id_rel_detalle_cony_dependientes');
page_require_level(3);
?>

<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$detalle_cony['id_rel_detalle_cony_dependientes'];
        $nombre_completo = $_POST['nombre_completo'];
        $parentesco = $_POST['parentesco'];
        $extranjero = $_POST['extranjero'];
        $curp = $_POST['curp'];
        $dependiente_econ = $_POST['dependiente_econ'];
        $desemp_admin_pub = $_POST['desemp_admin_pub'];
        $depen_ent_desemp_ap = $_POST['depen_ent_desemp_ap'];
        $habita_domicilio = $_POST['habita_domicilio'];
        $dom_si_no_habita = $_POST['dom_si_no_habita'];

        $sql = "UPDATE rel_detalle_cony_dependientes SET nombre_completo='{$nombre_completo}', parentesco='{$parentesco}', extranjero='{$extranjero}', 
                curp='{$curp}', dependiente_econ='{$dependiente_econ}', desemp_admin_pub='{$desemp_admin_pub}', depen_ent_desemp_ap='{$depen_ent_desemp_ap}', 
                habita_domicilio='{$habita_domicilio}', dom_si_no_habita='{$dom_si_no_habita}'
                WHERE id_rel_detalle_cony_dependientes ='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($db->query($query)) {
            //sucess
            $session->msg('s', "La información del Cónyuge, Concubina o Concubinario y/o Dependientes Económicos ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó info. cony.: ' . $id . '.', 2);
            redirect('edit_datos_conyuge.php?id=' . (int)$detalle_cony['id_rel_detalle_cony_dependientes'], false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la información.');
            redirect('edit_datos_conyuge.php?id=' . (int)$detalle_cony['id_rel_detalle_cony_dependientes'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_datos_conyuge.php?id=' . (int)$detalle_cony['id_rel_detalle_cony_dependientes'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="form-group clearfix">
    <a href="datos_conyuge.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
    </a>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>
            <span class="material-symbols-outlined" style="color: #3a3d44;">
                diversity_3
            </span>
            <p style="margin-top: -22px; margin-left: 32px; font-size:15;">Editar datos del Cónyuge, Concubina o Concubinario y/o Dependientes Económicos (Situación Actual)</p>
        </strong>
    </div>
    <div class="panel-body">
        <form method="post" action="edit_datos_conyuge.php?id=<?php echo (int)$detalle_cony['id_rel_detalle_cony_dependientes']; ?>">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre_completo">Nombre(s), primer apellido, segundo apellido</label>
                        <input type="text" class="form-control" name="nombre_completo" id="nombre_completo" value="<?php echo $detalle_cony['nombre_completo'] ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="parentesco">Parentesco</label>
                        <input type="text" class="form-control" name="parentesco" id="parentesco" value="<?php echo $detalle_cony['parentesco'] ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="extranjero">¿Es ciudadano extranjero?</label>
                        <select class="form-control" name="extranjero" id="extranjero">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($detalle_cony['extranjero'] == 0) echo 'selected="selected"'; ?> value="0">
                                No
                            </option>
                            <option <?php if ($detalle_cony['extranjero'] == 1) echo 'selected="selected"'; ?> value="1">
                                Sí
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="curp">CURP</label>
                        <input type="text" class="form-control" name="curp" id="curp" value="<?php echo $detalle_cony['curp'] ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="dependiente_econ">¿Es dependiente económico?</label>
                        <select class="form-control" name="dependiente_econ" id="dependiente_econ">
                            <option value="">Escoge una opción</option>
                            <!-- <option value="0">No</option>
                            <option value="1">Sí</option> -->
                            <option <?php if ($detalle_cony['dependiente_econ'] == 0) echo 'selected="selected"'; ?> value="0">
                                No
                            </option>
                            <option <?php if ($detalle_cony['dependiente_econ'] == 1) echo 'selected="selected"'; ?> value="1">
                                Sí
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="desemp_admin_pub"">¿Se ha desempeñado en la Administración Pública?</label>
                            <select class=" form-control" name="desemp_admin_pub" id="desemp_admin_pub">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($detalle_cony['desemp_admin_pub'] == 0) echo 'selected="selected"'; ?> value="0">
                                No
                            </option>
                            <option <?php if ($detalle_cony['desemp_admin_pub'] == 1) echo 'selected="selected"'; ?> value="1">
                                Sí
                            </option>
                            </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="depen_ent_desemp_ap">En caso de contestar afirmativamente, indique la Dependencia o Entidad en la que laboró y el periodo</label>
                        <textarea class="form-control" name="depen_ent_desemp_ap" id="depen_ent_desemp_ap" cols="30" rows="3"><?php echo $detalle_cony['parentesco'] ?></textarea>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="habita_domicilio"">¿Habita en el domicilio del declarante?</label>
                            <select class=" form-control" name="habita_domicilio" id="habita_domicilio">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($detalle_cony['habita_domicilio'] == 0) echo 'selected="selected"'; ?> value="0">
                                No
                            </option>
                            <option <?php if ($detalle_cony['habita_domicilio'] == 1) echo 'selected="selected"'; ?> value="1">
                                Sí
                            </option>
                            </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dom_si_no_habita">En caso de no habitar en el domicilio del declarante, indique calle, número exterior e interior, localidad o colonia, municipio o alcaldía, código postal, entidad federativa y país</label>
                        <textarea class="form-control" name="dom_si_no_habita" id="dom_si_no_habita" cols="30" rows="3"><?php echo $detalle_cony['parentesco'] ?></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>