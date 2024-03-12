<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos Curriculares del Declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$cat_municipios = find_all_cat_municipios();
$cat_nacionalidad = find_all('cat_nacionalidades');
$cat_entidad_fed = find_all('cat_entidad_fed');
$cat_escolaridad = find_all('cat_escolaridad');
$cat_periodos = find_all('cat_periodos_cursados');
$cat_estatus = find_all('cat_estatus_estudios');
$cat_documentos = find_all('cat_documento_obtenido');
?>
<style>
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 10%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        /* 10% from the top and centered */
        padding: 10px;
        /* Reducir el padding para hacerlo más pequeño */
        border: 1px solid #888;
        /* Cambiar el ancho del modal según sea necesario */
        max-width: 80%;
        width: 80%;
        /* Limitar el ancho máximo del modal */
    }

    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<?php
if (isset($_POST['add_datos_curri_declarante'])) {

    if (empty($errors)) {
        $id_cat_esc = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        if ($id_cat_esc <= 3) {
            $inst_educativa1 = remove_junk($db->escape($_POST['inst_educativa1']));
            $id_cat_periodo_cursado1 = remove_junk($db->escape($_POST['id_cat_periodo_cursado1']));
            $id_cat_documento_obtenido1 = remove_junk($db->escape($_POST['id_cat_documento_obtenido1']));
            $id_cat_estatus_estudios1 = remove_junk($db->escape($_POST['id_cat_estatus_estudios1']));

            $query = "INSERT INTO rel_detalle_estudios (";
            $query .= "id_detalle_usuario, id_cat_escolaridad, inst_educativa, id_cat_periodo_cursado, id_cat_documento_obtenido, id_cat_estatus_estudios,
                        fecha_creacion, estatus_detalle";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', '{$id_cat_esc}', '{$inst_educativa1}', '{$id_cat_periodo_cursado1}', '{$id_cat_documento_obtenido1}', 
                        '{$id_cat_estatus_estudios1}', NOW(), '1'";
            $query .= ")";
        }
        if ($id_cat_esc >= 4) {
            $inst_educativa = remove_junk($db->escape($_POST['inst_educativa']));
            $id_cat_periodo_cursado = remove_junk($db->escape($_POST['id_cat_periodo_cursado']));
            $id_cat_documento_obtenido = remove_junk($db->escape($_POST['id_cat_documento_obtenido']));
            $id_cat_estatus_estudios = remove_junk($db->escape($_POST['id_cat_estatus_estudios']));
            $ubic_inst = remove_junk($db->escape($_POST['ubic_inst']));
            $id_cat_ent_fed = remove_junk($db->escape($_POST['id_cat_ent_fed']));
            $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
            $carrera_area_con = remove_junk($db->escape($_POST['carrera_area_con']));
            $num_ced_prof = remove_junk($db->escape($_POST['num_ced_prof']));

            $query = "INSERT INTO rel_detalle_estudios (";
            $query .= "id_detalle_usuario, id_cat_escolaridad, inst_educativa, id_cat_periodo_cursado, id_cat_documento_obtenido, ubic_inst, id_cat_ent_fed,
                        id_cat_mun, carrera_area_con, id_cat_estatus_estudios, num_ced_prof, fecha_creacion, estatus_detalle";
            $query .= ") VALUES (";
            $query .= " '{$id_detalle_usuario}', '{$id_cat_esc}', '{$inst_educativa}', '{$id_cat_periodo_cursado}', '{$id_cat_documento_obtenido}', 
                        '{$ubic_inst}', '{$id_cat_ent_fed}', '{$id_cat_mun}', '{$carrera_area_con}', '{$id_cat_estatus_estudios}', '{$num_ced_prof}', NOW(), '1'";
            $query .= ")";
        }

        if ($db->query($query)) {
            //sucess
            $session->msg('s', "La información curricular ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó su escolaridad: ' . $id_cat_esc . '.', 1);
            redirect('datos_curri_declarante.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el trabajador.');
            redirect('add_datos_curri_declarante.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_datos_curri_declarante.php', false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<!-- <div class="row"> -->
    <div class="form-group clearfix">
        <a href="datos_curri_declarante.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
            Regresar
        </a>
    </div>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>
            <span class="material-symbols-outlined" style="color: #3a3d44">
                school
            </span>
            <p style="margin-top: -22px; margin-left: 32px;">Datos Curriculares del Declarante</p>
        </strong>
    </div>
    <!-- <form method="post" action="add_datos_curri_declarante.php"> -->
    <div class="panel-body">
        <p style="margin-top: -10px; font-weight: bold;">ESCOLARIDAD</p>
        <form method="post" action="add_datos_curri_declarante.php">
            <div class="row">
                <div id="inputFormRow" style="width: 100%;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_escolaridad">Grado máximo de estudios</label>
                            <select class="form-control" id="id_cat_escolaridad" name="id_cat_escolaridad" onchange="viewDatos(this)">
                                <option value="0">Escoge una opción</option>
                                <?php foreach ($cat_escolaridad as $id_cat_escolaridad) : ?>
                                    <option value="<?php echo $id_cat_escolaridad['id_cat_escolaridad']; ?>"><?php echo ucwords($id_cat_escolaridad['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="selectedValue" name="selectedValue" value="">
                </div>
            </div>

            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p style="margin-top: -10px; font-weight: bold;">SI ES PRIMARIA, SECUNDARIA O BACHILLERATO ESPECIFIQUE:</p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inst_educativa1">Institución Educativa</label>
                                <input type="text" class="form-control" id="inst_educativa1" name="inst_educativa1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_estatus_estudios">Estatus</label>
                                <select class="form-control" id="id_cat_estatus_estudios1" name="id_cat_estatus_estudios1">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_estatus as $estatus) : ?>
                                        <option value="<?php echo $estatus['id_cat_estatus_estudios']; ?>"><?php echo ucwords($estatus['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_periodo_cursado">Periodos Cursados</label>
                                <select class="form-control" id="id_cat_periodo_cursado1" name="id_cat_periodo_cursado1">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_periodos as $cat_periodo) : ?>
                                        <option value="<?php echo $cat_periodo['id_cat_periodo_cursado']; ?>"><?php echo ucwords($cat_periodo['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_documento_obtenido">Documento Obtenido</label>
                                <select class="form-control" id="id_cat_documento_obtenido1" name="id_cat_documento_obtenido1">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_documentos as $cat_documento) : ?>
                                        <option value="<?php echo $cat_documento['id_cat_documento_obtenido']; ?>"><?php echo ucwords($cat_documento['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_datos_curri_declarante" style="width: 75px;" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </div>
            <div id="myModal2" class="modal">
                <div class="modal-content">
                    <span class="close2">&times;</span>
                    <div class="row">
                        <p style="margin-top: -10px; font-weight: bold;">SI ES CARRERA TÉCNICA, LICENCIATURA, MAESTRÍA O DIPLOMADO ESPECIFIQUE:</p>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ubic_inst">Lugar donde se ubica la Institución Educativa</label>
                                <input type="text" class="form-control" name="ubic_inst">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_cat_ent_fed">Entidad Federativa</label>
                                <select class="form-control" id="id_cat_ent_fed" name="id_cat_ent_fed">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_entidad_fed as $entidad) : ?>
                                        <option value="<?php echo $entidad['id_cat_ent_fed']; ?>"><?php echo ucwords($entidad['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_cat_mun">Municipio</label>
                                <select class="form-control" id="id_cat_mun" name="id_cat_mun">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_municipios as $municipio) : ?>
                                        <option value="<?php echo $municipio['id_cat_mun']; ?>"><?php echo ucwords($municipio['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inst_educativa">Institución Educativa</label>
                                <input type="text" class="form-control" name="inst_educativa">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="carrera_area_con">Carrera o área del conocimiento</label>
                                <input type="text" class="form-control" name="carrera_area_con">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_estatus_estudios">Estatus</label>
                                <select class="form-control" id="id_cat_estatus_estudios" name="id_cat_estatus_estudios">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_estatus as $estatus) : ?>
                                        <option value="<?php echo $estatus['id_cat_estatus_estudios']; ?>"><?php echo ucwords($estatus['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="id_cat_periodo_cursado">Periodos Cursados</label>
                                <select class="form-control" id="id_cat_periodo_cursado" name="id_cat_periodo_cursado">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_periodos as $periodo) : ?>
                                        <option value="<?php echo $periodo['id_cat_periodo_cursado']; ?>"><?php echo ucwords($periodo['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_cat_documento_obtenido">Documento Obtenido</label>
                                <select class="form-control" id="id_cat_documento_obtenido" name="id_cat_documento_obtenido">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_documentos as $documento) : ?>
                                        <option value="<?php echo $documento['id_cat_documento_obtenido']; ?>"><?php echo ucwords($documento['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="num_ced_prof">Número de Cédula Profesional</label>
                                <input type="text" class="form-control" name="num_ced_prof">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_datos_curri_declarante" style="width: 70px;" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </div>
        </form>
        <!-- </form> -->
    </div>
</div>
<!-- </div> -->

<script>
    // JavaScript
    function checkSelect() {
        var selectValue = document.getElementById("id_cat_escolaridad").value;

        // Enviar el valor seleccionado al servidor utilizando AJAX
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("result").innerHTML = this.responseText;
            }
        };
        xhttp.open("POST", "add_datos_curri_declarante.php", true); // Aquí debes especificar la ruta de tu script PHP
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("id_cat_escolaridad=" + selectValue);
    }

    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Get the modal
    var modal2 = document.getElementById("myModal2");
    // Get the <span> element that closes the modal
    var span2 = document.getElementsByClassName("close2")[0];
    // When the user clicks on <span> (x), close the modal
    span2.onclick = function() {
        modal2.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal2) {
            modal2.style.display = "none";
        }
    }

    // // Submit form
    // document.getElementById("add_datos_curri_declarante").addEventListener("submit", function(event) {
    //     event.preventDefault(); // Prevent default form submission
    //     // Your form submission logic here
    //     alert('Formulario enviado'); // Placeholder for actual form submission
    // });

    function viewDatos(select) {
        // var selectValor = document.getElementById("id_cat_escolaridad").value;
        var selectValor = select.value;
        // alert(selectValor);

        if (selectValor <= "3") {
            modal.style.display = "block";
            modal2.style.display = "none";
        } else if (selectValor >= "4") {
            modal.style.display = "none";
            modal2.style.display = "block";
        }
    }
</script>

<?php include_once('layouts/footer.php'); ?>