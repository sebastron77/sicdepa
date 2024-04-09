<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Conflicto del declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$operaciones = find_all('cat_tipo_op_conf');
$naturs_vinc = find_all('cat_natur_vinc');
$sociedades = find_all('cat_soc_part');
$resps_conf = find_all('cat_tipo_resp_conf');
$parti_direc = find_all('cat_particip_direc');
$soc_part = find_all('cat_soc_part');
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
if (isset($_POST['add_conflicto_econ'])) {
    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_detalle_conflicto_part_econom (";
            $query .= "id_detalle_usuario, ninguno, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', 1, NOW()";
            $query .= ")";
        }
        if ($ninguno != 'on') {
            $ninguno = remove_junk($db->escape($_POST['ninguno']));
            if ($ninguno == 'on') {
                $nin = '1';
            } else {
                $nin = '0';
            }

            $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
            $nombre_empresa = $_POST['nombre_empresa'];
            $insc_reg_publ = $_POST['insc_reg_publ'];
            $id_cat_tipo_soc = $_POST['id_cat_tipo_soc'];
            $otra_soc = $_POST['otra_soc'];
            $antiguedad_part_anios = $_POST['antiguedad_part_anios'];
            $id_cat_resp_conf = $_POST['id_cat_resp_conf'];
            $fecha_const_soc = $_POST['fecha_const_soc'];
            $ubicacion = $_POST['ubicacion'];
            $sector_indust = $_POST['sector_indust'];
            $tipo_particip = $_POST['tipo_particip'];
            $especi_otro_particip = $_POST['especi_otro_particip'];
            $id_cat_particip_direc = $_POST['id_cat_particip_direc'];
            $observaciones = $_POST['observaciones'];

            for ($i = 0; $i < sizeof($id_cat_tipo_operacion); $i = $i + 1) {
                $query1 = "INSERT INTO rel_detalle_conflicto_part_econom (";
                $query1 .= "id_detalle_usuario, ninguno, id_cat_tipo_operacion, nombre_empresa, insc_reg_publ, id_cat_tipo_soc, otra_soc,
                            antiguedad_part_anios, id_cat_resp_conf, fecha_const_soc, ubicacion, sector_indust, tipo_particip, especi_otro_particip,
                            id_cat_particip_direc, observaciones, fecha_creacion";
                $query1 .= ") VALUES (";
                $query1 .= "'{$id_detalle_usuario}', '{$nin}', '$id_cat_tipo_operacion[$i]', '$nombre_empresa[$i]', '$insc_reg_publ[$i]', 
                            '$id_cat_tipo_soc[$i]', '$otra_soc[$i]', '$antiguedad_part_anios[$i]', '$id_cat_resp_conf[$i]', '$fecha_const_soc[$i]', 
                            '$ubicacion[$i]', '$sector_indust[$i]', '$tipo_particip[$i]', '$especi_otro_particip[$i]', '$id_cat_particip_direc[$i]',
                            '$observaciones[$i]', NOW()";
                $query1 .= ")";
                $db->query($query1);
            }
        }
        if ($db->query($query)) {
            $session->msg('s', "La información del conflicto de interés por participaciones económicas ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó conflicto de interés econ.', 1);
            redirect('conflicto_econ.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('conflicto_econ.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('conflicto_econ.php', false);
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
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_operacion">Tipo de operación</label>';
            html += '               <select class="form-control" name="id_cat_tipo_operacion[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($operaciones as $operacion) : ?>';
            html += '                   <option value="<?php echo $operacion['id_cat_tipo_op_conf']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Nombre de la empresa o sociedad o persona física</label>';
            html += '               <input class="form-control" type="text" name="nombre_empresa[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Inscripción en el registro público u otro dato que permita su identificación (en su ';
            html += '               <input class="form-control" type="text" name="insc_reg_publ[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_soc">Tipo de sociedad en la que se participa o con la que se contrata (en su caso)</label>';
            html += '               <select class="form-control" name="id_cat_tipo_soc[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($soc_part as $part) : ?>';
            html += '                       <option value="<?php echo $part['id_cat_soc_part']; ?>"><?php echo ucwords($part['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Otro tipo de sociedad en que participa</label>';
            html += '               <input class="form-control" type="text" name="otra_soc[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Antigüedad de la participación o convenio (años)</label>';
            html += '               <input class="form-control" type="text" name="antiguedad_part_anios[]">';
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
            html += '               <label for="fecha_const_soc">Fecha de constitución de la sociedad (en su caso)</label>';
            html += '               <input class="form-control" type="date" name="fecha_const_soc[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="ubicacion">Ubicación (ciudad o población, entidad federativa y país)</label>';
            html += '               <textarea class="form-control" name="ubicacion[]" cols="30" rows="2"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Sector o industria (en su caso)</label>';
            html += '               <input class="form-control" type="text" name="sector_indust[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Tipo de participación o contrato (porcentaje de participación en el capital,';
            html += '                   partes sociales, trabajo) Especificar </label>';
            html += '               <textarea class="form-control" name="tipo_particip[]" cols="30" rows="2"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Otro tipo de participación o contrato</label>';
            html += '               <textarea class="form-control" name="especi_otro_particip[]" cols="30" rows="2"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2  d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_particip_direc">Inicio de participación o contrato</label>';
            html += '               <select class="form-control" name="id_cat_particip_direc[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($parti_direc as $part) : ?>';
            html += '                       <option value="<?php echo $part['id_cat_particip_direc']; ?>"><?php echo ucwords($part['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label style="font-size: 12px;">Observaciones o aclaraciones</label>';
            html += '               <textarea class="form-control" name="observaciones[]" cols="10" rows="3"></textarea>';
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
                <div class="row" style="margin-top: -15px;">
                    <label style="font-size: 13px;">Posibles conflictos de intereses por participaciones económicas o financieras del declarante, cónyuge, concubina, concubinario y/o dependientes económicos.</label>
                </div>
                <form method="post" action="add_conflicto_econ.php">
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
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                        <select class="form-control" name="id_cat_tipo_operacion[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($operaciones as $operacion) : ?>
                                                <option value="<?php echo $operacion['id_cat_tipo_op_conf']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Nombre de la empresa o sociedad o persona física</label>
                                        <input class="form-control" type="text" name="nombre_empresa[]">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Inscripción en el registro público u otro dato que permita su identificación (en su caso)</label>
                                        <input class="form-control" type="text" name="insc_reg_publ[]">
                                    </div>
                                </div>
                                <div class="col-md-3  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_soc">Tipo de sociedad en la que se participa o con la que se contrata (en su caso)</label>
                                        <select class="form-control" name="id_cat_tipo_soc[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($soc_part as $part) : ?>
                                                <option value="<?php echo $part['id_cat_soc_part']; ?>"><?php echo ucwords($part['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Otro tipo de sociedad en que participa</label>
                                        <input class="form-control" type="text" name="otra_soc[]">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Antigüedad de la participación o convenio (años)</label>
                                        <input class="form-control" type="text" name="antiguedad_part_anios[]">
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
                                        <label for="fecha_const_soc">Fecha de constitución de la sociedad (en su caso)</label>
                                        <input class="form-control" type="date" name="fecha_const_soc[]">
                                    </div>
                                </div>
                                <div class="col-md-3  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="ubicacion">Ubicación (ciudad o población, entidad federativa y país)</label>
                                        <textarea class="form-control" name="ubicacion[]" cols="30" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Sector o industria (en su caso)</label>
                                        <input class="form-control" type="text" name="sector_indust[]">
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Tipo de participación o contrato (porcentaje de participación en el capital,
                                            partes sociales, trabajo) Especificar </label>
                                        <textarea class="form-control" name="tipo_particip[]" cols="30" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Otro tipo de participación o contrato</label>
                                        <textarea class="form-control" name="especi_otro_particip[]" cols="30" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2  d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_particip_direc">Inicio de participación o contrato</label>
                                        <select class="form-control" name="id_cat_particip_direc[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($parti_direc as $part) : ?>
                                                <option value="<?php echo $part['id_cat_particip_direc']; ?>"><?php echo ucwords($part['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Observaciones o aclaraciones</label>
                                        <textarea class="form-control" name="observaciones[]" cols="10" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">
                            <div class="row" id="newRow">
                            </div>
                        </div>
                    </div>

                    <a href="conflicto_econ.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_conflicto_econ" class="btn btn-primary btn-md">Guardar</button>
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