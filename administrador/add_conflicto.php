<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Conflicto del declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$operaciones = find_all('cat_tipo_op_conf');
$frecs_anual = find_all('cat_frec_anual');
$tipos_pers_jur = find_all('cat_tipo_pers_jurid');
$resps_conf = find_all('cat_tipo_resp_conf');
$naturs_vinc = find_all('cat_natur_vinc');
$particips_direc = find_all('cat_particip_direc');
$tipos_colab = find_all('cat_tipo_colab');
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

    /* Estilos para el switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 34px;
        /* Bordes redondeados */
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
        /* Bordes redondeados */
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Estilos opcionales para personalizar el aspecto del texto alrededor del switch */
    .switch-container {
        display: inline-flex;
        align-items: center;
    }

    .switch-label {
        margin-right: 8px;
    }
</style>

<?php
if (isset($_POST['add_conflicto'])) {
    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];
        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_detalle_conflicto_declarante (";
            $query .= "id_detalle_usuario, id_rel_declaracion, ninguno, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', '{$declaracion}', 1, NOW()";
            $query .= ")";
        }
        if ($ninguno != 'on') {
            $de_acuerdo_publica = remove_junk($db->escape($_POST['de_acuerdo_publica']));
            if ($de_acuerdo_publica == 'on') {
                $bool = '1';
            } else {
                $bool = '0';
            }
            $ninguno = remove_junk($db->escape($_POST['ninguno']));
            if ($ninguno == 'on') {
                $nin = '1';
            } else {
                $nin = '0';
            }
            $id_cat_tipo_op_conf = $_POST['id_cat_tipo_op_conf'];
            $nombre_entidad = $_POST['nombre_entidad'];
            $id_cat_frec_anual = $_POST['id_cat_frec_anual'];
            $otra_frec_anual = $_POST['otra_frec_anual'];
            $id_cat_tipo_pers_jur = $_POST['id_cat_tipo_pers_jur'];
            $otra_pers_jurid = $_POST['otra_pers_jurid'];
            $id_cat_resp_conf = $_POST['id_cat_resp_conf'];
            $id_cat_natur_vinc = $_POST['id_cat_natur_vinc'];
            $otro_nat_vinc = $_POST['otro_nat_vinc'];
            $antiguedad_vinc_anios = $_POST['antiguedad_vinc_anios'];
            $id_cat_particip_direc = $_POST['id_cat_particip_direc'];
            $id_cat_tipo_colab = $_POST['id_cat_tipo_colab'];
            $ubicacion = $_POST['ubicacion'];
            $observaciones_aclaraciones = $_POST['observaciones_aclaraciones'];

            for ($i = 0; $i < sizeof($id_cat_tipo_op_conf); $i = $i + 1) {
                $query1 = "INSERT INTO rel_detalle_conflicto_declarante (";
                $query1 .= "id_detalle_usuario, id_rel_declaracion, ninguno, de_acuerdo_publica, id_cat_tipo_operacion, nombre_entidad, id_cat_frec_anual,
                            otra_frec_anual, id_cat_tipo_pers_jur, otra_pers_jurid, id_cat_resp_conf, id_cat_natur_vinc, otro_nat_vinc, antiguedad_vinc_anios, id_cat_particip_direc, id_cat_tipo_colab, ubicacion, observaciones_aclaraciones, fecha_creacion";
                $query1 .= ") VALUES (";
                $query1 .= "'{$id_detalle_usuario}', '{$declaracion}', '{$nin}', '{$bool}', '$id_cat_tipo_op_conf[$i]', '$nombre_entidad[$i]', 
                            '$id_cat_frec_anual[$i]', '$otra_frec_anual[$i]', '$id_cat_tipo_pers_jur[$i]', '$otra_pers_jurid[$i]', '$id_cat_resp_conf[$i]',
                            '$id_cat_natur_vinc[$i]', '$otro_nat_vinc[$i]', '$antiguedad_vinc_anios[$i]', '$id_cat_particip_direc[$i]', 
                            '$id_cat_tipo_colab[$i]', '$ubicacion[$i]', '$observaciones_aclaraciones[$i]', NOW()";
                $query1 .= ")";
                $db->query($query1);
            }
        }
        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);
        
        if (($db->query($query)) && ($result2)) {
            $session->msg('s', "La información del conflicto de interés ha sido agregada con éxito. Continúa con conflictos de intereses económicos (si los hay).");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó conflicto de interés.', 1);
            redirect('conflicto_econ.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('conflicto.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('conflicto.php', false);
    }
}
?>
<script type="text/javascript">
    $(document).ready(function() {

        $("#addRow").click(function() {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '   <div class="row" style="margin-bottom: 5px;">';
            html += '	    <div class="col-md-2">';
            html += '	        <button type="button" class="btn btn-outline-danger" id="removeRow" > ';
            html += '   	        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
            html += '			        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
            html += '			        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
            html += '		        </svg>';
            html += '  	        </button>';
            html += '	    </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_op_conf">Tipo de operación</label>';
            html += '               <select class="form-control" name="id_cat_tipo_op_conf[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($operaciones as $operacion) : ?>';
            html += '                   <option value="<?php echo $operacion['id_cat_tipo_op_conf']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-5">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Nombre de la entidad (Empresa, asociación, sindicato, etc.)</label>';
            html += '               <input class="form-control" type="text" name="nombre_entidad[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_frec_anual">Frecuencia anual</label>';
            html += '               <select class="form-control" name="id_cat_frec_anual[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($frecs_anual as $frec) : ?>';
            html += '                       <option value="<?php echo $frec['id_cat_frec_anual']; ?>"><?php echo ucwords($frec['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Otra frecuencia anual</label>';
            html += '               <input class="form-control" type="text" name="otra_frec_anual[]">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-3  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_pers_jur">Tipo de persona jurídica</label>';
            html += '               <select class="form-control" name="id_cat_tipo_pers_jur[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($tipos_pers_jur as $pers) : ?>';
            html += '                   <option value="<?php echo $pers['id_cat_tipo_pers_jurid']; ?>"><?php echo ucwords($pers['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Otro tipo de persona jurídica</label>';
            html += '               <input class="form-control" type="text" name="otra_pers_jurid[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_resp_conf">Responsable del posible conflicto de interés</label>';
            html += '               <select class="form-control" name="id_cat_resp_conf[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($resps_conf as $resp) : ?>';
            html += '                   <option value="<?php echo $resp['id_cat_tipo_resp_conf']; ?>"><?php echo ucwords($resp['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_natur_vinc">Naturaleza del vínculo</label>';
            html += '               <select class="form-control" name="id_cat_natur_vinc[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($naturs_vinc as $vinc) : ?>';
            html += '                       <option value="<?php echo $vinc['id_cat_natur_vinc']; ?>"><?php echo ucwords($vinc['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Otro tipo de vínculo</label>';
            html += '               <input class="form-control" type="text" name="otro_nat_vinc[]">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Antigüedad del vínculo (Años)</label>';
            html += '               <input class="form-control" type="text" name="antiguedad_vinc_anios[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_particip_direc">Participación en la dirección o administración</label>';
            html += '               <select class="form-control" name="id_cat_particip_direc[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($particips_direc as $part) : ?>';
            html += '                   <option value="<?php echo $part['id_cat_particip_direc']; ?>"><?php echo ucwords($part['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_colab">Tipo de colaboración o aporte</label>';
            html += '               <select class="form-control" name="id_cat_tipo_colab[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($tipos_colab as $colab) : ?>';
            html += '                   <option value="<?php echo $colab['id_cat_tipo_colab']; ?>"><?php echo ucwords($colab['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Ubicación (Ciudad o Población, Entidad Federativa y País)</label>';
            html += '               <input class="form-control" type="text" name="ubicacion[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Observaciones o aclaraciones</label>';
            html += '               <textarea class="form-control" name="observaciones_aclaraciones[]" cols="10" rows="3"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '</div> ';
            html += '<hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">';
            $('#newRow').append(html);
        });

        $(document).on('click', '#removeRow', function() {
            $(this).closest('#inputFormRow').remove();
        });
    });
</script>
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
                    <span class="material-symbols-outlined" style="margin-top: -40px; color: #3a3d44; font-size: 35px;">
                    person_alert
                    </span>
                    <p style="margin-top: -37px; margin-left: 40px; font-size: 20px;">Declaración de posible conflicto de interés</p>
                </strong>
                <form method="post" action="add_conflicto.php">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="switch-container" style="margin-top: 2px;">
                                    <label class="switch" style="margin-top: 5px;">
                                        <input type="checkbox" id="flexSwitchCheckDefault" name="ninguno">
                                        <span class="slider"></span>
                                    </label>
                                    <span class="switch-label" style="margin-left: 8px; font-size: 12px;">Ninguno</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -10px">
                        <label style="font-size: 17px;">¿Estás de acuerdo en hacer pública la información de tu posible conflicto de interés?</label>
                    </div>
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="col-md-1">
                            <div class="switch-container" style="margin-top: 2px;">
                                <span class="switch-label" style="font-size: 12px;">No</span>
                                <label class="switch" style="margin-top: 5px;">
                                    <input type="checkbox" name="de_acuerdo_publica">
                                    <span class="slider"></span>
                                </label>
                                <span class="switch-label" style="margin-left: 8px; font-size: 12px;">Sí</span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: -20px;">
                        <label style="font-size: 13px;">Puesto, cargo, comisión, actividades o poderes que actualmente tenga el declarante, su cónyuge, concubina o concubinario y/o dependientes económicos desempeñen en asociaciones, sociedades, consejos, actividades filantrópicas o de consultoría.</label>
                    </div>
                    <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 100%; opacity: 0.5; margin-bottom: 15px;">
                    <div id="inputsContainer" style="display:block; margin-bottom: 15px;">
                        <div id="inputFormRow">
                            <div class="form-group">
                                <button type="button" class="btn btn-success" id="addRow" name="addRow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
                                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_op_conf">Tipo de operación</label>
                                        <select class="form-control" name="id_cat_tipo_op_conf[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($operaciones as $operacion) : ?>
                                                <option value="<?php echo $operacion['id_cat_tipo_op_conf']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Nombre de la entidad (Empresa, asociación, sindicato, etc.)</label>
                                        <input class="form-control" type="text" name="nombre_entidad[]">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="id_cat_frec_anual">Frecuencia anual</label>
                                        <select class="form-control" name="id_cat_frec_anual[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($frecs_anual as $frec) : ?>
                                                <option value="<?php echo $frec['id_cat_frec_anual']; ?>"><?php echo ucwords($frec['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Otra frecuencia anual</label>
                                        <input class="form-control" type="text" name="otra_frec_anual[]">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_pers_jur">Tipo de persona jurídica</label>
                                        <select class="form-control" name="id_cat_tipo_pers_jur[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($tipos_pers_jur as $pers) : ?>
                                                <option value="<?php echo $pers['id_cat_tipo_pers_jurid']; ?>"><?php echo ucwords($pers['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Otro tipo de persona jurídica</label>
                                        <input class="form-control" type="text" name="otra_pers_jurid[]">
                                    </div>
                                </div>
                                <div class="col-md-2  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_resp_conf">Responsable del posible conflicto de interés</label>
                                        <select class="form-control" name="id_cat_resp_conf[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($resps_conf as $resp) : ?>
                                                <option value="<?php echo $resp['id_cat_tipo_resp_conf']; ?>"><?php echo ucwords($resp['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_natur_vinc">Naturaleza del vínculo</label>
                                        <select class="form-control" name="id_cat_natur_vinc[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($naturs_vinc as $vinc) : ?>
                                                <option value="<?php echo $vinc['id_cat_natur_vinc']; ?>"><?php echo ucwords($vinc['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Otro tipo de vínculo</label>
                                        <input class="form-control" type="text" name="otro_nat_vinc[]">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Antigüedad del vínculo (Años)</label>
                                        <input class="form-control" type="text" name="antiguedad_vinc_anios[]">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_particip_direc">Participación en la dirección o administración</label>
                                        <select class="form-control" name="id_cat_particip_direc[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($particips_direc as $part) : ?>
                                                <option value="<?php echo $part['id_cat_particip_direc']; ?>"><?php echo ucwords($part['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_colab">Tipo de colaboración o aporte</label>
                                        <select class="form-control" name="id_cat_tipo_colab[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($tipos_colab as $colab) : ?>
                                                <option value="<?php echo $colab['id_cat_tipo_colab']; ?>"><?php echo ucwords($colab['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Ubicación (Ciudad o Población, Entidad Federativa y País)</label>
                                        <input class="form-control" type="text" name="ubicacion[]">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Observaciones o aclaraciones</label>
                                        <textarea class="form-control" name="observaciones_aclaraciones[]" cols="10" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">
                            <div class="row" id="newRow">
                            </div>
                        </div>
                    </div>

                    <a href="conflicto.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_conflicto" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#flexSwitchCheckDefault").change(function() {
            if (this.checked) {
                // Si el switch está activado, ocultar los inputs
                $("#inputsContainer").hide();
            } else {
                // Si el switch está desactivado, mostrar los inputs
                $("#inputsContainer").show();
            }
        });
    });
</script>
<?php include_once('layouts/footer.php'); ?>