<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Bienes Inmuebles';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_bienes_inmuebles', (int)$_GET['id'], 'id_rel_detalle_bienes');
$operaciones = find_all('cat_tipo_operacion');
$bienes = find_all('cat_tipo_bien_inmueble');
$obras = find_all('cat_si_obra');
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
        $id = (int)$detalles['id_rel_detalle_bienes'];
        $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
        $id_cat_tipo_bien = $_POST['id_cat_tipo_bien'];
        $cat_si_obra = $_POST['cat_si_obra'];
        $superficie_terreno = $_POST['superficie_terreno'];
        $superficie_construccion = $_POST['superficie_construccion'];
        $id_cat_forma_adquisicion = $_POST['id_cat_forma_adquisicion'];
        $nombre_razon_soc = $_POST['nombre_razon_soc'];
        $id_cat_titular = $_POST['id_cat_titular'];
        $id_cat_relacion_ces_don_her = $_POST['id_cat_relacion_ces_don_her'];
        $si_otro = $_POST['si_otro'];
        $valor_inmueble = $_POST['valor_inmueble'];
        $tipo_moneda = $_POST['tipo_moneda'];
        $fecha_adquisicion = $_POST['fecha_adquisicion'];
        $datos_registro = $_POST['datos_registro'];
        $ubicacion_inmueble = $_POST['ubicacion_inmueble'];
        $si_es_obra = $_POST['si_es_obra'];
        $si_venta = $_POST['si_venta'];
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        $sql = "UPDATE rel_detalle_bienes_inmuebles SET id_rel_declaracion='{$declaracion}', id_cat_tipo_operacion='{$id_cat_tipo_operacion}', 
                id_cat_tipo_bien='{$id_cat_tipo_bien}', cat_si_obra='{$cat_si_obra}', superficie_terreno='{$superficie_terreno}', 
                superficie_construccion='{$superficie_construccion}', id_cat_forma_adquisicion='{$id_cat_forma_adquisicion}', 
                nombre_razon_soc='{$nombre_razon_soc}', id_cat_titular='{$id_cat_titular}', id_cat_relacion_ces_don_her='{$id_cat_relacion_ces_don_her}', si_otro ='{$si_otro}', valor_inmueble='{$valor_inmueble}', tipo_moneda='{$tipo_moneda}', fecha_adquisicion='{$fecha_adquisicion}', datos_registro='{$datos_registro}', ubicacion_inmueble='{$ubicacion_inmueble}', si_es_obra='{$si_es_obra}', si_venta='{$si_venta}'
                WHERE id_rel_detalle_bienes ='{$db->escape($id)}'";

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";

        $result = $db->query($sql);
        $result2 = $db->query($sql2);

        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "La información de bienes e inmuebles ha sido editada con éxito. Continúa con los vehículos.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó bien inmueble' . $detalles['id_rel_detalle_bienes'] . '.', 1);
            redirect('vehiculos.php', false);
        } else {
            $session->msg('d', ' No se pudo editar la información de bienes e inmuebles.');
            redirect('edit_bienes_inmuebles.php?id=' . (int)$detalles['id_rel_detalle_bienes'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_bienes_inmuebles.php?id=' . (int)$detalles['id_rel_detalle_bienes'], false);
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
                        location_away
                    </span>
                    <?php
                    $ano_actual = date("Y");
                    $ano_anterior = $ano_actual - 1;
                    ?>
                    <p style="margin-top: -40px; margin-left: 32px; font-size: 20px;">Editar Bienes Inmuebles del Declarante, Cónyuge, Concubina o Concubinario y/o Dependientes Económicos (Situación Actual)</p>
                </strong>
                <form method="post" action="edit_bienes_inmuebles.php?id=<?php echo (int)$detalles['id_rel_detalle_bienes']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                    <select class="form-control" id="id_cat_tipo_operacion" name="id_cat_tipo_operacion">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($operaciones as $operacion) : ?>
                                            <option <?php if ($operacion['id_cat_tipo_operacion'] === $detalles['id_cat_tipo_operacion'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $operacion['id_cat_tipo_operacion']; ?>">
                                                <?php echo ucwords($operacion['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_tipo_bien">Tipo de bien</label>
                                    <select class="form-control" id="id_cat_tipo_bien" name="id_cat_tipo_bien">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($bienes as $bien) : ?>
                                            <option <?php if ($bien['id_cat_tipo_bien'] === $detalles['id_cat_tipo_bien'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $bien['id_cat_tipo_bien']; ?>">
                                                <?php echo ucwords($bien['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cat_si_obra">Si eligió obra indicar si se trata de</label>
                                    <select class="form-control" id="cat_si_obra" name="cat_si_obra">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($obras as $obra) : ?>
                                            <option <?php if ($obra['id_cat_si_obra'] === $detalles['cat_si_obra'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $obra['id_cat_si_obra']; ?>">
                                                <?php echo ucwords($obra['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="superficie_terreno">Superficie o div. (Terreno) m2</label>
                                    <input type="text" class="form-control" name="superficie_terreno" id="superficie_terreno" value="<?php echo $detalles['superficie_terreno'] ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="superficie_construccion">Superficie o div. (Construcción) m2</label>
                                    <input type="text" class="form-control" name="superficie_construccion" id="superficie_construccion" value="<?php echo $detalles['superficie_construccion'] ?>">
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
                                    <label for="nombre_razon_soc" style="text-align: justify;">Indicar el nombre o razón social del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular y llenar los dos rubros siguientes (Para efectos de posible conflicto de interés)</label>
                                    <input class=" form-control" type="text" name="nombre_razon_soc" id="nombre_razon_soc" value="<?php echo $detalles['nombre_razon_soc'] ?>">
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
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="id_cat_relacion_ces_don_her" style="text-align: justify;">Relación del cesionario del autor de la donación o del autor de la herencia, con el titular</label>
                                    <select class=" form-control" id="id_cat_relacion_ces_don_her " name="id_cat_relacion_ces_don_her">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($cesionarios as $cesionario) : ?>
                                            <option <?php if ($cesionario['id_cat_rel_cesionario'] === $detalles['id_cat_relacion_ces_don_her'])
                                                        echo 'selected="selected"'; ?> value="<?php echo $cesionario['id_cat_rel_cesionario']; ?>">
                                                <?php echo ucwords($cesionario['descripcion']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="si_otro" style="text-align: justify;">En caso de elegir “otro” especificar la relación del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular</label>
                                    <input class="form-control" type=" text" name="si_otro" id="si_otro" value="<?php echo $detalles['si_otro'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="valor_inmueble" style="text-align: justify;">Valor del inmueble conforme a escritura pública o contrato (no actualizar a valor presente) SIN CENTAVOS</label>
                                    <input class="form-control" type=" text" name="valor_inmueble" id="valor_inmueble" value="<?php echo $detalles['valor_inmueble'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex flex-column justify-content-end">
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
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="datos_registro" style="text-align: justify;">Datos del registro público de la propiedad: folio real u otro dato que permita a la identificación del mismo</label>
                                    <input class="form-control" type="text" name="datos_registro" id="datos_registro" value="<?php echo $detalles['datos_registro'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="ubicacion_inmueble" style="text-align: justify;">Ubicación del inmueble.<p style="font-size:9px">Especificar lugar donde se ubica (México/Extranjero), calle, número exterior e interior, localidad o colonia, entidad federativa, municipio o alcaldía, código postal.</p></label>
                                    <textarea class="form-control" name="ubicacion_inmueble" id="ubicacion_inmueble" cols="30" rows="5"><?php echo $detalles['ubicacion_inmueble'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="si_es_obra" style="text-align: justify;">Si eligió OBRA deberá especificar los datos de la operación:<p style="font-size:9px">-Inversion de la obra</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Fecha de la obra</p>
                                    </label>
                                    <textarea class="form-control" name="si_es_obra" id="si_es_obra" cols="30" rows="5"><?php echo $detalles['si_es_obra'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <label for="si_venta" style="text-align: justify;">Si eligió VENTA deberá especificar los datos de la operación:<p style="font-size:9px">-Forma de operación</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>
                                        <p style="font-size:9px; margin-top:-10px;">-Fecha de operación</p>
                                    </label>
                                    <textarea class="form-control" name="si_venta" id="si_venta" cols="30" rows="5"><?php echo $detalles['si_venta'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="bienes_inmuebles.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>