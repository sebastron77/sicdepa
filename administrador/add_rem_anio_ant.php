<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Situación Patrimonial Año Anterior';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
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
if (isset($_POST['add_rem_anio_ant'])) {

    if (empty($errors)) {
        $ing_anual_dec_cony = remove_junk($db->escape($_POST['ing_anual_dec_cony']));
        if ($ing_anual_dec_cony == 'on') {
            $bool = '1';
        } else {
            $bool = '0';
        }
        $inicio_periodo = remove_junk($db->escape($_POST['inicio_periodo']));
        $fin_periodo = remove_junk($db->escape($_POST['fin_periodo']));
        $renum_anual_neta = remove_junk($db->escape($_POST['renum_anual_neta']));
        $nombre_act_indus = remove_junk($db->escape($_POST['nombre_act_indus']));
        $act_indus = remove_junk($db->escape($_POST['act_indus']));
        $nombre_act_fin = remove_junk($db->escape($_POST['nombre_act_fin']));
        $act_finan = remove_junk($db->escape($_POST['act_finan']));
        $tipo_serv_prof = remove_junk($db->escape($_POST['tipo_serv_prof']));
        $serv_prof = remove_junk($db->escape($_POST['serv_prof']));
        $otros_info = remove_junk($db->escape($_POST['otros_info']));
        $otros = remove_junk($db->escape($_POST['otros']));
        $subtotal2 = remove_junk($db->escape($_POST['subtotal2']));
        $subtotal1_2 = remove_junk($db->escape($_POST['subtotal1_2']));
        $cony_deduce_imp = remove_junk($db->escape($_POST['cony_deduce_imp']));
        $ingr_anual_cony = remove_junk($db->escape($_POST['ingr_anual_cony']));
        $suma_ab = remove_junk($db->escape($_POST['suma_ab']));
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];

        $monto_solo1 = str_replace("$", "", $renum_anual_neta);
        $monto_solo2 = str_replace("$", "", $act_indus);
        $monto_solo3 = str_replace("$", "", $act_finan);
        $monto_solo4 = str_replace("$", "", $serv_prof);
        $monto_solo5 = str_replace("$", "", $otros);
        $monto_solo6 = str_replace("$", "", $subtotal2);
        $monto_solo7 = str_replace("$", "", $subtotal1_2);
        $monto_solo8 = str_replace("$", "", $ingr_anual_cony);
        $monto_solo9 = str_replace("$", "", $suma_ab);

        $query = "INSERT INTO rel_detalle_renum_anio_ant (";
        $query .= "id_detalle_usuario, id_rel_declaracion, ing_anual_dec_cony, inicio_periodo, fin_periodo, renum_anual_neta, nombre_act_indus, act_indus, 
                    nombre_act_fin, act_finan,tipo_serv_prof, serv_prof, otros_info, otros, subtotal2, subtotal1_2, cony_deduce_imp, ingr_anual_cony, suma_ab, fecha_creacion";
        $query .= ") VALUES (";
        $query .= " '{$id_detalle_usuario}','{$declaracion}', '{$bool}', '{$inicio_periodo}', '{$fin_periodo}', '{$monto_solo1}', '{$nombre_act_indus}', 
                    '{$monto_solo2}', '{$nombre_act_fin}', '{$monto_solo3}', '{$tipo_serv_prof}', '{$monto_solo4}', '{$otros_info}', '{$monto_solo5}', 
                    '{$monto_solo6}', '{$monto_solo7}', '{$cony_deduce_imp}', '{$monto_solo8}', '{$monto_solo9}', NOW()";
        $query .= ")";

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);

        if (($db->query($query)) && ($result2)) {
            $session->msg('s', "La información de la situación patrimonial del año inmediato anterior ha sido agregada con éxito. Continúa con los bienes inmuebles.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó remun. anio anterior: ' . '.', 1);
            updateLastArchivo('bienes_inmuebles.php', $declaracion);
            redirect('bienes_inmuebles.php', false);
        } else {
            $session->msg('d', ' No se pudo agregar la información de la situación patrimonial del año inmediato anterior.');
            redirect('rem_anio_ant.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_rem_anio_ant.php', false);
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
                    <span class="material-symbols-outlined" style="margin-top: -50px; margin-left: -10px; color: #3a3d44; font-size: 30px;">
                        monetization_on
                    </span>
                    <?php
                    $ano_actual = date("Y");
                    $ano_anterior = $ano_actual - 1;
                    ?>
                    <p style="margin-top: -50px; margin-left: 32px; font-size: 20px;">¿Te Desempeñaste Como Servidor Público Obligado a Presentar Declaración de Situación Patrimonial en el Año Inmediato Anterior? (<?php echo $ano_anterior; ?>)</p>
                </strong>
                <form method="post" action="add_rem_anio_ant.php">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row" style="margin-top: -10px">
                            <label style="font-size: 17px;">Ingreso anual neto del declarante, cónyuge, concubina o concubinarioy/o dependientes económicos entre el 1 de enero y el 31 de diciembre del año inmediato anterior</label>
                        </div>
                        <div class="row" style="margin-bottom: 25px;">
                            <div class="col-md-1">
                                <div class="switch-container" style="margin-top: 2px;">
                                    <span class="switch-label" style="font-size: 12px;">No</span>
                                    <label class="switch" style="margin-top: 5px;">
                                        <input type="checkbox" name="ing_anual_dec_cony">
                                        <span class="slider"></span>
                                    </label>
                                    <span class="switch-label" style="margin-left: 8px; font-size: 12px;">Sí</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label style="font-size: 11px; margin-top: 8px;">Si la respuesta es afirmativa indica el periodo del</label>
                            </div>
                            <div class="col-md-2" style="margin-left: -5%; width: 150px;">
                                <input class="form-control" type="date" name="inicio_periodo">
                            </div>
                            <div class="col-md-2">
                                <label style="margin-top: 8px;">al</label>
                            </div>
                            <div class="col-md-2" style="margin-left: -15%; width: 150px;">
                                <input class="form-control" type="date" name="fin_periodo">
                            </div>
                            <div class="col-md-2">
                                <label style="margin-top: 8px;">y los ingresos netos del año anterior</label>
                            </div>
                        </div>
                        <hr style="margin-top: -15px; margin-bottom: 25px; height: 1.3px; background-color: black; border: none;">
                        <div class="row">
                            <div class="col-md-8">
                                <label>I. REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (DEDUCE IMPUESTOS)</label>
                                <label style="font-size: 10px; margin-left: 1.5%;">(Por concepto de sueldos, honorarios, compensaciones, bonos, aguinaldos y otras prestaciones)</label>
                            </div>
                            <div class="col-md-2" style="margin-top: -8px;">
                                <div class="form-group">
                                    <label for="renum_mens">Subtotal I</label>
                                    <input type="text" class="form-control montodos" name="renum_anual_neta" id="currency-field renum_anual_neta" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar2();">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: -8px;">
                            <label>II. OTROS INGRESOS MENSUALES NETOS DEL DECLARANTE (SUMA DEL II.1 AL II.4)</label>
                        </div>
                        <div class="row" style="margin-left: 2%;">
                            <label style="font-size: 12px;">II. 1 Por actividad industrial y/o comercial</label>
                            <div class="col-md-7">
                                <label style="font-size: 12px;">Especifica nombre o razón social y tipo de negocio (deduce impuestos)</label>
                                <input class="form-control" type="text" name="nombre_act_indus" id="nombre_act_indus">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="act_indus" id="currency-field act_indus" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();">
                            </div>
                        </div>
                        <div class="row" style="margin-left: 2%; margin-top: 10px;">
                            <label style="font-size: 12px;">II. 2 Por actividad financiera (rendimientos de contratos bancarios o de valores)</label>
                            <div class="col-md-7">
                                <label style="font-size: 12px;">(Deduce Impuestos)</label>
                                <input class="form-control" type="text" name="nombre_act_fin" id="nombre_act_fin">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="act_finan" id="currency-field act_finan" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();">
                            </div>
                        </div>
                        <div class="row" style="margin-left: 2%; margin-top: 10px;">
                            <label style="font-size: 12px;">II. 3 Por servicios profesionales, participación en consejos, consultorías o asesorías</label>
                            <div class="col-md-7">
                                <label style="font-size: 12px;">Especifica el tipo de servicio y el contratante (Deduce Impuestos)</label>
                                <input class="form-control" type="text" name="tipo_serv_prof" id="tipo_serv_prof">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="serv_prof" id="currency-field serv_prof" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();">
                            </div>
                        </div>
                        <div class="row" style="margin-left: 2%; margin-top: 8px;">
                            <label style="font-size: 12px;">II. 4 Otros (arrendamientos, regalías, sorteos, concursos, donaciones, etc.) Especifica</label>
                            <div class="col-md-7">
                                <label style="font-size: 12px;">(Deduce Impuestos)</label>
                                <input class="form-control" type="text" name="otros_info" id="otros_info">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="otros" id="currency-field otros" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: 8px;">
                                <label style="font-size: 12px;">Subtotal II</label>
                                <input type="text" class="form-control montodos" name="subtotal2" id="subtotal2" readonly onchange="sumar2();">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 50px;">
                            <div class="col-md-3">
                                <label style="font-size: 12px; margin-top: -25px;">A. INGRESO MENSUAL NETO DEL DECLARANTE</label>
                            </div>
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: -35px">
                                <label style="font-size: 12px; margin-top: -10px">Suma del subtotal I y subtotal II</label>
                                <input type="text" class="form-control montotres" name="subtotal1_2" id="subtotal1_2" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar3();" readonly>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-7">
                                <label style="font-size: 12px; margin-top: -5px;">B. INGRESO MENSUAL NETO DEL CÓNYUGE CONCUBINA O CONCUBINARIO Y/O DEPENDIENTES ECONÓMICOS (DEDUCE IMPUESTOS)</label>
                                <input class="form-control" type="text" name="cony_deduce_imp" id="cony_deduce_imp">
                            </div>
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: -53px">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control montotres" name="ingr_anual_cony" id="currnrency-field ingr_anual_cony" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar3();">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 50px;">
                            <div class="col-md-7">
                                <label style="font-size: 12px;" style="margin-top: -20px;">C. TOTAL DE INGRESO MENSUAL NETO DEL DECLARANTE, CÓNYUGE, CONCUBINA, CONCUBINARIO Y/O DEPENDIENTES ECONÓMICOS</label>
                            </div>
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: -55px">
                                <label style="font-size: 12px;">Suma de A y B</label>
                                <input type="text" class="form-control" name="suma_ab" id="suma_ab" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" readonly>
                            </div>
                        </div>
                    </div><br>

                    <a href="rem_mens.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="add_rem_anio_ant" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function sumar2() {
        const $subtotal1_2 = document.getElementById('subtotal1_2');
        let subtotaldos = 0;
        document.querySelectorAll("input.montodos").forEach(function(element) {
            // Eliminar símbolos de moneda y comas antes de sumar
            const valuedos = element.value.replace("$", "").replace(/,/g, "");
            if (valuedos !== '') {
                // alert(value);
                subtotaldos += parseFloat(valuedos);
                // alert(subtotaldos);
            }
        });
        $subtotal1_2.value = "$" + subtotaldos; // Redondear a 2 decimales
    }

    function sumar() {

        const $subtotal2 = document.getElementById('subtotal2');
        let subtotal = 0;
        document.querySelectorAll("input.monto").forEach(function(element) {
            // Eliminar símbolos de moneda y comas antes de sumar
            const value = element.value.replace("$", "").replace(/,/g, "");
            if (value !== '') {
                // alert(value);
                subtotal += parseFloat(value);

            }
        });

        $subtotal2.value = "$" + subtotal; // Redondear a 2 decimales
        sumar2();
    }

    function sumar3() {

        const $suma_ab = document.getElementById('suma_ab');
        let subtotal_ab = 0;
        document.querySelectorAll("input.montotres").forEach(function(element) {
            // Eliminar símbolos de moneda y comas antes de sumar
            const valuetres = element.value.replace("$", "").replace(/,/g, "");
            if (valuetres !== '') {
                // alert(value);
                subtotal_ab += parseFloat(valuetres);

            }
        });

        $suma_ab.value = "$" + subtotal_ab; // Redondear a 2 decimales
        sumar2();
    }

    // Jquery Dependency
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
        // Appends $ to value, validates decimal side and puts cursor back in right position.
        // Get input value
        var input_val = input.val();
        // Don't validate empty input
        if (input_val === "") {
            return;
        }
        // Original length
        var original_len = input_val.length;
        // Initial caret position 
        var caret_pos = input.prop("selectionStart");
        // Check for decimal
        if (input_val.indexOf(".") >= 0) {
            // Get position of first decimal this prevents multiple decimals from being entered
            var decimal_pos = input_val.indexOf(".");
            // Split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);
            // Add commas to left side of number
            left_side = formatNumber(left_side);
            // Validate right side
            right_side = formatNumber(right_side);
            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }
            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);
            // Jjoin number by .
            input_val = "$" + left_side + "." + right_side;
        } else {
            // No decimal entered, add commas to number, remove all non-digits
            input_val = formatNumber(input_val);
            input_val = "$" + input_val;
            // Final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }
        // Send updated string to input
        input.val(input_val);
        // Put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>
<?php include_once('layouts/footer.php'); ?>