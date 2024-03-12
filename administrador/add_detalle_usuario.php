<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos Generales Declarante';
require_once('includes/load.php');

$user = current_user();
$cat_municipios = find_all_cat_municipios();
$cat_est_civ = find_all('cat_estado_civil');
$cat_regimen_matrimonial = find_all('cat_regimen_matrimonial');
$cat_nacionalidad = find_all('cat_nacionalidades');
$cat_entidad_fed = find_all('cat_entidad_fed');
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_detalle_usuario'])) {

    if (empty($errors)) {
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

        $query = "INSERT INTO detalles_usuario (";
        $query .= "nombre, apellido_paterno, apellido_materno, curp, rfc, correo_laboral, correo_personal, id_cat_estado_civil, id_cat_regimen_matrimonial, 
                    pais_nac, nacionalidad, entidad_nac, telefono, lugar_ubica_dom, calle_num, colonia, municipio, tel_part, entidad_resid, cod_post,
                    estatus_detalle";
        $query .= ") VALUES (";
        $query .= " '{$nombre}', '{$apellido_paterno}', '{$apellido_materno}', '{$curp}', '{$rfc}', '{$correo_laboral}', '{$correo_personal}', 
                    '{$id_cat_estado_civil}', '{$id_cat_regimen_matrimonial}', '{$pais_nac}', '{$nacionalidad}', '{$entidad_nac}', '{$telefono}', 
                    '{$lugar_ubica_dom}', '{$calle_num}', '{$colonia}', '{$municipio}',  '{$tel_part}', '{$entidad_resid}', '{$cod_post}','1'";
        $query .= ")";

        if ($db->query($query)) {
            //sucess
            $session->msg('s', " El trabajador ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó al trabajador(a): ' . $nombre . ' ' . $apellido_paterno . ' ' .
                $apellido_materno . '.', 1);
            redirect('detalles_usuario.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el trabajador.');
            redirect('add_detalles_usuario.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_detalle_usuario.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<a href="detalles_usuario.php" style="margin-top: -10px; margin-bottom: 10px;" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
    Regresar
</a>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="material-symbols-outlined" style="color: #3a3d44">
                    contacts
                </span>
                <p style="margin-top: -30px; margin-left: 25px;">Datos Generales del Declarante</p>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_detalle_usuario.php">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nombre">Nombre(s)</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="apellido_paterno">Primer Apellido</label>
                            <input type="text" class="form-control" name="apellido_paterno" placeholder="Apellido Paterno" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="apellido_materno">Segundo Apellido</label>
                            <input type="text" class="form-control" name="apellido_materno" placeholder="Apellido Materno" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control" name="curp" placeholder="CURP" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="rfc">RFC/Homoclave</label>
                            <input type="text" class="form-control" name="rfc" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="correo_laboral">Correo Electrónico Laboral</label>
                            <input type="text" class="form-control" name="correo_laboral" placeholder="ejemplo@cedhmichoacan.org">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="correo_personal">Correo Electrónico Personal</label>
                            <input type="text" class="form-control" name="correo_personal" placeholder="ejemplo@correo.com" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_estado_civil">Estado Civil</label>
                            <select class="form-control" name="id_cat_estado_civil">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_est_civ as $est_civ) : ?>
                                    <option value="<?php echo $est_civ['id_cat_estado_civil']; ?>"><?php echo ucwords($est_civ['descripcion']); ?></option>
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
                                    <option value="<?php echo $cat_reg_mat['id_cat_regimen_matrimonial']; ?>"><?php echo ucwords($cat_reg_mat['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="pais_nac">País donde nació</label>
                            <input type="text" class="form-control" name="pais_nac" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad</label>
                            <select class="form-control" name="nacionalidad">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_nacionalidad as $cat_nac) : ?>
                                    <option value="<?php echo $cat_nac['id_cat_nacionalidad']; ?>"><?php echo ucwords($cat_nac['descripcion']); ?></option>
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
                                    <option value="<?php echo $ent_fed['id_cat_ent_fed']; ?>"><?php echo ucwords($ent_fed['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" required>
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
                                <option value="México">México</option>
                                <option value="Extranjero">Extranjero</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="calle_num">Calle y Número Ext. e Int.</label>
                            <input type="text" class="form-control" name="calle_num" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" class="form-control" name="colonia" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entidad_resid">Entidad Federativa</label>
                            <select class="form-control" name="entidad_resid">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_entidad_fed as $ent_fed) : ?>
                                    <option value="<?php echo $ent_fed['id_cat_ent_fed']; ?>"><?php echo ucwords($ent_fed['descripcion']); ?></option>
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
                                    <option value="<?php echo $id_cat_municipio['id_cat_mun']; ?>"><?php echo ucwords($id_cat_municipio['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cod_post">Código Postal</label>
                            <input type="text" class="form-control" name="cod_post" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tel_part">Teléfono (particular, incluir clave lada)</label>
                            <input type="text" class="form-control" name="tel_part">
                        </div>
                    </div>
                </div>
        </div>
        <div class="form-group clearfix">
            <button type="submit" name="add_detalle_usuario" class="btn btn-primary">Guardar</button>
        </div>
        </form>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>