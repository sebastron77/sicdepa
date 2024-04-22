<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Inversiones, cuentas bancarias';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$operaciones = find_all('cat_tipo_operacion');
$titulares = find_all('cat_titular');
$inversiones = find_all('cat_tipo_inversion');
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
if (isset($_POST['add_cuentas'])) {
    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_detalle_inv_cbanc (";
            $query .= "id_detalle_usuario, id_rel_declaracion, ninguno, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', '{$declaracion}', 1, NOW()";
            $query .= ")";
        }
        if ($ninguno != 'on') {
            $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
            $id_cat_titular = $_POST['id_cat_titular'];
            $num_cuenta = $_POST['num_cuenta'];
            $mexico = $_POST['mexico'];
            $inst_razon_soc = $_POST['inst_razon_soc'];
            $extranjero = $_POST['extranjero'];
            $inst_razon_soc_ext = $_POST['inst_razon_soc_ext'];
            $pais_localiza = $_POST['pais_localiza'];
            $saldo_fecha_toma = $_POST['saldo_fecha_toma'];
            $tipo_moneda = $_POST['tipo_moneda'];
            $id_cat_tipo_inversion = $_POST['id_cat_tipo_inversion'];

            for ($i = 0; $i < sizeof($id_cat_tipo_operacion); $i = $i + 1) {
                $query1 = "INSERT INTO rel_detalle_inv_cbanc (";
                $query1 .= "id_detalle_usuario, id_rel_declaracion, ninguno, id_cat_tipo_operacion, id_cat_titular, num_cuenta, mexico, inst_razon_soc, 
                            extranjero, inst_razon_soc_ext, pais_localiza, saldo_fecha_toma, tipo_moneda, id_cat_tipo_inversion, fecha_creacion";
                $query1 .= ") VALUES (";
                $query1 .= "'{$id_detalle_usuario}', '{$declaracion}', 0, '$id_cat_tipo_operacion[$i]', '$id_cat_titular[$i]', '$num_cuenta[$i]', 
                            '$mexico[$i]', '$inst_razon_soc[$i]', '$extranjero[$i]', '$inst_razon_soc_ext[$i]', '$pais_localiza[$i]', '$saldo_fecha_toma[$i]', 
                            '$tipo_moneda[$i]', '$id_cat_tipo_inversion[$i]', NOW()";
                $query1 .= ")";
                $db->query($query1);
            }
        }
        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);

        if (($db->query($query)) && ($result2)) {
            $session->msg('s', "La información de la/las cuenta(s) bancaria(s) e inversiones ha sido agregada con éxito. Continúa con los adeudos del declarante.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó cuenta(s) banc.', 1);
            updateLastArchivo('adeudos.php', $declaracion);
            redirect('adeudos.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('cuentas.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('cuentas.php', false);
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
            html += '               <label for="id_cat_tipo_operacion">Tipo de operación</label>';
            html += '               <select class="form-control" id="id_cat_tipo_operacion" name="id_cat_tipo_operacion[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($operaciones as $operacion) : ?>';
            html += '                       <?php $x = $operacion['id_cat_tipo_operacion']; ?>';
            html += '                       <?php if ($x != 2 && $x != 5 && $x != 7 && $x != 8) : ?>';
            html += '                           <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>';
            html += '                       <?php endif; ?>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_titular">Titular</label>';
            html += '               <select class="form-control" id="id_cat_titular" name="id_cat_titular[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($titulares as $titular) : ?>';
            html += '                       <option value="<?php echo $titular['id_cat_titular']; ?>"><?php echo ucwords($titular['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="num_cuenta">Número de Cuenta O Contrato</label>';
            html += '               <input class="form-control" type="text" name="num_cuenta[]" id="num_cuenta">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-1">';
            html += '           <div class="form-group">';
            html += '               <label for="mexico">México</label>';
            html += '               <select class="form-control" name="mexico[]" id="mexico">';
            html += '                   <option value="">Opciones</option>';
            html += '                   <option value="0">No</option>';
            html += '                   <option value="1">Sí</option>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="inst_razon_soc">Institución o Razón Social</label>';
            html += '               <input class="form-control" type="text" name="inst_razon_soc[]" id="inst_razon_soc">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-1">';
            html += '           <div class="form-group">';
            html += '               <label for="extranjero">Extranjero</label>';
            html += '               <select class="form-control" name="extranjero[]" id="extranjero">';
            html += '                   <option value="">Opciones</option>';
            html += '                   <option value="0">No</option>';
            html += '                   <option value="1">Sí</option>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="inst_razon_soc_ext">Institución o Razón Social</label>';
            html += '               <input class="form-control" type="text" name="inst_razon_soc_ext[]" id="inst_razon_soc_ext">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="pais_localiza">País donde se localiza</label>';
            html += '               <input class="form-control" type="text" name="pais_localiza[]" id="pais_localiza">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="saldo_fecha_toma">Saldo a la fecha de toma o posesión del encargo que inicia SIN CENTAVOS</label>';
            html += '               <input class="form-control" type="text" name="saldo_fecha_toma[]" id="saldo_fecha_toma">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="tipo_moneda">Tipo de moneda (especificar)</label>';
            html += '               <input class="form-control" type="text" name="tipo_moneda[]" id="tipo_moneda">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_inversion">Tipo de inversión</label>';
            html += '               <select class="form-control" id="id_cat_tipo_inversion" name="id_cat_tipo_inversion[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($inversiones as $inversion) : ?>';
            html += '                       <option value="<?php echo $inversion['id_cat_tipo_inversion']; ?>"><?php echo ucwords($inversion['descripcion']) ?>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
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
                        credit_card
                    </span>
                    <p style="margin-top: -37px; margin-left: 40px; font-size: 20px;">Inversiones, cuentas bancarias y otro tipo de valores (situación actual)</p>
                </strong>
                <form method="post" action="add_cuentas.php">
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_operacion">Tipo de operación</label>
                                        <select class="form-control" id="id_cat_tipo_operacion" name="id_cat_tipo_operacion[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($operaciones as $operacion) : ?>
                                                <?php $x = $operacion['id_cat_tipo_operacion'];
                                                if ($x != 2 && $x != 5 && $x != 7 && $x != 8) : ?>
                                                    <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_titular">Titular</label>
                                        <select class="form-control" id="id_cat_titular" name="id_cat_titular[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($titulares as $titular) : ?>
                                                <option value="<?php echo $titular['id_cat_titular']; ?>"><?php echo ucwords($titular['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="num_cuenta">Número de Cuenta O Contrato</label>
                                        <input class="form-control" type="text" name="num_cuenta[]" id="num_cuenta">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="mexico">México</label>
                                        <select class="form-control" name="mexico[]" id="mexico">
                                            <option value="">Opciones</option>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inst_razon_soc">Institución o Razón Social</label>
                                        <input class="form-control" type="text" name="inst_razon_soc[]" id="inst_razon_soc">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="extranjero">Extranjero</label>
                                        <select class="form-control" name="extranjero[]" id="extranjero">
                                            <option value="">Opciones</option>
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inst_razon_soc_ext">Institución o Razón Social</label>
                                        <input class="form-control" type="text" name="inst_razon_soc_ext[]" id="inst_razon_soc_ext">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="pais_localiza">País donde se localiza</label>
                                        <input class="form-control" type="text" name="pais_localiza[]" id="pais_localiza">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="saldo_fecha_toma">Saldo a la fecha de toma o posesión del encargo que inicia SIN CENTAVOS</label>
                                        <input class="form-control" type="text" name="saldo_fecha_toma[]" id="saldo_fecha_toma">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="tipo_moneda">Tipo de moneda (especificar)</label>
                                        <input class="form-control" type="text" name="tipo_moneda[]" id="tipo_moneda">
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_inversion">Tipo de inversión</label>
                                        <select class="form-control" id="id_cat_tipo_inversion" name="id_cat_tipo_inversion[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($inversiones as $inversion) : ?>
                                                <option value="<?php echo $inversion['id_cat_tipo_inversion']; ?>"><?php echo ucwords($inversion['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">
                            <div class="row" id="newRow">
                            </div>
                        </div>
                    </div>

                    <a href="cuentas.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_cuentas" class="btn btn-primary btn-md">Guardar</button>
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