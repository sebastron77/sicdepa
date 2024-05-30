<?php
// error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Encargo que Inicia';
require_once('includes/load.php');
?>
<?php
$user = current_user();
if ($user['user_level'] <= 2) {
    $all_detalles = find_all_encargo_ini();
}
if ($user['user_level'] >= 3) {
    $all_detalles2 = find_by_id_all_encargo_ini($user['id_detalle_user']);
    $total = find_by_id_all_encargo_ini('encargo_ini_mod_conc', $user['id_detalle_user']);
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
                    <span style="font-size: 15px;">Información del Encargo que Inicia</span>
                </strong>
                <a href="add_encargo_inicia.php" class="btn btn-info pull-right">Agregar información</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;"">
                            <th style=" width: 1%;" class="text-center">#</th>
                            <th style="width: 10%;" class="text-center">Declarante</th>
                            <th style="width: 10%;" class="text-center">Dependencia/Entidad</th>
                            <th style="width: 10%;" class="text-center">Nivel de Encargo</th>
                            <th style="width: 10%;" class="text-center">Área de Adscripción</th>
                            <th style="width: 1%;" class="text-center">Fecha Creación</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($user['user_level'] >= 3) : ?>
                            <?php foreach ($all_detalles2 as $a_detalle2) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle2['id_encargo_inicia'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle2['nombre'] . ' ' . $a_detalle2['apellido_paterno'] . ' ' . $a_detalle2['apellido_materno'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle2['dependencia_entidad']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle2['no_hono_niv_encargo']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle2['area']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle2['fecha_creacion']) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_encargo_inicia.php?id=<?php echo (int)$a_detalle2['id_encargo_inicia']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($user['user_level'] <= 2) : ?>
                            <?php foreach ($all_detalles as $a_detalle) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['id_encargo_inicia'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['nombre'] . ' ' . $a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['dependencia_entidad']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['no_hono_niv_encargo']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['area']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['fecha_creacion']) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">                                            
                                            <a href="edit_encargo_inicia.php?id=<?php echo (int)$a_detalle['id_encargo_inicia']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                            </a>
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