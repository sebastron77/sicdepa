<?php
// error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Experiencia Laboral';
require_once('includes/load.php');
?>
<?php
$user = current_user();
if ($user['user_level'] <= 2) {
    $all_detalles = find_all_exp_laboral();
}
if ($user['user_level'] >= 3) {
    $all_detalles2 = find_by_id_all_exp($user['id_detalle_user']);
    $total = find_by_detalle_tabla('rel_exp_laboral', $user['id_detalle_user']);
}
page_require_level(3);
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
                    <span style="font-size: 15px;">Información Experiencia Laboral</span>
                </strong>
                <a href="add_exp_laboral.php" class="btn btn-info pull-right">Agregar información</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;"">
                            <th style=" width: 1%;" class="text-center">#</th>
                            <th style="width: 1%;" class="text-center">Exp. Laboral</th>
                            <th style="width: 10%;" class="text-center">Nombre Completo</th>
                            <th style="width: 1%;" class="text-center">Sector</th>
                            <th style="width: 1%;" class="text-center">Poder</th>
                            <th style="width: 1%;" class="text-center">Ámbito</th>
                            <th style="width: 10%;" class="text-center">Inst./Empresa</th>
                            <th style="width: 1%;" class="text-center">Fecha Creación</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($user['user_level'] >= 3) : ?>
                            <?php foreach ($all_detalles2 as $a_detalle2) : ?>
                                <?php if ($a_detalle2['ninguno'] == 0 && $total['total'] >= 1) : ?>
                                    <tr>
                                        <td class="text-center"><?php echo remove_junk(ucwords($a_detalle2['id_rel_exp_lab'])) ?></td>
                                        <td><?php echo 'Sí' ?></td>
                                        <td><?php echo remove_junk(ucwords($a_detalle2['nombre'] . ' ' . $a_detalle2['apellido_paterno'] . ' ' . $a_detalle2['apellido_materno'])) ?></td>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['sector']) ?></td>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['poder']) ?></td>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['ambito']) ?></td>
                                        <td><?php echo remove_junk($a_detalle2['nombre_inst_empresa']) ?></td>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['fecha_creacion']) ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="edit_exp_laboral.php?id=<?php echo (int)$a_detalle2['id_rel_exp_lab']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                    <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($a_detalle2['ninguno'] == 1) : ?>
                                    <tr>
                                        <td class="text-center"><?php echo $a_detalle2['id_rel_exp_lab'] ?></td>
                                        <td><?php echo 'No' ?></td>
                                        <td><?php echo remove_junk(ucwords($a_detalle2['nombre'] . ' ' . $a_detalle2['apellido_paterno'] . ' ' . $a_detalle2['apellido_materno'])) ?></td>
                                        <td class="text-center"><?php echo '-' ?></td>
                                        <td class="text-center"><?php echo '-' ?></td>
                                        <td class="text-center"><?php echo '-' ?></td>
                                        <td class="text-center"><?php echo '-' ?></td>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['fecha_creacion']) ?></td>
                                        <td class="text-center">
                                            <!-- <div class="btn-group">
                                                <a href="edit_exp_laboral.php?id=<?php echo (int)$a_detalle2['id_rel_exp_lab']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                    <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                                </a>
                                            </div> -->
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($user['user_level'] <= 2) : ?>
                            <?php foreach ($all_detalles as $a_detalle) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['id_rel_exp_lab'])) ?></td>
                                    <?php if ($a_detalle['ninguno'] == 0) : ?>
                                        <td><?php echo 'Sí' ?></td>
                                    <?php else : ?>
                                        <td><?php echo 'No' ?></td>
                                    <?php endif; ?>
                                    <td><?php echo remove_junk(ucwords($a_detalle['nombre'] . ' ' . $a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['sector']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['poder']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['ambito']) ?></td>
                                    <td><?php echo remove_junk($a_detalle['nombre_inst_empresa']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['fecha_creacion']) ?></td>
                                    <td class="text-center">
                                        <?php if($a_detalle['ninguno'] == 0): ?>
                                        <div class="btn-group">
                                            <a href="edit_exp_laboral.php?id=<?php echo (int)$a_detalle['id_rel_exp_lab']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>