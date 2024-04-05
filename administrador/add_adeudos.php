<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Adeudos del declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$operaciones = find_all('cat_tipo_operacion');
$titulares = find_all('cat_titular');
$adeudos = find_all('cat_tipo_adeudo');
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
if (isset($_POST['add_adeudos'])) {
    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_detalle_adeudos (";
            $query .= "id_detalle_usuario, ninguno, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', 1, NOW()";
            $query .= ")";
        }
        if ($ninguno != 'on') {
            $id_cat_tipo_operacion = $_POST['id_cat_tipo_operacion'];
            $id_cat_tipo_adeudo = $_POST['id_cat_tipo_adeudo'];
            $num_cuenta = $_POST['num_cuenta'];
            $mexico = $_POST['mexico'];
            $inst_razon_soc = $_POST['inst_razon_soc'];
            $extranjero = $_POST['extranjero'];
            $pais_inst_razon_soc = $_POST['pais_inst_razon_soc'];
            $fecha_otorgamiento = $_POST['fecha_otorgamiento'];
            $monto_orig_adeudo = $_POST['monto_orig_adeudo'];
            $tipo_moneda_or = $_POST['tipo_moneda_or'];
            $saldo_inso = $_POST['saldo_inso'];
            $tipo_moneda_ins = $_POST['tipo_moneda_ins'];
            $id_cat_plazo_adeudo = $_POST['id_cat_plazo_adeudo'];
            $id_cat_titular = $_POST['id_cat_titular'];

            for ($i = 0; $i < sizeof($id_cat_tipo_operacion); $i = $i + 1) {
                $query1 = "INSERT INTO rel_detalle_adeudos (";
                $query1 .= "id_detalle_usuario, ninguno, id_cat_tipo_operacion, id_cat_tipo_adeudo, num_cuenta, mexico, inst_razon_soc, extranjero, 
                            pais_inst_razon_soc, fecha_otorgamiento, monto_orig_adeudo, tipo_moneda_or, saldo_inso, tipo_moneda_ins, id_cat_plazo_adeudo, 
                            id_cat_titular, fecha_creacion";
                $query1 .= ") VALUES (";
                $query1 .= "'{$id_detalle_usuario}', 0, '$id_cat_tipo_operacion[$i]', '$id_cat_tipo_adeudo[$i]', '$num_cuenta[$i]', '$mexico[$i]', 
                            '$inst_razon_soc[$i]', '$extranjero[$i]', '$pais_inst_razon_soc[$i]', '$fecha_otorgamiento[$i]', '$monto_orig_adeudo[$i]', 
                            '$tipo_moneda_or[$i]', '$saldo_inso[$i]', '$tipo_moneda_ins[$i]', '$id_cat_plazo_adeudo[$i]', '$id_cat_titular[$i]', NOW()";
                $query1 .= ")";
                $db->query($query1);
            }
        }
        if ($db->query($query)) {
            $session->msg('s', "La información de los adeudos ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó adeudo.', 1);
            redirect('adeudos.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('adeudos.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('adeudos.php', false);
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
            html += '                       <?php $x = $operacion['id_cat_tipo_operacion'];?>';
            html += '                       <?php if ($x != 2 && $x != 3 && $x != 5 && $x != 8) : ?>';
            html += '                           <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>';
            html += '                       <?php endif; ?>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_adeudo">Tipo de adeudo</label>';
            html += '               <select class="form-control" id="id_cat_tipo_adeudo" name="id_cat_tipo_adeudo[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($adeudos as $adeudo) : ?>';
            html += '                   <option value="<?php echo $adeudo['id_cat_tipo_adeudo']; ?>"><?php echo ucwords($adeudo['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="num_cuenta">Número de cuenta o contrato</label>';
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
            html += '               <label for="inst_razon_soc">Institución o razón social</label>';
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
            html += '               <label for="pais_inst_razon_soc">País e institución o razón social</label>';
            html += '               <input class="form-control" type="text" name="pais_inst_razon_soc[]" id="pais_inst_razon_soc">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="fecha_otorgamiento">Fecha del otorgamiento</label>';
            html += '               <input class="form-control" type="date" name="fecha_otorgamiento[]" id="fecha_otorgamiento">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="monto_orig_adeudo">Monto original del adeudo SIN CENTAVOS</label>';
            html += '               <input class="form-control" type="text" name="monto_orig_adeudo[]" id="monto_orig_adeudo">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="tipo_moneda_or">Tipo moneda (especificar)</label>';
            html += '               <input class="form-control" type="text" name="tipo_moneda_or[]" id="tipo_moneda_or">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="saldo_inso">Saldo Insoluto a la fecha del encargo que inicia SIN CENTAVOS </label>';
            html += '               <input class="form-control" type="text" name="saldo_inso[]" id="saldo_inso">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="tipo_moneda_ins">Tipo de moneda (especificar)</label>';
            html += '               <input class="form-control" type="text" name="tipo_moneda_ins[]" id="tipo_moneda_ins">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_plazo_adeudo">Plazo del adeudo<p style="font-size:9px">-Vehículos (meses)</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Crédito Hipotecario (años)</p></label>';
            html += '               <input class="form-control" type="text" name="id_cat_plazo_adeudo[]" id="id_cat_plazo_adeudo">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_titular">Titular</label>';
            html += '               <select class="form-control" id="id_cat_titular" name="id_cat_titular[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($titulares as $titular) : ?>';
            html += '                    <option value="<?php echo $titular['id_cat_titular']; ?>"><?php echo ucwords($titular['descripcion']); ?></option>';
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
                    <p style="margin-top: -37px; margin-left: 40px; font-size: 20px;">Adeudos del declarante, cónyuge, concubina o concubinario y/o dependientes económicos (situación actual)</p>
                </strong>
                <form method="post" action="add_adeudos.php">
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
                                                if ($x != 2 && $x != 3 && $x != 5 && $x != 8) : ?>
                                                    <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_adeudo">Tipo de adeudo</label>
                                        <select class="form-control" id="id_cat_tipo_adeudo" name="id_cat_tipo_adeudo[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($adeudos as $adeudo) : ?>
                                                <option value="<?php echo $adeudo['id_cat_tipo_adeudo']; ?>"><?php echo ucwords($adeudo['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="num_cuenta">Número de cuenta o contrato</label>
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
                                        <label for="inst_razon_soc">Institución o razón social</label>
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
                                        <label for="pais_inst_razon_soc">País e institución o razón social</label>
                                        <input class="form-control" type="text" name="pais_inst_razon_soc[]" id="pais_inst_razon_soc">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="fecha_otorgamiento">Fecha del otorgamiento</label>
                                        <input class="form-control" type="date" name="fecha_otorgamiento[]" id="fecha_otorgamiento">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="monto_orig_adeudo">Monto original del adeudo SIN CENTAVOS</label>
                                        <input class="form-control" type="text" name="monto_orig_adeudo[]" id="monto_orig_adeudo">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="tipo_moneda_or">Tipo moneda (especificar)</label>
                                        <input class="form-control" type="text" name="tipo_moneda_or[]" id="tipo_moneda_or">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="saldo_inso">Saldo Insoluto a la fecha del encargo que inicia SIN CENTAVOS </label>
                                        <input class="form-control" type="text" name="saldo_inso[]" id="saldo_inso">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="tipo_moneda_ins">Tipo de moneda (especificar)</label>
                                        <input class="form-control" type="text" name="tipo_moneda_ins[]" id="tipo_moneda_ins">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_plazo_adeudo">Plazo del adeudo<p style="font-size:9px">-Vehículos (meses)</p>
                                            <p style="font-size:9px; margin-top:-10px;">-Crédito Hipotecario (años)</p></label>
                                        <input class="form-control" type="text" name="id_cat_plazo_adeudo[]" id="id_cat_plazo_adeudo">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
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
                            </div>
                            <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">
                            <div class="row" id="newRow">
                            </div>
                        </div>
                    </div>

                    <a href="adeudos.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_adeudos" class="btn btn-primary btn-md">Guardar</button>
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