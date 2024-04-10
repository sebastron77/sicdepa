<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos del Cónyuge, Concubinario y/o Dependientes Económicos';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
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
if (isset($_POST['add_datos_conyuge'])) {

    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_detalle_cony_dependientes (";
            $query .= "id_detalle_usuario, ninguno, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', 1, NOW()";
            $query .= ")";
        }
        if ($ninguno != 'on') {
            $nombre2 = $_POST['nombre_completo'];
            echo $nombre2;
            $nombre_completo = $_POST['nombre_completo'];
            $parentesco = $_POST['parentesco'];
            $extranjero = $_POST['extranjero'];
            $curp = $_POST['curp'];
            $dependiente_econ = $_POST['dependiente_econ'];
            $desemp_admin_pub = $_POST['desemp_admin_pub'];
            $depen_ent_desmp_ap = $_POST['depen_ent_desemp_ap'];
            $habita_domicilio = $_POST['habita_domicilio'];
            $dom_si_no_habita = $_POST['dom_si_no_habita'];

            for ($i = 0; $i < sizeof($nombre2); $i = $i + 1) {
                $query1 = "INSERT INTO rel_detalle_cony_dependientes (";
                $query1 .= "id_detalle_usuario, ninguno, nombre_completo, parentesco, extranjero, curp, dependiente_econ, desemp_admin_pub, depen_ent_desemp_ap, 
                        habita_domicilio, dom_si_no_habita, fecha_creacion";
                $query1 .= ") VALUES (";
                $query1 .= " '{$id_detalle_usuario}', 0, '$nombre_completo[$i]', '$parentesco[$i]', '$extranjero[$i]', '$curp[$i]', 
                            '$dependiente_econ[$i]', '$desemp_admin_pub[$i]', '$depen_ent_desmp_ap[$i]', '$habita_domicilio[$i]', 
                            '$dom_si_no_habita[$i]', NOW()";
                $query1 .= ")";
                $db->query($query1);
            }
        }

        if ($db->query($query)) {
            $session->msg('s', "La información de el/los cónyuge(s), concubina y/o dependiente(s) económico(s) ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó su cony., concu., depen. econo.: ' . '.', 1);
            redirect('datos_conyuge.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('add_datos_conyuge.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_datos_conyuge.php', false);
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
            html += '	    <div class="col-md-3">';
            html += '           <div class="form-group">';
            html += '	            <label for="nombre_completo">Nombre(s), primer apellido, segundo apellido</label>';
            html += '               <input type="text" class="form-control" name="nombre_completo[]" id="nombre_completo">';
            html += '           </div>';
            html += '	    </div>';
            html += '	    <div class="col-md-3">';
            html += '           <div class="form-group">';
            html += '	            <label for="parentesco">Parentesco</label>';
            html += '               <input type="text" class="form-control" name="parentesco[]" id="parentesco">';
            html += '           </div>';
            html += '	    </div>';
            html += '	    <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '	            <label for="extranjero">¿Es ciudadano extranjero?</label>';
            html += '               <select class="form-control" name="extranjero[]" id="extranjero">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <option value="0">No</option>';
            html += '                   <option value="1">Sí</option>';
            html += '               </select>';
            html += '           </div>';
            html += '	    </div>';
            html += '	    <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '	            <label for="curp">CURP</label>';
            html += '               <input type="text" class="form-control" name="curp[]" id="curp">';
            html += '           </div>';
            html += '	    </div>';
            html += '	    <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="dependiente_econ">¿Es dependiente económico?</label>';
            html += '               <select class="form-control" name="dependiente_econ[]" id="dependiente_econ">';
            html += '                   <option value = ""> Escoge una opción </option>';
            html += '                   <option value = "0"> No </option>';
            html += '                   <option value = "1"> Sí </option>';
            html += '               </select>';
            html += '           </div>';
            html += '	    </div>';
            html += '   </div> ';
            html += '   <div class="row"> ';
            html += '       <div class="col-md-2"> ';
            html += '           <div class="form-group">';
            html += '               <label for="desemp_admin_pub"">¿Se ha desempeñado en la Administración Pública?</label>';
            html += '               <select class=" form-control" name="desemp_admin_pub[]" id="desemp_admin_pub">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <option value="0">No</option>';
            html += '                   <option value="1">Sí</option>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4">';
            html += '           <div class="form-group">';
            html += '               <label for="depen_ent_desemp_ap">En caso de contestar afirmativamente, indique la Dependencia o Entidad en la que ';
            html += '               laboró y el periodo</label>';
            html += '               <textarea class="form-control" name="depen_ent_desemp_ap[]" id="depen_ent_desemp_ap" cols="30" rows="3"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="habita_domicilio"">¿Habita en el domicilio del declarante?</label>';
            html += '               <select class=" form-control" name="habita_domicilio[]" id="habita_domicilio">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <option value="0">No</option>';
            html += '                   <option value="1">Sí</option>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4">';
            html += '           <div class="form-group">';
            html += '               <label for="dom_si_no_habita">En caso de no habitar en el domicilio del declarante, indique calle, número exterior e ';
            html += '               interior, localidad o colonia, municipio o alcaldía, código postal, entidad federativa y país</label>';
            html += '               <textarea class="form-control" name="dom_si_no_habita[]" id="dom_si_no_habita" cols="30" rows="3"></textarea>';
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

<div class="modal">
    <div class="modal-content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group clearfix">
                <?php echo display_msg($msg); ?>
                </div>
            </div>
            <div class="panel-body">
                <strong>
                    <span class="material-symbols-outlined" style="margin-top: -50px; color: #3a3d44; font-size: 25px;">
                        diversity_3
                    </span>
                    <p style="margin-top: -53px; margin-left: 32px; font-size: 20px;">Datos del Cónyuge, Concubina o Concubinario y/o Dependientes Económicos (Situación Actual)</p>
                </strong>
                <form method="post" action="add_datos_conyuge.php">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="ninguno">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Ninguno</label>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nombre_completo">Nombre(s), primer apellido, segundo apellido</label>
                                        <input type="text" class="form-control" name="nombre_completo[]" id="nombre_completo">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="parentesco">Parentesco</label>
                                        <input type="text" class="form-control" name="parentesco[]" id="parentesco">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="extranjero">¿Es ciudadano extranjero?</label>
                                        <select class="form-control" name="extranjero[]" id="extranjero">
                                            <option value="">Escoge una opción</option>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="curp">CURP</label>
                                        <input type="text" class="form-control" name="curp[]" id="curp">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="dependiente_econ">¿Es dependiente económico?</label>
                                        <select class="form-control" name="dependiente_econ[]" id="dependiente_econ">
                                            <option value="">Escoge una opción</option>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="desemp_admin_pub"">¿Se ha desempeñado en la Administración Pública?</label>
                                            <select class=" form-control" name="desemp_admin_pub[]" id="desemp_admin_pub">
                                            <option value="">Escoge una opción</option>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="depen_ent_desemp_ap">En caso de contestar afirmativamente, indique la Dependencia o Entidad en la que laboró y el periodo</label>
                                        <textarea class="form-control" name="depen_ent_desemp_ap[]" id="depen_ent_desemp_ap" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="habita_domicilio"">¿Habita en el domicilio del declarante?</label>
                                        <select class=" form-control" name="habita_domicilio[]" id="habita_domicilio">
                                            <option value="">Escoge una opción</option>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dom_si_no_habita">En caso de no habitar en el domicilio del declarante, indique calle, número exterior e interior, localidad o colonia, municipio o alcaldía, código postal, entidad federativa y país</label>
                                        <textarea class="form-control" name="dom_si_no_habita[]" id="dom_si_no_habita" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">
                        <div class="row" id="newRow">
                        </div>
                    </div>

                    <a href="datos_conyuge.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_datos_conyuge" class="btn btn-primary btn-md">Guardar</button>
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