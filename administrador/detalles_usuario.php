<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Datos Declarantes';
require_once('includes/load.php');
?>
<?php
$all_detalles = find_all_trabajadores();
$user = current_user();
$nivel = $user['user_level'];
$id_usuario = $user['id'];
$id_user = $user['id'];
$nivel_user = $user['user_level'];
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
          <?php if ($nivel_user <= 2) : ?>
            <span>Lista de Declarantes de la CEDH</span>
          <?php endif ?>
          <?php if ($nivel_user >= 3) : ?>
            <span>Información del Declarante</span>
          <?php endif ?>
        </strong>
        <?php if ($otro == 1 || $nivel_user == 1) : ?>
          <a href="add_detalle_usuario.php" class="btn btn-info pull-right">Agregar trabajador</a>
        <?php endif ?>
      </div>

      <div class="panel-body">
        <table class="datatable table table-bordered table-striped">
          <thead class="thead-purple">
            <tr style="height: 10px;"">
              <th style=" width: 1%;" class="text-center">#</th>
              <th style="width: 8%;" class="text-center">Nombre(s)</th>
              <th style="width: 8%;" class="text-center">Apellidos</th>
              <th style="width: 10%;" class="text-center">Correo Personal</th>
              <th style="width: 10%;" class="text-center">Tel. Particular</th>
              <th style="width: 1%;" class="text-center">Estatus</th>
              <th style="width: 1%;" class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_detalles as $a_detalle) : ?>
              <tr>
                <?php if ($a_detalle['detalleID'] == $user['id_detalle_user'] && $nivel >= 3) : ?>
                  <td><?php echo remove_junk(ucwords($a_detalle['detalleID'])) ?></td>
                  <td><?php echo remove_junk(ucwords($a_detalle['nombre'])) ?></td>
                  <td><?php echo remove_junk(ucwords($a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                  <td><?php echo remove_junk($a_detalle['correo_laboral']) ?></td>
                  <td><?php echo remove_junk($a_detalle['tel_part']) ?></td>
                  <td class="text-center">
                    <?php if ($a_detalle['estatus_detalle'] === '1') : ?>
                      <span class="label label-success"><?php echo "Activo"; ?></span>
                    <?php else : ?>
                      <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                    <?php endif; ?>
                  </td>
                  <?php if ($otro <= 3 || $nivel_user <= 3) : ?>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="ver_info_detalle.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información" style="height: 32px; width: 32px;">
                          <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 2px; margin-left: -3px;">visibility</span>
                        </a>
                        <a href="edit_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                          <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                        </a>
                        <?php if ($nivel == 1) : ?>
                          <?php if ($a_detalle['estatus_detalle'] == 0) : ?>
                            <a href="activate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                              <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 2px; margin-left: -3px;">check</span>
                            </a>
                          <?php else : ?>
                            <a href="inactivate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" title="Inactivar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                              <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 2px; margin-left: -3px;">block</span>
                            </a>
                          <?php endif; ?>
                        <?php endif; ?>
                      </div>
                    </td>
                  <?php endif; ?>
                <?php endif; ?>
                <?php if ($nivel <= 2) : ?>
                  <td><?php echo remove_junk(ucwords($a_detalle['detalleID'])) ?></td>
                  <td><?php echo remove_junk(ucwords($a_detalle['nombre'])) ?></td>
                  <td><?php echo remove_junk(ucwords($a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                  <td><?php echo remove_junk($a_detalle['correo_laboral']) ?></td>
                  <td><?php echo remove_junk($a_detalle['tel_part']) ?></td>
                  <td class="text-center">
                    <?php if ($a_detalle['estatus_detalle'] === '1') : ?>
                      <span class="label label-success"><?php echo "Activo"; ?></span>
                    <?php else : ?>
                      <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                    <?php endif; ?>
                  </td>
                  <?php if ($otro <= 3 || $nivel_user <= 3) : ?>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="ver_info_detalle.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información" style="height: 32px; width: 32px;">
                          <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 2px; margin-left: -3px;">visibility</span>
                        </a>
                        <a href="edit_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                          <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                        </a>
                        <?php if ($nivel == 1) : ?>
                          <?php if ($a_detalle['estatus_detalle'] == 0) : ?>
                            <a href="activate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                              <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 2px; margin-left: -3px;">check</span>
                            </a>
                          <?php else : ?>
                            <a href="inactivate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" title="Inactivar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                              <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 2px; margin-left: -3px;">block</span>
                            </a>
                          <?php endif; ?>
                        <?php endif; ?>
                      </div>
                    </td>
                  <?php endif; ?>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>