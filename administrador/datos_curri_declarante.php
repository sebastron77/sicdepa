<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Datos Curriculares';
require_once('includes/load.php');
?>
<?php
page_require_level(3);
$user = current_user();
if ($user['user_level'] <= 2) {
    $all_detalles = find_all_det_estudios();
}
if ($user['user_level'] >= 3) {
    $all_detalles = find_by_id_estudios($user['id_detalle_user']);
}
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
                    <span style="font-size: 15px;">Información Curricular</span>
                </strong>
                <a href="add_datos_curri_declarante.php" class="btn btn-info pull-right">Agregar información</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;"">
                            <th style=" width: 1%;">#</th>
                            <th style="width: 10%;" class="text-center">Nombre Completo</th>
                            <th style="width: 5%;" class="text-center">Escolaridad</th>
                            <th style="width: 5%;" class="text-center">Estatus</th>
                            <th style="width: 5%;" class="text-center">Documento Obtenido</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_detalles as $a_detalle) : ?>
                            <tr>
                                <td><?php echo remove_junk(ucwords($a_detalle['id_rel_detalle_estudios'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_detalle['nombre'] . ' ' . $a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                                <td><?php echo remove_junk($a_detalle['escolaridad']) ?></td>
                                <td><?php echo remove_junk($a_detalle['estatus_est']) ?></td>
                                <td><?php echo remove_junk($a_detalle['doc_obt']) ?></td>
                                <!-- <td class="text-center">
                                    <?php if ($a_detalle['estatus_detalle'] == '1') : ?>
                                        <span class="label label-success"><?php echo "Activo"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                                    <?php endif; ?>
                                </td> -->
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="edit_datos_curri_declarante.php?id=<?php echo (int)$a_detalle['id_rel_detalle_estudios']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                            <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>