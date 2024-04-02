<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Bienes inmuebles del declarante, cónyuge, concubina';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$operaciones = find_all('cat_tipo_operacion');
$bienes = find_all('cat_tipo_bien_inmueble');
$obras = find_all('cat_si_obra');
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
if (isset($_POST['add_bienes_inmuebles'])) {
    if (empty($errors)) {
        $ninguno = remove_junk($db->escape($_POST['ninguno']));
        if ($ninguno == 'on') {
            $query = "INSERT INTO rel_detalle_bienes_inmuebles (";
            $query .= "id_detalle_usuario, ninguno, fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', 1, NOW()";
            $query .= ")";
        }
        if ($ninguno != 'on') {
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

            for ($i = 0; $i < sizeof($id_cat_tipo_operacion); $i = $i + 1) {
                $query1 = "INSERT INTO rel_detalle_bienes_inmuebles (";
                $query1 .= "id_detalle_usuario, ninguno, id_cat_tipo_operacion, id_cat_tipo_bien, cat_si_obra, superficie_terreno, superficie_construccion,
                            id_cat_forma_adquisicion, nombre_razon_soc, id_cat_titular, id_cat_relacion_ces_don_her, si_otro, valor_inmueble, tipo_moneda, 
                            fecha_adquisicion, datos_registro, ubicacion_inmueble, si_es_obra, si_venta, fecha_creacion";
                $query1 .= ") VALUES (";
                $query1 .= "'{$id_detalle_usuario}', 0, '$id_cat_tipo_operacion[$i]', '$id_cat_tipo_bien[$i]', '$cat_si_obra[$i]', '$superficie_terreno[$i]', 
                            '$superficie_construccion[$i]', '$id_cat_forma_adquisicion[$i]', '$nombre_razon_soc[$i]', '$id_cat_titular[$i]', 
                            '$id_cat_relacion_ces_don_her[$i]', '$si_otro[$i]', '$valor_inmueble[$i]', '$tipo_moneda[$i]', '$fecha_adquisicion[$i]', 
                            '$datos_registro[$i]', '$ubicacion_inmueble[$i]', '$si_es_obra[$i]', '$si_venta[$i]', NOW()";
                $query1 .= ")";
                $db->query($query1);
            }
        }
        if ($db->query($query)) {
            $session->msg('s', "La información de el/los bien(es) inmueble(s) ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó bien(es) inmueble(s).', 1);
            redirect('bienes_inmuebles.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('bienes_inmuebles.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('bienes_inmuebles.php', false);
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
            html += '                       <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_tipo_bien">Tipo de bien</label>';
            html += '               <select class="form-control" id="id_cat_tipo_bien" name="id_cat_tipo_bien[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($bienes as $bien) : ?>';
            html += '                       <option value="<?php echo $bien['id_cat_tipo_bien']; ?>"><?php echo ucwords($bien['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="cat_si_obra">Si eligió obra indicar si se trata de</label>';
            html += '               <select class="form-control" id="cat_si_obra" name="cat_si_obra[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($obras as $obra) : ?>';
            html += '                       <option value="<?php echo $obra['id_cat_si_obra']; ?>"><?php echo ucwords($obra['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="superficie_terreno">Superficie o div. (Terreno) m2</label>';
            html += '               <input type="text" class="form-control" name="superficie_terreno[]" id="superficie_terreno">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="superficie_construccion">Superficie o div. (Construcción) m2</label>';
            html += '               <input type="text" class="form-control" name="superficie_construccion[]" id="superficie_construccion">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-2">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_forma_adquisicion">Forma de adquisición</label>';
            html += '               <select class="form-control" id="id_cat_forma_adquisicion" name="id_cat_forma_adquisicion[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($adquisiciones as $adquisicion) : ?>';
            html += '                <option value="<?php echo $adquisicion['id_cat_forma_adquisicion']; ?>"><?php echo ucwords($adquisicion['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="nombre_razon_soc" style="text-align: justify;">Indicar el nombre o razón social del cesionario, del autor de la    donación o del autor de ';
            html += 'la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular y llenar los dos rubros siguientes';
            html += '                (Para efectos de posible conflicto de interés)</label>';
            html += '               <input class="form-control" type="text" name="nombre_razon_soc[]" id="nombre_razon_soc">';
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
            html += '       <div class="col-md-2 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="id_cat_relacion_ces_don_her" style="text-align: justify;">Relación del cesionario del autor de la donación o del autor de la herencia, ';
            html += '               <select class=" form-control" id="id_cat_relacion_ces_don_her " name="id_cat_relacion_ces_don_her[]">';
            html += '                   <option value="">Escoge una opción</option>';
            html += '                   <?php foreach ($cesionarios as $cesionario) : ?>';
            html += '                       <option value="<?php echo $cesionario['id_cat_rel_cesionario']; ?>"><?php echo ucwords($cesionario['descripcion']); ?></option>';
            html += '                   <?php endforeach; ?>';
            html += '               </select>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="si_otro" style="text-align: justify;">En caso de elegir “otro” especificar la relación del cesionario, del autor de la donación o del ';
            html += '               <input class="form-control" type=" text" name="si_otro[]" id="si_otro">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="valor_inmueble" style="text-align: justify;">Valor del inmueble conforme a escritura pública o contrato (no actualizar a valor presente) SIN CENTAVOS</label>';
            html += '               <input class="form-control" type=" text" name="valor_inmueble[]" id="valor_inmueble">';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-3 d-flex flex-column justify-content-end">';
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
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="datos_registro" style="text-align: justify;">Datos del registro público de la propiedad: folio real u otro dato que permita a la identificación del mismo</label>';
            html += '               <input class="form-control" type="text" name="datos_registro[]" id="datos_registro">';
            html += '           </div>';
            html += '       </div>';
            html += '   </div>';
            html += '   <div class="row">';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="ubicacion_inmueble" style="text-align: justify;">Ubicación del inmueble.<p style="font-size:9px">Especificar lugar donde se ubica (México/Extranjero), calle, número exterior e interior, localidad o colonia, entidad federativa, municipio o alcaldía, código postal.</p></label>';
            html += '               <textarea class="form-control" name="ubicacion_inmueble[]" id="ubicacion_inmueble" cols="30" rows="5"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="si_es_obra" style="text-align: justify;">Si eligió OBRA deberá especificar los datos de la operación:<p style="font-size:9px">-Inversion de la obra</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Fecha de la obra</p>';
            html += '               </label>';
            html += '               <textarea class="form-control" name="si_es_obra[]" id="si_es_obra" cols="30" rows="5"></textarea>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-4 d-flex flex-column justify-content-end">';
            html += '           <div class="form-group">';
            html += '               <label for="si_venta" style="text-align: justify;">Si eligió VENTA deberá especificar los datos de la operación:<p style="font-size:9px">-Forma de operación</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>';
            html += '                   <p style="font-size:9px; margin-top:-10px;">-Fecha de operación</p>';
            html += '               </label>';
            html += '               <textarea class="form-control" name="si_venta[]" id="si_venta" cols="30" rows="5"></textarea>';
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
                    <span class="material-symbols-outlined" style="margin-top: -50px; color: #3a3d44; font-size: 25px;">
                        location_away
                    </span>
                    <p style="margin-top: -53px; margin-left: 32px; font-size: 20px;">Bienes Inmuebles del Declarante, Cónyuge, Concubina o Concubinario y/o Dependientes Económicos (Situación Actual)</p>
                </strong>
                <form method="post" action="add_bienes_inmuebles.php">
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
                                                <option value="<?php echo $operacion['id_cat_tipo_operacion']; ?>"><?php echo ucwords($operacion['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="id_cat_tipo_bien">Tipo de bien</label>
                                        <select class="form-control" id="id_cat_tipo_bien" name="id_cat_tipo_bien[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($bienes as $bien) : ?>
                                                <option value="<?php echo $bien['id_cat_tipo_bien']; ?>"><?php echo ucwords($bien['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cat_si_obra">Si eligió obra indicar si se trata de</label>
                                        <select class="form-control" id="cat_si_obra" name="cat_si_obra[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($obras as $obra) : ?>
                                                <option value="<?php echo $obra['id_cat_si_obra']; ?>"><?php echo ucwords($obra['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="superficie_terreno">Superficie o div. (Terreno) m2</label>
                                        <input type="text" class="form-control" name="superficie_terreno[]" id="superficie_terreno">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="superficie_construccion">Superficie o div. (Construcción) m2</label>
                                        <input type="text" class="form-control" name="superficie_construccion[]" id="superficie_construccion">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="id_cat_forma_adquisicion">Forma de adquisición</label>
                                        <select class="form-control" id="id_cat_forma_adquisicion" name="id_cat_forma_adquisicion[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($adquisiciones as $adquisicion) : ?>
                                                <option value="<?php echo $adquisicion['id_cat_forma_adquisicion']; ?>"><?php echo ucwords($adquisicion['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="nombre_razon_soc" style="text-align: justify;">Indicar el nombre o razón social del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular y llenar los dos rubros siguientes
                                            (Para efectos de posible conflicto de interés)</label>
                                        <input class=" form-control" type="text" name="nombre_razon_soc[]" id="nombre_razon_soc">
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
                                <div class="col-md-2 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="id_cat_relacion_ces_don_her" style="text-align: justify;">Relación del cesionario del autor de la donación o del autor de la herencia, con el titular</label>
                                        <select class=" form-control" id="id_cat_relacion_ces_don_her " name="id_cat_relacion_ces_don_her[]">
                                            <option value="">Escoge una opción</option>
                                            <?php foreach ($cesionarios as $cesionario) : ?>
                                                <option value="<?php echo $cesionario['id_cat_rel_cesionario']; ?>"><?php echo ucwords($cesionario['descripcion']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="si_otro" style="text-align: justify;">En caso de elegir “otro” especificar la relación del cesionario, del autor de la donación o del autor de la herencia, permuta, rifa, sorteo o del vendedor o enajenante con el titular</label>
                                        <input class="form-control" type=" text" name="si_otro[]" id="si_otro">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="valor_inmueble" style="text-align: justify;">Valor del inmueble conforme a escritura pública o contrato (no actualizar a valor presente) SIN CENTAVOS</label>
                                        <input class="form-control" type=" text" name="valor_inmueble[]" id="valor_inmueble">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end">
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
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="datos_registro" style="text-align: justify;">Datos del registro público de la propiedad: folio real u otro dato que permita a la identificación del mismo</label>
                                        <input class="form-control" type="text" name="datos_registro[]" id="datos_registro">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="ubicacion_inmueble" style="text-align: justify;">Ubicación del inmueble.<p style="font-size:9px">Especificar lugar donde se ubica (México/Extranjero), calle, número exterior e interior, localidad o colonia, entidad federativa, municipio o alcaldía, código postal.</p></label>
                                        <textarea class="form-control" name="ubicacion_inmueble[]" id="ubicacion_inmueble" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="si_es_obra" style="text-align: justify;">Si eligió OBRA deberá especificar los datos de la operación:<p style="font-size:9px">-Inversion de la obra</p>
                                            <p style="font-size:9px; margin-top:-10px;">-Fecha de la obra</p>
                                        </label>
                                        <textarea class="form-control" name="si_es_obra[]" id="si_es_obra" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-end">
                                    <div class="form-group">
                                        <label for="si_venta" style="text-align: justify;">Si eligió VENTA deberá especificar los datos de la operación:<p style="font-size:9px">-Forma de operación</p>
                                            <p style="font-size:9px; margin-top:-10px;">-Valor de la operación</p>
                                            <p style="font-size:9px; margin-top:-10px;">-Fecha de operación</p>
                                        </label>
                                        <textarea class="form-control" name="si_venta[]" id="si_venta" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="background: #3a3d44;  margin: auto; height: 0.5px; width: 95%; opacity: 0.5; margin-bottom: 15px;">
                        <div class="row" id="newRow">
                        </div>
                    </div>

                    <a href="bienes_inmuebles.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_bienes_inmuebles" class="btn btn-primary btn-md">Guardar</button>
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