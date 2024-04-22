<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Vehículos del declarante, cónyuge, concubina';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$operaciones = find_all('cat_tipo_operacion');
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
if (isset($_POST['add_vehiculos'])) {
    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];
        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_detalle_automotores (";
            $query .= "id_detalle_usuario, id_rel_declaracion, ninguno, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', '{$declaracion}', 1, NOW()";
            $query .= ")";
        }
        if ($ninguno != 'on') {
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

            for ($i = 0; $i < sizeof($id_cat_tipo_operacion); $i = $i + 1) {
                $query1 = "INSERT INTO rel_detalle_automotores (";
                $query1 .= "id_detalle_usuario, id_rel_declaracion, ninguno, id_cat_tipo_operacion, marca, tipo, modelo, num_serie, donde_registrado, 
                            ent_fed_pais, id_cat_forma_adquis, nombre_cesionario, id_cat_rel_cesionario, otro_especifica, valor_momento_compra, tipo_moneda, 
                            fecha_adquisicion, id_cat_titular, si_vent_forma_oper, si_sinies_tipo, fecha_creacion";
                $query1 .= ") VALUES (";
                $query1 .= "'{$id_detalle_usuario}', '{$declaracion}', 0, '$id_cat_tipo_operacion[$i]', '$marca[$i]', '$tipo[$i]', '$modelo[$i]', 
                            '$num_serie[$i]', '$donde_registrado[$i]', '$ent_fed_pais[$i]', '$id_cat_forma_adquis[$i]', '$nombre_cesionario[$i]', '$id_cat_rel_cesionario[$i]', '$otro_especifica[$i]', '$valor_momento_compra[$i]', '$tipo_moneda[$i]', '$fecha_adquisicion[$i]', '$id_cat_titular[$i]', '$si_vent_forma_oper[$i]', '$si_sinies_tipo[$i]', NOW()";
                $query1 .= ")";
                $db->query($query1);
            }
        }

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);

        if (($db->query($query)) && ($result2)) {
            $session->msg('s', "La información de el/los vehículo(s) ha sido agregada con éxito. Continúa con bienes muebles.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó vehículo(s).', 1);
            updateLastArchivo('bienes_muebles.php', $declaracion);
            redirect('bienes_muebles.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('vehiculos.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('vehiculos.php', false);
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
            html += '                       <?php if ($x != 2 && $x != 6 && $x != 7 && $x != 8) : ?>';
            html += '                        <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>';
            html += '                       <?php endif; ?>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="marca">Marca</label>';
            html += '               <input class="form-control" type="text" name="marca[]" id="marca">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="tipo">Tipo</label>';
            html += '               <input class="form-control" type="text" name="tipo[]" id="tipo">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="modelo">Modelo</label>';
            html += '               <input class="form-control" type="text" name="modelo[]" id="modelo">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="num_serie">Número de serie</label>';
            html += '               <input class="form-control" type="text" name="num_serie[]" id="num_serie">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="donde_registrado">¿Dónde se encuentra registrado?</label>';
            html += '               <select class="form-control" id="donde_registrado" name="donde_registrado[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <option value="México">México</option>';
            html += '                   <option value="Extranjero">Extranjero</option>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="ent_fed_pais">Entidad Federativa<label style="font-size: 9px;">(Si es en México indique el estado, si es en el extranjero indique el ';
            html += '               <input class="form-control" type="text" name="ent_fed_pais[]" id="ent_fed_pais">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_forma_adquis">Forma de adquisición</label>';
            html += '               <select class="form-control" id="id_cat_forma_adquis" name="id_cat_forma_adquis[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($adquisiciones as $adquisicion) : ?>';
            html += '                       <option value="<?php echo $adquisicion['id_cat_forma_adquisicion']; ?>"><?php echo ucwords($adquisicion['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-5 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="nombre_cesionario" style="text-align: justify;">Indicar el nombre o razón social del cesionario, del autor de la donación o del autor de la herencia con el titular y llenar los dos rubros siguientes (Para efectos de posible conflicto de interés)</label>';
            html += '               <input class=" form-control" type="text" name="nombre_cesionario[]">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_rel_cesionario" style="text-align: justify;">Relación del cesionario del autor de la donación o del autor de la herencia, con el titular</label>';
            html += '               <select class=" form-control" id="id_cat_rel_cesionario" name="id_cat_rel_cesionario[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($cesionarios as $cesionario) : ?>';
            html += '                       <option value="<?php echo $cesionario['id_cat_rel_cesionario']; ?>"><?php echo ucwords($cesionario['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="otro_especifica" style="text-align: justify;">En caso de elegir “otro” especificar la relación del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular</label>';
            html += '               <input class="form-control" type=" text" name="otro_especifica[]" id="otro_especifica">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="valor_momento_compra" style="text-align: justify;">Valor del vehículo al momento de la adquisición SIN CENTAVOS</label>';
            html += '               <input class="form-control" type=" text" name="valor_momento_compra[]" id="valor_momento_compra">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="tipo_moneda" style="text-align: justify;">Tipo de moneda (especificar)</label>';
            html += '               <input class="form-control" type=" text" name="tipo_moneda[]" id="tipo_moneda">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="fecha_adquisicion" style="text-align: justify;">Fecha de adquisición</label>';
            html += '               <input class="form-control" type="date" name="fecha_adquisicion[]" id="fecha_adquisicion">';
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
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="si_venta_forma_oper" style="text-align: justify;">Si eligió VENTA deberá especificar los datos de la operación:<p style="font-size:9px">-Forma de operación</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Fecha de operación</p>';
            html += '               </label>';
            html += '               <textarea class="form-control" name="si_venta_forma_oper[]" id="si_venta_forma_oper" cols="30" rows="5"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="si_sinies_tipo" style="text-align: justify;">Si eligió SINIESTRO deberá especificar los datos de la operación:<p style="font-size:9px">-Tipo de siniestro</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Aseguradora</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Fecha del siniestro</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>';
            html += '               </label>';
            html += '               <textarea class="form-control" name="si_sinies_tipo[]" id="si_sinies_tipo" cols="30" rows="5"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   </div> ';
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
                    <span class="material-symbols-outlined" style="margin-top: -30px; color: #3a3d44; font-size: 35px;">
                        directions_car
                    </span>
                    <p style="margin-top: -48px; margin-left: 40px; font-size: 20px;">Vehículos automotores, aeronaves y embarcaciones del declarante, cónyuge, concubina o concubinario y/o dependientes económicos (situación actual)</p>
                </strong>
                <form method="post" action="add_vehiculos.php">
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
                                                if ($x != 2 && $x != 6 && $x != 7 && $x != 8) : ?>
                                                    <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="marca">Marca</label>
                                        <input class="form-control" type="text" name="marca[]" id="marca">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tipo">Tipo</label>
                                        <input class="form-control" type="text" name="tipo[]" id="tipo">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="modelo">Modelo</label>
                                        <input class="form-control" type="text" name="modelo[]" id="modelo">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="num_serie">Número de serie</label>
                                        <input class="form-control" type="text" name="num_serie[]" id="num_serie">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="donde_registrado">¿Dónde se encuentra registrado?</label>
                                        <select class="form-control" id="donde_registrado" name="donde_registrado[]">
                                            <option value="">Escoge una opción</option>
                                            <option value="México">México</option>
                                            <option value="Extranjero">Extranjero</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="ent_fed_pais">Entidad Federativa<label style="font-size: 9px;">(Si es en México indique el estado, si es en el extranjero indique el país)</label></label>
                                        <input class="form-control" type="text" name="ent_fed_pais[]" id="ent_fed_pais">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_forma_adquis">Forma de adquisición</label>
                                        <select class="form-control" id="id_cat_forma_adquis" name="id_cat_forma_adquis[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($adquisiciones as $adquisicion) : ?>
                                                <option value="<?php echo $adquisicion['id_cat_forma_adquisicion']; ?>"><?php echo ucwords($adquisicion['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="nombre_cesionario" style="text-align: justify;">Indicar el nombre o razón social del cesionario, del autor de la donación o del autor de la herencia con el titular y llenar los dos rubros siguientes (Para efectos de posible conflicto de interés)</label>
                                        <input class=" form-control" type="text" name="nombre_cesionario[]">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_rel_cesionario" style="text-align: justify;">Relación del cesionario del autor de la donación o del autor de la herencia, con el titular</label>
                                        <select class=" form-control" id="id_cat_rel_cesionario" name="id_cat_rel_cesionario[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($cesionarios as $cesionario) : ?>
                                                <option value="<?php echo $cesionario['id_cat_rel_cesionario']; ?>"><?php echo ucwords($cesionario['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="otro_especifica" style="text-align: justify;">En caso de elegir “otro” especificar la relación del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular</label>
                                        <input class="form-control" type=" text" name="otro_especifica[]" id="otro_especifica">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="valor_momento_compra" style="text-align: justify;">Valor del vehículo al momento de la adquisición SIN CENTAVOS</label>
                                        <input class="form-control" type=" text" name="valor_momento_compra[]" id="valor_momento_compra">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="tipo_moneda" style="text-align: justify;">Tipo de moneda (especificar)</label>
                                        <input class="form-control" type=" text" name="tipo_moneda[]" id="tipo_moneda">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="fecha_adquisicion" style="text-align: justify;">Fecha de adquisición</label>
                                        <input class="form-control" type="date" name="fecha_adquisicion[]" id="fecha_adquisicion">
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
                            </div>
                            <div class="row">
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="si_vent_forma_oper" style="text-align: justify;">Si eligió VENTA deberá especificar los datos de la operación:<p style="font-size:9px">-Forma de operación</p>
                                            <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>
                                            <p style="font-size:9px; margin-top:-10px;">-Fecha de operación</p>
                                        </label>
                                        <textarea class="form-control" name="si_vent_forma_oper[]" id="si_vent_forma_oper" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="si_sinies_tipo" style="text-align: justify;">Si eligió SINIESTRO deberá especificar los datos de la operación:<p style="font-size:9px">-Tipo de siniestro</p><p style="font-size:9px; margin-top:-10px;">-Aseguradora</p>
                                            <p style="font-size:9px; margin-top:-10px;">-Fecha del siniestro</p><p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>
                                        </label>
                                        <textarea class="form-control" name="si_sinies_tipo[]" id="si_sinies_tipo" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">
                        <div class="row" id="newRow">
                        </div>
                    </div>

                    <a href="vehiculos.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_vehiculos" class="btn btn-primary btn-md">Guardar</button>
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