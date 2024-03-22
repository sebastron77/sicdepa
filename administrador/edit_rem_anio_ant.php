<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Remuneración Declarante';
require_once('includes/load.php');
error_reporting(E_ALL ^ E_NOTICE);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$detalles = find_by_id('rel_detalle_renum', (int)$_GET['id'], 'id_rel_detalle_renum');
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
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$detalles['id_rel_detalle_renum'];
        $renum_mens = remove_junk($db->escape($_POST['renum_mens']));
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
        $ingr_cony = remove_junk($db->escape($_POST['ingr_cony']));
        $suma_ab = remove_junk($db->escape($_POST['suma_ab']));

        $monto_solo1 = str_replace("$", "", $renum_mens);
        $monto_solo2 = str_replace("$", "", $act_indus);
        $monto_solo3 = str_replace("$", "", $act_finan);
        $monto_solo4 = str_replace("$", "", $serv_prof);
        $monto_solo5 = str_replace("$", "", $otros);
        $monto_solo6 = str_replace("$", "", $subtotal2);
        $monto_solo7 = str_replace("$", "", $subtotal1_2);
        $monto_solo8 = str_replace("$", "", $ingr_cony);
        $monto_solo9 = str_replace("$", "", $suma_ab);

        $sql = "UPDATE rel_detalle_renum SET renum_mens='{$monto_solo1}', nombre_act_indus='{$nombre_act_indus}', act_indus='{$monto_solo2}', 
                nombre_act_fin='{$nombre_act_fin}', act_finan='{$monto_solo3}', tipo_serv_prof='{$tipo_serv_prof}', serv_prof='{$monto_solo4}', 
                otros_info='{$otros_info}', otros='{$monto_solo5}', subtotal2='{$monto_solo6}', subtotal1_2='{$monto_solo7}', cony_deduce_imp='{$cony_deduce_imp}', ingr_cony='{$monto_solo8}', suma_ab='{$monto_solo9}'
                WHERE id_rel_detalle_renum ='{$db->escape($id)}'";

        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "La información de la remuneración del cargo que inicia ha sido editada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó remun_mens. que inicia: ' . '.', 1);
            redirect('edit_rem_mens.php?id=' . (int)$detalles['id_rel_detalle_renum'], false);
        } else {
            $session->msg('d', ' No se pudo agregar la remuneración del cargo que inicia.');
            redirect('edit_rem_mens.php?id=' . (int)$detalles['id_rel_detalle_renum'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_rem_mens.php?id=' . (int)$detalles['id_rel_detalle_renum'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<div class="modal">
    <div class="modal-content">
        <div class="row">
            <div class="col-md-12">
                <?php echo display_msg($msg); ?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-group clearfix">
                </div>
            </div>
            <div class="panel-body">
                <strong>
                    <span class="material-symbols-outlined" style="margin-top: -40px; margin-left: -10px; color: #3a3d44; font-size: 30px;">
                        monetization_on
                    </span>
                    <p style="margin-top: -53px; margin-left: 32px; font-size: 20px;">Remuneración Mensual Neta Del Declarante por su Cargo que Inicia, así como el Ingreso del Cónyuge, Concubina o Concubinario y/o Dependientes Económicos</p>
                </strong>
                <form method="post" action="edit_rem_mens.php?id=<?php echo (int)$detalles['id_rel_detalle_renum']; ?>" class="clearfix">
                    <div id="inputsContainer" style="display:block; margin-top:15px;">
                        <div class="row">
                            <div class="col-md-8">
                                <label>I. REMUNERACIÓN MENSUAL NETA DEL DECLARANTE POR SU CARGO PÚBLICO (DEDUCE IMPUESTOS)</label>
                                <label style="font-size: 10px; margin-left: 1.5%;">(Por concepto de sueldos, honorarios, compensaciones, bonos, aguinaldos y otras prestaciones)</label>
                            </div>
                            <div class="col-md-2" style="margin-top: -8px;">
                                <div class="form-group">
                                    <label for="renum_mens">Subtotal I</label>
                                    <input type="text" class="form-control montodos" name="renum_mens" id="currency-field renum_mens" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar2();" value="<?php echo "$" . $detalles['renum_mens'] ?>">
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
                                <input class="form-control" type="text" name="nombre_act_indus" id="nombre_act_indus" value="<?php echo $detalles['nombre_act_indus'] ?>">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="act_indus" id="currency-field act_indus" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();" value="<?php echo "$" . $detalles['act_indus'] ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-left: 2%; margin-top: 10px;">
                            <label style="font-size: 12px;">II. 2 Por actividad financiera (rendimientos de contratos bancarios o de valores)</label>
                            <div class="col-md-7">
                                <label style="font-size: 12px;">(Deduce Impuestos)</label>
                                <input class="form-control" type="text" name="nombre_act_fin" id="nombre_act_fin" value="<?php echo $detalles['nombre_act_indus'] ?>">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="act_finan" id="currency-field act_finan" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();" value="<?php echo "$" . $detalles['act_finan'] ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-left: 2%; margin-top: 10px;">
                            <label style="font-size: 12px;">II. 3 Por servicios profesionales, participación en consejos, consultorías o asesorías</label>
                            <div class="col-md-7">
                                <label style="font-size: 12px;">Especifica el tipo de servicio y el contratante (Deduce Impuestos)</label>
                                <input class="form-control" type="text" name="tipo_serv_prof" id="tipo_serv_prof" value="<?php echo $detalles['tipo_serv_prof'] ?>">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="serv_prof" id="currency-field serv_prof" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();" value="<?php echo "$" . $detalles['serv_prof'] ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-left: 2%; margin-top: 8px;">
                            <label style="font-size: 12px;">II. 4 Otros (arrendamientos, regalías, sorteos, concursos, donaciones, etc.) Especifica</label>
                            <div class="col-md-7">
                                <label style="font-size: 12px;">(Deduce Impuestos)</label>
                                <input class="form-control" type="text" name="otros_info" id="otros_info" value="<?php echo $detalles['otros_info'] ?>">
                            </div>
                            <div class="col-md-2">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control monto" name="otros" id="currency-field otros" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar();" value="<?php echo "$" . $detalles['otros'] ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: 8px;">
                                <label style="font-size: 12px;">Subtotal II</label>
                                <input type="text" class="form-control montodos" name="subtotal2" id="subtotal2" readonly onchange="sumar2();" value="<?php echo "$" . $detalles['subtotal2'] ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 50px;">
                            <div class="col-md-3">
                                <label style="font-size: 12px; margin-top: -25px;">A. INGRESO MENSUAL NETO DEL DECLARANTE</label>
                            </div>
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: -35px">
                                <label style="font-size: 12px; margin-top: -10px">Suma del subtotal I y subtotal II</label>
                                <input type="text" class="form-control montotres" name="subtotal1_2" id="subtotal1_2" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar3();" readonly value="<?php echo "$" . $detalles['subtotal1_2'] ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-7">
                                <label style="font-size: 12px; margin-top: -5px;">B. INGRESO MENSUAL NETO DEL CÓNYUGE CONCUBINA O CONCUBINARIO Y/O DEPENDIENTES ECONÓMICOS (DEDUCE IMPUESTOS)</label>
                                <input class="form-control" type="text" name="cony_deduce_imp" id="cony_deduce_imp" value="<?php echo $detalles['cony_deduce_imp'] ?>">
                            </div>
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: -53px">
                                <label style="font-size: 12px;">Monto</label>
                                <input type="text" class="form-control montotres" name="ingr_cony" id="currnrency-field ingr_cony" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" onchange="sumar3();" value="<?php echo "$" . $detalles['ingr_cony'] ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 50px;">
                            <div class="col-md-7">
                                <label style="font-size: 12px;" style="margin-top: -20px;">C. TOTAL DE INGRESO MENSUAL NETO DEL DECLARANTE, CÓNYUGE, CONCUBINA, CONCUBINARIO Y/O DEPENDIENTES ECONÓMICOS</label>
                            </div>
                            <div class="col-md-2" style="margin-left: 66.5%; margin-top: -55px">
                                <label style="font-size: 12px;">Suma de A y B</label>
                                <input type="text" class="form-control" name="suma_ab" id="suma_ab" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" readonly value="<?php echo "$" . $detalles['suma_ab'] ?>">
                            </div>
                        </div>
                    </div><br>

                    <a href="rem_mens.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>
                    <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
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

        $suma_ab.value = "$" + subtotal_ab.toFixed(2); // Redondear a 2 decimales
        // sumar2();
    }

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

        $subtotal1_2.value = "$" + (subtotaldos).toFixed(2); // Redondear a 2 decimales
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

        $subtotal2.value = "$" + subtotal.toFixed(2); // Redondear a 2 decimales
        sumar2();
        sumar3();
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