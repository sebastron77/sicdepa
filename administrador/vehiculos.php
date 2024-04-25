<?php
// error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Vehículos';
require_once('includes/load.php');
?>
<?php
$user = current_user();
if ($user['user_level'] <= 2) {
    $all_detalles = find_all_vehiculos();
}
if ($user['user_level'] >= 3) {
    $all_detalles2 = find_by_id_vehiculos($user['id_detalle_user']);
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
                    <span style="font-size: 15px;">Vehículos automotores, aeronaves y embarcaciones del declarante, cónyuge, concubina o concubinario y/o dependientes económicos</span>
                </strong>
                <a href="add_vehiculos.php" class="btn btn-info pull-right">Agregar información</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;"">
                            <th style=" width: 1%;" class="text-center">#</th>
                            <th style=" width: 15%;" class="text-center">Declarante</th>
                            <th style="width: 8%;" class="text-center">Tipo Operación</th>
                            <th style="width: 10%;" class="text-center">Titular</th>
                            <th style="width: 10%;" class="text-center">Tipo</th>
                            <th style="width: 10%;" class="text-center">Marca</th>
                            <th style="width: 5%;" class="text-center">Fecha Adquisición</th>
                            <th style="width: 1%;" class="text-center">Fecha Creación</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($user['user_level'] >= 3) : ?>
                            <?php foreach ($all_detalles2 as $a_detalle2) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle2['id_rel_detalle_automotores'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle2['nombre'] . ' ' . $a_detalle2['apellido_paterno'] . ' ' . $a_detalle2['apellido_materno'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle2['tipo_operacion'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle2['titular'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle2['tipo'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle2['marca'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle2['fecha_adquisicion'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle2['fecha_creacion'])) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($a_detalle2['ninguno'] == 0) : ?>
                                                <a href="edit_vehiculos.php?id=<?php echo (int)$a_detalle2['id_rel_detalle_automotores']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                    <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($user['user_level'] <= 2) : ?>
                            <?php foreach ($all_detalles as $a_detalle) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['id_rel_detalle_automotores'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['nombre'] . ' ' . $a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['tipo_operacion'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['titular'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['tipo'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['marca'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['fecha_adquisicion'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['fecha_creacion'])) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($a_detalle['ninguno'] == 0) : ?>
                                                <a href="edit_vehiculos.php?id=<?php echo (int)$a_detalle['id_rel_detalle_automotores']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                    <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
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