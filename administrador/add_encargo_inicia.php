<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Encargo Inicia';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$areas = find_all('area');
$ent_feds = find_all('cat_entidad_fed');
$municipios = find_all('cat_municipios');
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
if (isset($_POST['add_encargo_inicia'])) {

    if (empty($errors)) {
        // $ninguno = remove_junk($db->escape($_POST['ninguno']));
        $dependencia_entidad = remove_junk($db->escape($_POST['dependencia_entidad']));
        $nombre_emp_car_com = remove_junk($db->escape($_POST['nombre_emp_car_com']));
        $honorarios = remove_junk($db->escape($_POST['honorarios']));
        $no_hono_niv_encargo = remove_junk($db->escape($_POST['no_hono_niv_encargo']));
        $id_area_adscripcion = remove_junk($db->escape($_POST['id_area_adscripcion']));
        $fecha_toma_pos_enc = remove_junk($db->escape($_POST['fecha_toma_pos_enc']));
        $lugar_ubica = remove_junk($db->escape($_POST['lugar_ubica']));
        $si_extranjero_pais = remove_junk($db->escape($_POST['si_extranjero_pais']));
        $localidad_colonia = remove_junk($db->escape($_POST['localidad_colonia']));
        $id_cat_ent_fed = remove_junk($db->escape($_POST['id_cat_ent_fed']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $cod_post = remove_junk($db->escape($_POST['cod_post']));
        $tel_oficina = remove_junk($db->escape($_POST['tel_oficina']));
        $extension = remove_junk($db->escape($_POST['extension']));
        // Convertimos el array de opciones en una cadena separada por comas
        $id_cat_func_realiza = implode(",", $_POST['id_cat_func_realiza']);
        $otro = remove_junk($db->escape($_POST['otro']));

        $query = "INSERT INTO encargo_ini_mod_conc (";
        $query .= "id_detalle_usuario, dependencia_entidad, nombre_emp_car_com, honorarios, no_hono_niv_encargo, id_area_adscripcion, fecha_toma_pos_enc, 
                        lugar_ubica, si_extranjero_pais, localidad_colonia, id_cat_ent_fed, id_cat_mun, cod_post, tel_oficina, extension, id_cat_func_realiza,
                        otro, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$id_detalle_usuario}', '{$dependencia_entidad}', '{$nombre_emp_car_com}', '{$honorarios}', '{$no_hono_niv_encargo}', 
                        '{$id_area_adscripcion}', '{$fecha_toma_pos_enc}', '{$lugar_ubica}', '{$si_extranjero_pais}', '{$localidad_colonia}', 
                        '{$id_cat_ent_fed}', '{$id_cat_mun}', '{$cod_post}', '{$tel_oficina}', '{$extension}', '{$id_cat_func_realiza}', '{$otro}', 
                        NOW()";
        $query .= ")";

        if ($db->query($query)) {
            $session->msg('s', "La información del encargo que inicia ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó el encargo que inicia: ' . '.', 1);
            redirect('add_encargo_inicia.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar el encargo que inicia.');
            redirect('add_encargo_inicia.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_encargo_inicia.php', false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="modal">
    <div class="modal-content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group clearfix">
                </div>
            </div>
            <div class="panel-body">
                <strong>
                    <span class="material-symbols-outlined" style="margin-top: -50px; color: #3a3d44; font-size: 25px;">
                        description
                    </span>
                    <p style="margin-top: -53px; margin-left: 32px; font-size: 20px;">Datos del Encargo que Inicia</p>
                </strong>
                <form method="post" action="add_encargo_inicia.php">
                    <div id="inputsContainer" style="display:block;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dependencia_entidad">Dependencia o Entidad</label>
                                    <input type="text" class="form-control" id="unidad_admin_area" name="dependencia_entidad">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nombre_emp_car_com">Nombre del empleo, cargo o comisión</label>
                                    <input type="text" class="form-control" id="nombre_emp_car_com" name="nombre_emp_car_com">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="honorarios">¿Está contratado por honorarios?</label>
                                    <select class="form-control" name="honorarios" id="honorarios">
                                        <option value="">Escoge una opción</option>
                                        <option value="0">No</option>
                                        <option value="1">Sí</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_hono_niv_encargo">Si la respuesta es negativa, anota el NIVEL DE ENCARGO</label>
                                    <input type="text" class="form-control" id="no_hono_niv_encargo" name="no_hono_niv_encargo">
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
                                            <option value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha_toma_pos_enc">Fecha de toma posesión del encargo</label>
                                    <input type="date" class="form-control" name="fecha_toma_pos_enc" id="fecha_toma_pos_enc">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="lugar_ubica">Lugar donde se ubica</label>
                                    <select class="form-control" name="lugar_ubica" id="lugar_ubica">
                                        <option value="">Escoge una opción</option>
                                        <option value="México">México</option>
                                        <option value="Extranjero">Extranjero</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="si_extranjero_pais">Si la respuesta es Extranjero anota el PAÍS, ESTADO O PROVINCIA Y CIUDAD</label>
                                    <input type="text" class="form-control" id="si_extranjero_pais" name="si_extranjero_pais">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="localidad_colonia">Localidad o colonia</label>
                                    <input type="text" class="form-control" id="localidad_colonia" name="localidad_colonia">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_cat_ent_fed">Entidad Federativa</label>
                                    <select class="form-control" name="id_cat_ent_fed">
                                        <option value="">Escoge una opción</option>
                                        <?php foreach ($ent_feds as $ent) : ?>
                                            <option value="<?php echo $ent['id_cat_ent_fed']; ?>"><?php echo ucwords($ent['descripcion']); ?></option>
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
                                            <option value="<?php echo $mun['id_cat_mun']; ?>"><?php echo ucwords($mun['descripcion']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="cod_post">Código Postal</label>
                                    <input type="text" class="form-control" id="cod_post" name="cod_post">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tel_oficina">Teléfono de oficina</label>
                                    <input type="text" class="form-control" id="tel_oficina" name="tel_oficina">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="extension">Extensión</label>
                                    <input type="text" class="form-control" id="extension" name="extension">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label for="id_cat_func_realiza">Marca la(s) función(es) principal(es) que realiza según el siguiente catálogo</label>
                            <div class="col-md-5">
                                <input type="checkbox" name="id_cat_func_realiza[]" value="1"> Administración de Bienes Materiales<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="2"> Áreas Técnicas<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="3"> Atención Directa al Público<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="4"> Auditorias<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="5"> Calificación o Determinación para la Expedición de Licencias, Permisos o Concesiones<br>
                            </div>
                            <div class="col-md-3">
                                <input type="checkbox" name="id_cat_func_realiza[]" value="6"> Cuerpo de Seguridad<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="7"> Funciones de Inspección<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="8"> Funciones de Vigilancia<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="9"> Interventorías<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="10"> Investigación de Delitos<br>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" name="id_cat_func_realiza[]" value="11"> Labor de Supervisión<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="12"> Licitación y Adjudicación de Contratos de Bienes y Servicios<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="13"> Manejo de Recursos Financieros<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="14"> Manejo de Recursos Humanos<br>
                                <input type="checkbox" name="id_cat_func_realiza[]" value="15"> Otro<br>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="otro">Si escogió "Otro", especifique la función</label>
                                    <textarea class="form-control" name="otro" id="otro" cols="20" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div><br>

                    <a href="encargo_inicia.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_encargo_inicia" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>