<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Bienes Muebles';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_bien_mueble', (int)$_GET['id'], 'id_rel_detalle_bien_mueble');
$operaciones = find_all('cat_tipo_operacion');
$bienes = find_all('cat_tipo_bien_mueble');
$adquisiciones = find_all('cat_forma_adquisicion');
$titulares = find_all('cat_titular');
$cesionarios = find_all('cat_rel_cesionario');
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
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$detalles['id_rel_detalle_bien_mueble'];
        $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
        $id_cat_tipo_bien = $_POST['id_cat_tipo_bien'];
        $descripcion_bien = $_POST['descripcion_bien'];
        $id_cat_forma_adquisicion = $_POST['id_cat_forma_adquisicion'];
        $nombre_razon_soc_ces = $_POST['nombre_razon_soc_ces'];
        $id_cat_rel_cesionario = $_POST['id_cat_rel_cesionario'];
        $otro_indica_relac = $_POST['otro_indica_relac'];
        $valor_bien = $_POST['valor_bien'];
        $tipo_moneda = $_POST['tipo_moneda'];
        $fecha_adquisicion = $_POST['fecha_adquisicion'];
        $id_cat_titular = $_POST['id_cat_titular'];
        $venta_forma_oper = $_POST['venta_forma_oper'];
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        $sql = "UPDATE rel_detalle_bien_mueble SET id_rel_declaracion='{$declaracion}', id_cat_tipo_operacion='{$id_cat_tipo_operacion}',  
                id_cat_tipo_bien='{$id_cat_tipo_bien}', descripcion_bien='{$descripcion_bien}', id_cat_forma_adquisicion='{$id_cat_forma_adquisicion}', nombre_razon_soc_ces='{$nombre_razon_soc_ces}', id_cat_rel_cesionario='{$id_cat_rel_cesionario}', otro_indica_relac ='{$otro_indica_relac}', valor_bien='{$valor_bien}', tipo_moneda='{$tipo_moneda}', fecha_adquisicion='{$fecha_adquisicion}', id_cat_titular='{$id_cat_titular}', 
                venta_forma_oper='{$venta_forma_oper}'
                WHERE id_rel_detalle_bien_mueble ='{$db->escape($id)}'";

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";

        $result = $db->query($sql);
        $result2 = $db->query($sql2);
        
        if (($result && $db->affected_rows() === 1) && ($result2 && $db->affected_rows() === 1)) {
            $session->msg('s', "La información de bienes muebles ha sido editada con éxito. Continúa con cuentas bancarias.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó bien mueble' . $detalles['id _rel_detalle_bien_mueble'] . '.', 1);
            redirect('cuentas.php', false);
        } else {
            $session->msg('d', ' No se pudo editar la información de bienes muebles.');
            redirect('edit_bienes_muebles.php?id=' . (int)$detalles['id_rel_detalle_bien_mueble'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_bienes_muebles.php?id=' . (int)$detalles['id_rel_detalle_bien_mueble'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<div class="modal">
    <div class="modal-content">
        <div class="row">
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group clearfix">
                </div>
            </div>
            <div class="panel-body">
                <strong>
                    <span class="material-symbols-outlined" style="margin-top: -40px; margin-left: -10px; color: #3a3d44; font-size: 30px;">
                        diamond
                    </span>
                    <?php
                    $ano_actual = date("Y");
                    $ano_anterior = $ano_actual - 1;
                    ?>
                    <p style="margin-top: -40px; margin-left: 32px; font-size: 20px;">Editar Bienes Muebles (Situación Actual)</p>
                </strong>
                <form method="post" action="edit_bienes_muebles.php?id=<?php echo (int)$detalles['id_rel_detalle_bien_mueble']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                    <select class="form-control" id="id_cat_tipo_operacion" name="id_cat_tipo_operacion">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($operaciones as $operacion) : ?>
                                            <?php $x = $operacion['id_cat_tipo_operacion'];
                                            if ($x != 2 && $x != 5 && $x != 6 && $x != 7 && $x != 8) : ?>
                                                <option <?php if ($operacion['id_cat_tipo_operacion'] === $detalles['id_cat_tipo_operacion'])
                                                            echo 'selected="selected"'; ?> value="<?php echo $operacion['id_cat_tipo_operacion']; ?>">
                                                    <?php echo ucwords($operacion['descripcion']) ?>
                                                <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_cat_tipo_bien">Tipo de bien</label>
                                    <select class="form-control" id="id_cat_tipo_bien" name="id_cat_tipo_bien">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($bienes as $bien) : ?>
                                            <option <?php if ($bien['id_cat_tipo_bien_mueble'] === $detalles['id_cat_tipo_bien']) echo 'selected="selected"'; ?> value="<?php echo $bien['id_cat_tipo_bien_mueble']; ?>">
                                                <?php echo ucwords($bien['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="descripcion_bien">Descripción del bien</label>
                                    <input class="form-control" type="text" name="descripcion_bien" id="descripcion_bien" value="<?php echo $detalles['descripcion_bien'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_forma_adquisicion">Forma de adquisición</label>
                                    <select class="form-control" id="id_cat_forma_adquisicion" name="id_cat_forma_adquisicion">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($adquisiciones as $adquisicion) : ?>
                                            <option <?php if ($adquisicion['id_cat_forma_adquisicion'] === $detalles['id_cat_forma_adquisicion'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $adquisicion['id_cat_forma_adquisicion']; ?>">
                                                <?php echo ucwords($adquisicion['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="nombre_razon_soc_ces" style="text-align: justify;">Indicar el nombre o razón social del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular y llenar los dos rubros siguientes (Para efectos de posible conflicto de interés)</label>
                                    <input class=" form-control" type="text" name="nombre_razon_soc_ces" id="nombre_razon_soc_ces" value="<?php echo $detalles['nombre_razon_soc_ces'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_rel_cesionario" style="text-align: justify;">Relación del cesionario del autor de la donación o del autor de la herencia, con el titular</label>
                                    <select class=" form-control" id="id_cat_rel_cesionario" name="id_cat_rel_cesionario">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($cesionarios as $cesionario) : ?>
                                            <option <?php if ($cesionario['id_cat_rel_cesionario'] === $detalles['id_cat_rel_cesionario'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $cesionario['id_cat_rel_cesionario']; ?>">
                                                <?php echo ucwords($cesionario['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="otro_indica_relac" style="text-align: justify;">En caso de elegir “otro” especificar la relación del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular</label>
                                    <input class="form-control" type=" text" name="otro_indica_relac" id="otro_indica_relac" value="<?php echo $detalles['otro_indica_relac'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="valor_bien" style="text-align: justify;">Valor del bien SIN CENTAVOS</label>
                                    <input class="form-control" type=" text" name="valor_bien" id="valor_bien" value="<?php echo $detalles['valor_bien'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipo_moneda" style="text-align: justify;">Tipo de moneda (especificar)</label>
                                    <input class="form-control" type=" text" name="tipo_moneda" id="tipo_moneda" value="<?php echo $detalles['tipo_moneda'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha_adquisicion" style="text-align: justify;">Fecha de adquisición</label>
                                    <input class="form-control" type="date" name="fecha_adquisicion" id="fecha_adquisicion" value="<?php echo $detalles['fecha_adquisicion'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_titular">Titular</label>
                                    <select class="form-control" id="id_cat_titular" name="id_cat_titular">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($titulares as $titular) : ?>
                                            <option <?php if ($titular['id_cat_titular'] === $detalles['id_cat_titular'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $titular['id_cat_titular']; ?>">
                                                <?php echo ucwords($titular['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="venta_forma_oper" style="text-align: justify;">Si eligió VENTA deberá especificar los datos de la operación:<p style="font-size:9px">-Forma de operación</p>
                                        <p style="font-size:9px; margin-top:-10px;">-En el caso de cesión, donación o herencia proporcionar nombre o razón social del nuevo propietario </p>
                                        <p style="font-size:9px; margin-top:-10px;">-Fecha de operación</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>
                                    </label>
                                    <textarea class="form-control" name="venta_forma_oper" id="venta_forma_oper" cols="50" rows="5"><?php echo ucwords($detalles['venta_forma_oper']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="bienes_muebles.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>