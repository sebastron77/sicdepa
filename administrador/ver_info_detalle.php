<?php
$page_title = 'Datos trabajadores';
require_once('includes/load.php');
?>
<?php
header('Content-Type: text/html; charset=UTF-8');
// page_require_level(1);
$e_detalle = find_by_id_detalle((int) $_GET['id']);
$user = current_user();
$nivel = $user['user_level'];
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Información completa de
                        <?php echo remove_junk(ucwords($e_detalle['nombre'])) ?>
                        <?php echo remove_junk(ucwords($e_detalle['apellido_paterno'])) ?>
                    </span>
                </strong>
            </div>

            <div class="panel-body">

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <!-- <th style="width: 10%;">ID Trabajador</th> -->
                            <th style="width: 12%;">Nombre(s)</th>
                            <th style="width: 13%;">Primer Apellido</th>
                            <th style="width: 13%;">Segundo Apellido</th>
                            <th style="width: 10%;">CURP</th>
                            <th style="width: 10%;">RFC</th>
                            <th style="width: 10%;">Correo Laboral</th>
                            <th style="width: 10%;">Correo Personal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- <td>
                                <?php echo remove_junk($e_detalle['id_det_usuario']) ?>
                            </td> -->
                            <td>
                                <?php echo remove_junk($e_detalle['nombre']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['apellido_paterno']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['apellido_materno']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['curp']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['rfc']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['correo_laboral']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['correo_personal']) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 7%;">Estado Civil</th>
                            <th style="width: 10%;">Régimen Matrimonial</th>
                            <th style="width: 10%;">País de Nacimiento</th>
                            <th style="width: 10%;">Nacionalidad</th>
                            <th style="width: 10%;">Entidad donde nació</th>
                            <th style="width: 5%;">Teléfono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo remove_junk($e_detalle['estado_civil']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['regimen_matrimonial']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['pais_nac']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['nacionalidad']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['ent_nac']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['telefono']) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 10%;">Lugar donde se ubica el domicilio</th>
                            <th style="width: 10%;">Calle y Núm.</th>
                            <th style="width: 10%;">Colonia</th>
                            <th style="width: 10%;">Municipio</th>
                            <th style="width: 5%;">Tel. Particular</th>
                            <th style="width: 10%;">Entidad de Residencia</th>
                            <th style="width: 5%;">Código Postal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo remove_junk($e_detalle['lugar_ubica_dom']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['calle_num']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['colonia']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['mun']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['tel_part']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['ent_resid']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['cod_post']) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-9">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="edit_detalle_usuario.php?id=<?php echo (int) $e_detalle['id_det_usuario']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                            Editar
                        </a>
                        <?php if ($nivel == 1) : ?>
                            <?php if ($e_detalle['estatus_detalle'] == 1) : ?>
                                <a href="inactivate_detalle_usuario.php?id=<?php echo (int) $e_detalle['id_det_usuario']; ?>" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
                                    Inactivar
                                </a>
                            <?php endif; ?>
                            <?php if ($e_detalle['estatus_detalle'] == 0) : ?>
                                <a href="activate_detalle_usuario.php?id=<?php echo (int) $e_detalle['id_det_usuario']; ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Activar">
                                    Activar
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>