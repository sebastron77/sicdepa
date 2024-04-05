<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Vehículos';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_automotores', (int)$_GET['id'], 'id_rel_detalle_automotores');
$operaciones = find_all('cat_tipo_operacion');
$adquisiciones = find_all('cat_forma_adquisicion');
$titulares = find_all('cat_titular');
$cesionarios = find_all('cat_rel_cesionario');
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
        $id = (int)$detalles['id_rel_detalle_automotores'];
        $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
        $marca = $_POST['marca'];
        $tipo = $_POST['tipo'];
        $modelo = $_POST['modelo'];
        $num_serie = $_POST['num_serie'];
        $donde_registrado = $_POST['donde_registrado'];
        $ent_fed_pais = $_POST['ent_fed_pais'];
        $id_cat_forma_adquis = $_POST['id_cat_forma_adquis'];
        $nombre_cesionario = $_POST['nombre_cesionario'];
        $id_cat_rel_cesionario = $_POST['id_cat_rel_cesionario'];
        $otro_especifica = $_POST['otro_especifica'];
        $valor_momento_compra = $_POST['valor_momento_compra'];
        $tipo_moneda = $_POST['tipo_moneda'];
        $fecha_adquisicion = $_POST['fecha_adquisicion'];
        $id_cat_titular = $_POST['id_cat_titular'];
        $si_vent_forma_oper = $_POST['si_vent_forma_oper'];
        $si_sinies_tipo = $_POST['si_sinies_tipo'];

        $sql = "UPDATE rel_detalle_automotores SET id_cat_tipo_operacion='{$id_cat_tipo_operacion}', marca='{$marca}', tipo='{$tipo}', modelo='{$modelo}', 
                num_serie='{$num_serie}', donde_registrado='{$donde_registrado}', ent_fed_pais='{$ent_fed_pais}', id_cat_forma_adquis='{$id_cat_forma_adquis}', 
                nombre_cesionario='{$nombre_cesionario}', id_cat_rel_cesionario ='{$id_cat_rel_cesionario}', otro_especifica='{$otro_especifica}', 
                valor_momento_compra='{$valor_momento_compra}', tipo_moneda='{$tipo_moneda}', fecha_adquisicion='{$fecha_adquisicion}', 
                id_cat_titular='{$id_cat_titular}', si_vent_forma_oper='{$si_vent_forma_oper}', si_sinies_tipo='{$si_sinies_tipo}'
                WHERE id_rel_detalle_automotores ='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "La información del vehículo ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó vehiculo' . $detalles['id_rel_detalle_automotores'] . '.', 1);
            redirect('edit_vehiculos.php?id=' . (int)$detalles['id_rel_detalle_automotores'], false);
        } else {
            $session->msg('d', ' No se pudo editar la información del vehículo.');
            redirect('edit_vehiculos.php?id=' . (int)$detalles['id_rel_detalle_automotores'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_vehiculos.php?id=' . (int)$detalles['id_rel_detalle_automotores'], false);
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
                    <span class="material-symbols-outlined" style="margin-top: -30px; color: #3a3d44; font-size: 35px;">
                        directions_car
                    </span>
                    <p style="margin-top: -48px; margin-left: 40px; font-size: 20px;">Vehículos automotores, aeronaves y embarcaciones del declarante, cónyuge, concubina o concubinario y/o dependientes económicos (situación actual)</p>
                </strong>
                <form method="post" action="edit_vehiculos.php?id=<?php echo (int)$detalles['id_rel_detalle_automotores']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                    <select class="form-control" id="id_cat_tipo_operacion" name="id_cat_tipo_operacion">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($operaciones as $operacion) : ?>
                                            <?php $x = $operacion['id_cat_tipo_operacion'];
                                            if ($x != 2 && $x != 6 && $x != 7 && $x != 8) : ?>
                                                <option <?php if ($operacion['id_cat_tipo_operacion'] === $detalles['id_cat_tipo_operacion'])
                                                            echo 'selected="selected"'; ?> value="<?php echo $operacion['id_cat_tipo_operacion']; ?>">
                                                    <?php echo ucwords($operacion['descripcion']) ?>
                                                <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="marca">Marca</label>
                                    <input class="form-control" type="text" name="marca" id="marca" value="<?php echo $detalles['marca'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <input class="form-control" type="text" name="tipo" id="tipo" value="<?php echo $detalles['tipo'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="modelo">Modelo</label>
                                    <input class="form-control" type="text" name="modelo" id="modelo" value="<?php echo $detalles['modelo'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="num_serie">Número de serie</label>
                                    <input class="form-control" type="text" name="num_serie" id="num_serie" value="<?php echo $detalles['num_serie'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="donde_registrado">¿Dónde se encuentra registrado?</label>
                                    <select class="form-control" id="donde_registrado" name="donde_registrado">
                                        <option value="">Escoge una opción</option>
                                        <option <?php if ($detalles['donde_registrado'] == 'México') echo 'selected="selected"'; ?> value="México">
                                            México
                                        </option>
                                        <option <?php if ($detalles['donde_registrado'] == 'Extranjero') echo 'selected="selected"'; ?> value="Extranjero">
                                            Extranjero
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="ent_fed_pais">Entidad Federativa<label style="font-size: 9px;">(Si es en México indique el estado, si es en el extranjero indique el país)</label></label>
                                    <input class="form-control" type="text" name="ent_fed_pais" id="ent_fed_pais" value="<?php echo $detalles['ent_fed_pais'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_forma_adquis">Forma de adquisición</label>
                                    <select class="form-control" id="id_cat_forma_adquis" name="id_cat_forma_adquis">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($adquisiciones as $adquisicion) : ?>
                                            <option <?php if ($adquisicion['id_cat_forma_adquisicion'] === $detalles['id_cat_forma_adquis'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $adquisicion['id_cat_forma_adquisicion']; ?>">
                                                <?php echo ucwords($adquisicion['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="nombre_cesionario" style="text-align: justify;">Indicar el nombre o razón social del cesionario, del autor de la donación o del autor de la herencia con el titular y llenar los dos rubros siguientes (Para efectos de posible conflicto de interés)</label>
                                    <input class=" form-control" type="text" name="nombre_cesionario" value="<?php echo $detalles['nombre_cesionario'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex flex-column justify-content-end">
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
                        </div>
                        <div class="row">
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="otro_especifica" style="text-align: justify;">En caso de elegir “otro” especificar la relación del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular</label>
                                    <input class="form-control" type=" text" name="otro_especifica" id="otro_especifica" value="<?php echo $detalles['otro_especifica'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="valor_momento_compra" style="text-align: justify;">Valor del vehículo al momento de la adquisición SIN CENTAVOS</label>
                                    <input class="form-control" type=" text" name="valor_momento_compra" id="valor_momento_compra" value="<?php echo $detalles['valor_momento_compra'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="tipo_moneda" style="text-align: justify;">Tipo de moneda (especificar)</label>
                                    <input class="form-control" type=" text" name="tipo_moneda" id="tipo_moneda" value="<?php echo $detalles['tipo_moneda'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="fecha_adquisicion" style="text-align: justify;">Fecha de adquisición</label>
                                    <input class="form-control" type="date" name="fecha_adquisicion" id="fecha_adquisicion" value="<?php echo $detalles['fecha_adquisicion'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
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
                        </div>
                        <div class="row">
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="si_vent_forma_oper" style="text-align: justify;">Si eligió VENTA deberá especificar los datos de la operación:<p style="font-size:9px">-Forma de operación</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Fecha de operación</p>
                                    </label>
                                    <textarea class="form-control" name="si_vent_forma_oper" id="si_vent_forma_oper" cols="30" rows="5"><?php echo $detalles['si_vent_forma_oper'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="si_sinies_tipo" style="text-align: justify;">Si eligió SINIESTRO deberá especificar los datos de la operación:<p style="font-size:9px">-Tipo de siniestro</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Aseguradora</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Fecha del siniestro</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>
                                    </label>
                                    <textarea class="form-control" name="si_sinies_tipo" id="si_sinies_tipo" cols="30" rows="5"><?php echo $detalles['si_sinies_tipo'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="vehiculos.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>