<?php
$page_title = 'Editar Datos del Declarante';
require_once('includes/load.php');

?>
<?php
$e_detalle = find_by_id('detalles_usuario', (int)$_GET['id'], 'id_det_usuario');
if (!$e_detalle) {
    $session->msg("d", "Id del declarante no encontrado.");
    redirect('detalles_usuario.php');
}
$user = current_user();
$nivel = $user['user_level'];
$cat_municipios = find_all_cat_municipios();
$cat_est_civ = find_all('cat_estado_civil');
$cat_regimen_matrimonial = find_all('cat_regimen_matrimonial');
$cat_nacionalidad = find_all('cat_nacionalidades');
$cat_entidad_fed = find_all('cat_entidad_fed');
$id_rel_declaracion = find_by_id('rel_declaracion', (int)$_GET['id'], 'id_detalle_usuario');
page_require_level(3);
?>

<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$e_detalle['id_det_usuario'];
        $nombre = remove_junk($db->escape($_POST['nombre']));
        $apellido_paterno = remove_junk($db->escape($_POST['apellido_paterno']));
        $apellido_materno = remove_junk($db->escape($_POST['apellido_materno']));
        $curp = $db->escape($_POST['curp']);
        $rfc = $db->escape($_POST['rfc']);
        $correo_laboral = remove_junk($db->escape($_POST['correo_laboral']));
        $correo_personal = remove_junk($db->escape($_POST['correo_personal']));
        $id_cat_estado_civil = remove_junk($db->escape($_POST['id_cat_estado_civil']));
        $id_cat_regimen_matrimonial = remove_junk($db->escape($_POST['id_cat_regimen_matrimonial']));
        $pais_nac = remove_junk($db->escape($_POST['pais_nac']));
        $nacionalidad = remove_junk($db->escape($_POST['nacionalidad']));
        $entidad_nac = remove_junk($db->escape($_POST['entidad_nac']));
        $telefono = $db->escape($_POST['telefono']);
        $lugar_ubica_dom = remove_junk($db->escape($_POST['lugar_ubica_dom']));
        $calle_num = $db->escape($_POST['calle_num']);
        $colonia = $db->escape($_POST['colonia']);
        $municipio = $db->escape($_POST['municipio']);
        $tel_part = remove_junk($db->escape($_POST['tel_part']));
        $entidad_resid = remove_junk($db->escape($_POST['entidad_resid']));
        $cod_post = remove_junk($db->escape($_POST['cod_post']));

        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        $sql = "UPDATE detalles_usuario SET id_rel_declaracion='{$declaracion}', nombre='{$nombre}', apellido_paterno='{$apellido_paterno}',
                apellido_materno='{$apellido_materno}', curp='{$curp}', rfc='{$rfc}', correo_laboral='{$correo_laboral}', correo_personal='{$correo_personal}', id_cat_estado_civil='{$id_cat_estado_civil}', id_cat_regimen_matrimonial='{$id_cat_regimen_matrimonial}', pais_nac='{$pais_nac}', 
                nacionalidad='{$nacionalidad}', entidad_nac='{$entidad_nac}', telefono='{$telefono}', lugar_ubica_dom='{$lugar_ubica_dom}', 
                calle_num='{$calle_num}', colonia='{$colonia}', municipio='{$municipio}', tel_part='{$tel_part}', entidad_resid='{$entidad_resid}', 
                cod_post='{$cod_post}'
                WHERE id_det_usuario='{$db->escape($id)}'";
        $result = $db->query($sql);

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);

        if (($result && $db->affected_rows() === 1) && ($result2 && $db->affected_rows() === 1)) {
            $session->msg('s', "Los datos generales del declarante han sido actualizados. Continúa con tu información Curricular");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó al trabajador(a): ' . $nombre . ' ' . $apellidos . '.', 2);
            // updateLastArchivo('edit_detalle_usuario.php', $declaracion);
            redirect('datos_curri_declarante.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<a href="detalles_usuario.php" style="margin-top: -10px; margin-bottom: 10px;" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
    Regresar
</a>
<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="material-symbols-outlined" style="color: #3a3d44">
                        contacts
                    </span>
                    <p style="margin-top: -30px; margin-left: 25px;">Actualizar datos generales del declarante</p>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_detalle_usuario.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" class="clearfix">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre" class="control-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo ($e_detalle['nombre']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="apellido_paterno" class="control-label">Primer Apellido</label>
                                <input type="text" class="form-control" name="apellido_paterno" value="<?php echo ($e_detalle['apellido_paterno']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="apellido_materno" class="control-label">Segundo Apellido</label>
                                <input type="text" class="form-control" name="apellido_materno" value="<?php echo ($e_detalle['apellido_materno']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="curp">CURP</label>
                                <input type="text" class="form-control" name="curp" value="<?php echo ($e_detalle['curp']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rfc">RFC/Homoclave</label>
                                <input type="text" class="form-control" name="rfc" value="<?php echo ($e_detalle['rfc']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="correo_laboral">Correo Electrónico Laboral</label>
                                <input type="text" class="form-control" name="correo_laboral" value="<?php echo ($e_detalle['correo_laboral']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="correo_personal">Correo Electrónico Personal</label>
                                <input type="text" class="form-control" name="correo_personal" value="<?php echo ($e_detalle['correo_personal']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_estado_civil">Estado Civil</label>
                                <select class="form-control" name="id_cat_estado_civil">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_est_civ as $est_civ) : ?>
                                        <option <?php if ($est_civ['id_cat_estado_civil'] === $e_detalle['id_cat_estado_civil'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $est_civ['id_cat_estado_civil']; ?>">
                                            <?php echo ucwords($est_civ['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_regimen_matrimonial">Régimen Matrimonial</label>
                                <select class="form-control" name="id_cat_regimen_matrimonial">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_regimen_matrimonial as $cat_reg_mat) : ?>
                                        <option <?php if ($cat_reg_mat['id_cat_regimen_matrimonial'] === $e_detalle['id_cat_regimen_matrimonial'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $cat_reg_mat['id_cat_regimen_matrimonial']; ?>">
                                            <?php echo ucwords($cat_reg_mat['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="pais_nac">País donde nació</label>
                                <input type="text" class="form-control" name="pais_nac" value="<?php echo ($e_detalle['pais_nac']); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nacionalidad">Nacionalidad</label>
                                <select class="form-control" name="nacionalidad">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_nacionalidad as $cat_nac) : ?>
                                        <option <?php if ($cat_nac['id_cat_nacionalidad'] === $e_detalle['nacionalidad'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $cat_nac['id_cat_nacionalidad']; ?>">
                                            <?php echo ucwords($cat_nac['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="entidad_nac">Entidad donde nació</label>
                                <select class="form-control" name="entidad_nac">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_entidad_fed as $ent_fed) : ?>
                                        <option <?php if ($ent_fed['id_cat_ent_fed'] === $e_detalle['entidad_nac'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $ent_fed['id_cat_ent_fed']; ?>">
                                            <?php echo ucwords($ent_fed['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" name="telefono" value="<?php echo ($e_detalle['telefono']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <span class="material-symbols-outlined" style="margin-top: 20px; color: #3a3d44">
                                house
                            </span>
                            <p style="margin-top: -31px; margin-left: 30px; font-weight: bold; font-size: 17px;">Domicilio</p>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lugar_ubica_dom">Nacionalidad</label>
                                <select class="form-control" name="lugar_ubica_dom">
                                    <option value="">Escoge una opción</option>
                                    <option value="México" <?php if ($e_detalle['lugar_ubica_dom'] === 'México') echo 'selected="selected"'; ?>>México</option>
                                    <option value="Extranjero" <?php if ($e_detalle['lugar_ubica_dom'] === 'Extranjero') echo 'selected="selected"'; ?>>Extranjero</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="calle_num">Calle y Número Ext. e Int.</label>
                                <input type="text" class="form-control" name="calle_num" value="<?php echo ($e_detalle['calle_num']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="colonia">Colonia</label>
                                <input type="text" class="form-control" name="colonia" value="<?php echo ($e_detalle['colonia']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="entidad_resid">Entidad Federativa</label>
                                <select class="form-control" name="entidad_resid">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_entidad_fed as $ent_fed) : ?>
                                        <option <?php if ($ent_fed['id_cat_ent_fed'] === $e_detalle['entidad_resid'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $ent_fed['id_cat_ent_fed']; ?>">
                                            <?php echo ucwords($ent_fed['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="municipio">Municipio o alcaldía</label>
                                <select class="form-control" name="municipio">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_municipios as $id_cat_municipio) : ?>
                                        <option <?php if ($id_cat_municipio['id_cat_mun'] === $e_detalle['municipio'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $id_cat_municipio['id_cat_mun']; ?>">
                                            <?php echo ucwords($id_cat_municipio['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cod_post">Código Postal</label>
                                <input type="text" class="form-control" name="cod_post" value="<?php echo ($e_detalle['cod_post']); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tel_part">Teléfono (particular, incluir clave lada)</label>
                                <input type="text" class="form-control" name="tel_part" value="<?php echo ($e_detalle['tel_part']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>