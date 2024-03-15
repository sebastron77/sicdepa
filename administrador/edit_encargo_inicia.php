<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Datos del Encargo que Inicia';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalle_encargo = find_by_id('encargo_ini_mod_conc', $_GET['id'], 'id_encargo_inicia');
$areas = find_all('area');
$ent_feds = find_all('cat_entidad_fed');
$municipios = find_all('cat_municipios');
page_require_level(3);
?>

<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$detalle_encargo['id_encargo_inicia'];
        $dependencia_entidad = $_POST['dependencia_entidad'];
        $nombre_emp_car_com = $_POST['nombre_emp_car_com'];
        $honorarios = $_POST['honorarios'];
        $no_hono_niv_encargo = $_POST['no_hono_niv_encargo'];
        $id_area_adscripcion = $_POST['id_area_adscripcion'];
        $fecha_toma_pos_enc = $_POST['fecha_toma_pos_enc'];
        $lugar_ubica = $_POST['lugar_ubica'];
        $si_extranjero_pais = $_POST['si_extranjero_pais'];
        $localidad_colonia = $_POST['localidad_colonia'];
        $id_cat_ent_fed = $_POST['id_cat_ent_fed'];
        $id_cat_mun = $_POST['id_cat_mun'];
        $cod_post = $_POST['cod_post'];
        $tel_oficina = $_POST['tel_oficina'];
        $extension = $_POST['extension'];
        // $id_cat_func_realiza = $_POST['id_cat_func_realiza'];
        $otro = $_POST['otro'];

        $opciones_seleccionadas = explode(",", $detalle_encargo['id_cat_func_realiza']);
        $id_cat_func_realiza = implode(",", $_POST['id_cat_func_realiza']);

        $sql = "UPDATE encargo_ini_mod_conc SET dependencia_entidad='{$dependencia_entidad}', nombre_emp_car_com='{$nombre_emp_car_com}', honorarios='{$honorarios}', 
                no_hono_niv_encargo='{$no_hono_niv_encargo}', id_area_adscripcion='{$id_area_adscripcion}', fecha_toma_pos_enc='{$fecha_toma_pos_enc}',
                lugar_ubica='{$lugar_ubica}', si_extranjero_pais='{$si_extranjero_pais}', localidad_colonia='{$localidad_colonia}', 
                id_cat_ent_fed='{$id_cat_ent_fed}', id_cat_mun='{$id_cat_mun}', cod_post='{$cod_post}', tel_oficina='{$tel_oficina}', extension='{$extension}',
                id_cat_func_realiza='{$id_cat_func_realiza}', otro='{$otro}'
                WHERE id_encargo_inicia ='{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($db->query($query)) {
            //sucess
            $session->msg('s', "La información del Encargo que Inicia ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó encargo: ' . $id . '.', 2);
            redirect('edit_encargo_inicia.php?id=' . (int)$detalle_encargo['id_encargo_inicia'], false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la información.');
            redirect('edit_encargo_inicia.php?id=' . (int)$detalle_encargo['id_encargo_inicia'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_encargo_inicia.php?id=' . (int)$detalle_encargo['id_encargo_inicia'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="form-group clearfix">
    <a href="encargo_inicia.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
    </a>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>
            <span class="material-symbols-outlined" style="color: #3a3d44;">
                diversity_3
            </span>
            <p style="margin-top: -22px; margin-left: 32px; font-size:15;">Editar datos del Encargo que Inicia</p>
        </strong>
    </div>
    <div class="panel-body">
        <form method="post" action="edit_encargo_inicia.php?id=<?php echo (int)$detalle_encargo['id_encargo_inicia']; ?>">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dependencia_entidad">Dependencia o Entidad</label>
                        <input type="text" class="form-control" id="unidad_admin_area" name="dependencia_entidad" value="<?php echo $detalle_encargo['dependencia_entidad'] ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre_emp_car_com">Nombre del empleo, cargo o comisión</label>
                        <input type="text" class="form-control" id="nombre_emp_car_com" name="nombre_emp_car_com" value="<?php echo $detalle_encargo['nombre_emp_car_com'] ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="honorarios">¿Está contratado por honorarios?</label>
                        <select class="form-control" name="honorarios" id="honorarios">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($detalle_encargo['honorarios'] == 0) echo 'selected="selected"'; ?> value="0">
                                No
                            </option>
                            <option <?php if ($detalle_encargo['honorarios'] == 1) echo 'selected="selected"'; ?> value="1">
                                Sí
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_hono_niv_encargo">Si la respuesta es negativa, anota el NIVEL DE ENCARGO</label>
                        <input type="text" class="form-control" id="no_hono_niv_encargo" name="no_hono_niv_encargo" value="<?php echo $detalle_encargo['no_hono_niv_encargo'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="id_area_adscripcion">Área de Adscripción</label>
                        <select class="form-control" name="id_area_adscripcion">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($areas as $area) : ?>
                                <option <?php if ($area['id_area'] === $detalle_encargo['id_area_adscripcion'])
                                            echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>">
                                    <?php echo ucwords($area['nombre_area']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_toma_pos_enc">Fecha de toma posesión del encargo</label>
                        <input type="date" class="form-control" name="fecha_toma_pos_enc" id="fecha_toma_pos_enc" value="<?php echo $detalle_encargo['fecha_toma_pos_enc'] ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="lugar_ubica">Lugar donde se ubica</label>
                        <select class="form-control" name="lugar_ubica" id="lugar_ubica">
                            <option value="">Escoge una opción</option>
                            <option <?php if ($detalle_encargo['lugar_ubica'] == 'México') echo 'selected="selected"'; ?> value="México">
                                México
                            </option>
                            <option <?php if ($detalle_encargo['lugar_ubica'] == 'Extranjero') echo 'selected="selected"'; ?> value="Extranjero">
                                Extranjeros
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="si_extranjero_pais">Si la respuesta es Extranjero anota el PAÍS, ESTADO O PROVINCIA Y CIUDAD</label>
                        <input type="text" class="form-control" id="si_extranjero_pais" name="si_extranjero_pais" value="<?php echo $detalle_encargo['si_extranjero_pais'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="localidad_colonia">Localidad o colonia</label>
                        <input type="text" class="form-control" id="localidad_colonia" name="localidad_colonia" value="<?php echo $detalle_encargo['localidad_colonia'] ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id_cat_ent_fed">Entidad Federativa</label>
                        <select class="form-control" name="id_cat_ent_fed">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($ent_feds as $ent) : ?>
                                <option <?php if ($ent['id_cat_ent_fed'] === $detalle_encargo['id_cat_ent_fed'])
                                            echo 'selected="selected"'; ?> value="<?php echo $ent['id_cat_ent_fed']; ?>">
                                    <?php echo ucwords($ent['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id_cat_mun">Municipio o Alcaldía</label>
                        <select class="form-control" name="id_cat_mun">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($municipios as $mun) : ?>
                                <option <?php if ($mun['id_cat_mun'] === $detalle_encargo['id_cat_mun'])
                                            echo 'selected="selected"'; ?> value="<?php echo $mun['id_cat_ent_fed']; ?>">
                                    <?php echo ucwords($mun['descripcion']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="cod_post">Código Postal</label>
                        <input type="text" class="form-control" id="cod_post" name="cod_post" value="<?php echo $detalle_encargo['cod_post'] ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tel_oficina">Teléfono de oficina</label>
                        <input type="text" class="form-control" id="tel_oficina" name="tel_oficina" value="<?php echo $detalle_encargo['tel_oficina'] ?>">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="extension">Extensión</label>
                        <input type="text" class="form-control" id="extension" name="extension" value="<?php echo $detalle_encargo['extension'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <label for="id_cat_func_realiza">Marca la(s) función(es) principal(es) que realiza según el siguiente catálogo</label>
                <div class="col-md-5">
                    <?php $opciones_seleccionadas = explode(",", $detalle_encargo['id_cat_func_realiza']);?>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="1" <?php if (in_array("1", $opciones_seleccionadas)) echo "checked"; ?>> Administración de Bienes Materiales<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="2" <?php if (in_array("2", $opciones_seleccionadas)) echo "checked"; ?>> Áreas Técnicas<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="3" <?php if (in_array("3", $opciones_seleccionadas)) echo "checked"; ?>> Atención Directa al Público<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="4" <?php if (in_array("4", $opciones_seleccionadas)) echo "checked"; ?>> Auditorias<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="5" <?php if (in_array("5", $opciones_seleccionadas)) echo "checked"; ?>> Calificación o Determinación para la Expedición de Licencias, Permisos o Concesiones<br>
                </div>
                <div class="col-md-3">
                    <input type="checkbox" name="id_cat_func_realiza[]" value="6" <?php if (in_array("6", $opciones_seleccionadas)) echo "checked"; ?>> Cuerpo de Seguridad<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="7" <?php if (in_array("7", $opciones_seleccionadas)) echo "checked"; ?>> Funciones de Inspección<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="8" <?php if (in_array("8", $opciones_seleccionadas)) echo "checked"; ?>> Funciones de Vigilancia<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="9" <?php if (in_array("9", $opciones_seleccionadas)) echo "checked"; ?>> Interventorías<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="10" <?php if (in_array("10", $opciones_seleccionadas)) echo "checked"; ?>> Investigación de Delitos<br>
                </div>
                <div class="col-md-4">
                    <input type="checkbox" name="id_cat_func_realiza[]" value="11" <?php if (in_array("11", $opciones_seleccionadas)) echo "checked"; ?>> Labor de Supervisión<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="12" <?php if (in_array("12", $opciones_seleccionadas)) echo "checked"; ?>> Licitación y Adjudicación de Contratos de Bienes y Servicios<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="13" <?php if (in_array("13", $opciones_seleccionadas)) echo "checked"; ?>> Manejo de Recursos Financieros<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="14" <?php if (in_array("14", $opciones_seleccionadas)) echo "checked"; ?>> Manejo de Recursos Humanos<br>
                    <input type="checkbox" name="id_cat_func_realiza[]" value="15" <?php if (in_array("15", $opciones_seleccionadas)) echo "checked"; ?>> Otro<br>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="otro">Si escogió "Otro", especifique la función</label>
                        <textarea class="form-control" name="otro" id="otro" cols="20" rows="3"><?php echo $detalle_encargo['otro'] ?></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" name="update" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>