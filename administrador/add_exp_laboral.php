<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Experiencia Laboral del Declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$cat_sector = find_all('cat_sector');
$cat_poder = find_all('cat_poder');
$cat_ambito = find_all('cat_ambito');
$id_rel_declaracion = find_by_id_dec((int)$id_detalle_usuario);
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
if (isset($_POST['add_exp_laboral'])) {

    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];
        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_exp_laboral (";
            $query .= "id_detalle_usuario, id_rel_declaracion, ninguno, fecha_creacion, estatus_exp";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', '{$declaracion}', 1, NOW(), '1'";
            $query .= ")";
        }
        if ($ninguno != 'on') {
            $id_cat_sector = remove_junk($db->escape($_POST['id_cat_sector']));
            $id_cat_poder = remove_junk($db->escape($_POST['id_cat_poder']));
            $id_cat_ambito = remove_junk($db->escape($_POST['id_cat_ambito']));
            $nombre_inst_empresa = remove_junk($db->escape($_POST['nombre_inst_empresa']));
            $unidad_admin_area = remove_junk($db->escape($_POST['unidad_admin_area']));
            $puesto_cargo = remove_junk($db->escape($_POST['puesto_cargo']));
            $funcion_principal = remove_junk($db->escape($_POST['funcion_principal']));
            $ingreso = remove_junk($db->escape($_POST['ingreso']));
            $egreso = remove_junk($db->escape($_POST['egreso']));            

            $query = "INSERT INTO rel_exp_laboral (";
            $query .= "id_detalle_usuario, id_rel_declaracion, ninguno, id_cat_sector, id_cat_poder, id_cat_ambito, nombre_inst_empresa, unidad_admin_area, 
                        puesto_cargo, funcion_principal, ingreso, egreso, fecha_creacion, estatus_exp";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', '{$declaracion}', 0, '{$id_cat_sector}', '{$id_cat_poder}', '{$id_cat_ambito}', '{$nombre_inst_empresa}', 
                    '{$unidad_admin_area}', '{$puesto_cargo}', '{$funcion_principal}', '{$ingreso}', '{$egreso}', NOW(), '1'";
            $query .= ")";
        }

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);

        if (($db->query($query)) && ($result2)) {
            $session->msg('s', "La información de experiencia laboral ha sido agregada con éxito. Continúa con la información del cónyuge.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó su exp. laboral: ' . '.', 1);
            updateLastArchivo('datos_conyuge.php', $declaracion);
            redirect('datos_conyuge.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la experiencia laboral.');
            redirect('add_exp_laboral.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_exp_laboral.php', false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="modal">
    <div class="modal-content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group clearfix">
                </div>
            </div>
            <div class="panel-body">
                <strong>
                    <span class="material-symbols-outlined" style="margin-top: -50px; color: #3a3d44; font-size: 25px;">
                        work
                    </span>
                    <p style="margin-top: -53px; margin-left: 32px; font-size: 20px;">Experiencia Laboral</p>
                </strong>
                <form method="post" action="add_exp_laboral.php">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="ninguno">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Ninguno</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="inputsContainer" style="display:block;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_sector">Sector</label>
                                    <select class="form-control" id="id_cat_sector" name="id_cat_sector">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($cat_sector as $sector) : ?>
                                            <option value="<?php echo $sector['id_cat_sector']; ?>"><?php echo ucwords($sector['descripcion']); ?></option>
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
                                            <option value="<?php echo $poder['id_cat_poder']; ?>"><?php echo ucwords($poder['descripcion']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_cat_ambito">Ámbito</label>
                                    <select class="form-control" id="id_cat_ambito" name="id_cat_ambito">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($cat_ambito as $ambito) : ?>
                                            <option value="<?php echo $ambito['id_cat_ambito']; ?>"><?php echo ucwords($ambito['descripcion']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nombre_inst_empresa">Institución/Empresa/Nombre, denominación o razón social</label>
                                    <input type="text" class="form-control" id="nombre_inst_empresa" name="nombre_inst_empresa">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unidad_admin_area">Unidad Administrativa/Área</label>
                                    <input type="text" class="form-control" id="unidad_admin_area" name="unidad_admin_area">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="puesto_cargo">Puesto o cargo desempeñado</label>
                                    <input type="text" class="form-control" id="puesto_cargo" name="puesto_cargo">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="ingreso">Ingreso</label>
                                    <input type="date" class="form-control" name="ingreso" id="ingreso">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="egreso">Egreso</label>
                                    <input type="date" class="form-control" name="egreso" id="egreso">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="funcion_principal">Función Principal</label>
                                    <textarea class="form-control" id="funcion_principal" name="funcion_principal" name="" id="" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="exp_laboral.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_exp_laboral" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#flexSwitchCheckDefault").change(function() {
            if (this.checked) {
                // Si el switch está activado, ocultar los inputs
                $("#inputsContainer").hide();
            } else {
                // Si el switch está desactivado, mostrar los inputs
                $("#inputsContainer").show();
            }
        });
    });
</script>
<?php include_once('layouts/footer.php'); ?>