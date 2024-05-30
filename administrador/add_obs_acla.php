<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Conflicto del declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$id_rel_declaracion = find_by_id_dec((int)$id_detalle_usuario);
$id_dec = (int)$id_rel_declaracion['id_rel_declaracion'];
$tot_estudios = count_by_id_tablas('rel_detalle_estudios', 'id_rel_declaracion', $id_dec);
$tot_exp_laboral = count_by_id_tablas('rel_exp_laboral', 'id_rel_declaracion', $id_dec);
$tot_datos_pat_pub = count_by_id_tablas('rel_datos_pub_dec', 'id_rel_declaracion', $id_dec);
$tot_datos_cony = count_by_id_tablas('rel_detalle_cony_dependientes', 'id_rel_declaracion', $id_dec);
$tot_encargo_ini = count_by_id_tablas('encargo_ini_mod_conc', 'id_rel_declaracion', $id_dec);
$tot_rem_mens = count_by_id_tablas('rel_detalle_renum', 'id_rel_declaracion', $id_dec);
$tot_rem_anio = count_by_id_tablas('rel_detalle_renum_anio_ant', 'id_rel_declaracion', $id_dec);
$tot_bienes_in = count_by_id_tablas('rel_detalle_bienes_inmuebles', 'id_rel_declaracion', $id_dec);
$tot_vehi = count_by_id_tablas('rel_detalle_automotores', 'id_rel_declaracion', $id_dec);
$tot_biene_mue = count_by_id_tablas('rel_detalle_bien_mueble', 'id_rel_declaracion', $id_dec);
$tot_cuentas = count_by_id_tablas('rel_detalle_inv_cbanc', 'id_rel_declaracion', $id_dec);
$tot_adeudos = count_by_id_tablas('rel_detalle_adeudos', 'id_rel_declaracion', $id_dec);
$tot_conf = count_by_id_tablas('rel_detalle_conflicto_declarante', 'id_rel_declaracion', $id_dec);
$tot_conf_ec = count_by_id_tablas('rel_detalle_conflicto_part_econom', 'id_rel_declaracion', $id_dec);
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
        width: 60% !important;
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
if (isset($_POST['add_obs_acla'])) {
    if (empty($errors)) {

        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];
        $observaciones = $_POST['observaciones'];

        $sql = "UPDATE rel_declaracion SET observaciones='{$observaciones}', fecha_conclusion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result = $db->query($sql);
        $result2 = $db->query($sql2);

        if (($result && $db->affected_rows() === 1) && ($result2 && $db->affected_rows() === 1)) {
            $session->msg('s', "La información de las observaciones y aclaraciones ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó obs. acla.', 1);
            concluirDeclaracion($declaracion);
            redirect('admin.php', false);
        } else {
            $session->msg('d', ' No se pudo guardar la información de las observaciones y aclaraciones.');
            redirect('obs_acla.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('obs_acla.php', false);
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
                    <span class="material-symbols-outlined" style="margin-top: -40px; color: #3a3d44; font-size: 35px;">
                        person_alert
                    </span>
                    <p style="margin-top: -37px; margin-left: 40px; font-size: 20px;">Observaciones y Aclaraciones <?php echo $tot_conf_ec['total']; ?></p>
                </strong>
                <form method="post" action="add_obs_acla.php">
                    <div id="inputsContainer" style="display:block; margin-bottom: 15px;">
                        <div class="row" style="margin-top: -15px;">
                            <label style="font-size: 15px;">Deberás usar este espacio para aclarar o ampliar la información sobre cualquier asunto referido a su patrimonio, así como cualquier sugerencia o comentario sobre el formato. </label>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex flex-column justify-content-end">
                                <div class="form-group">
                                    <textarea class="form-control" name="observaciones" cols="10" rows="15"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="obs_acla.php" class="btn btn-md btn-success" title="Cerrar">
                        Cerrar
                    </a>

                    <?php if (
                        ($tot_estudios['total'] > 0) && ($tot_exp_laboral['total'] > 0) && ($tot_datos_pat_pub['total'] > 0) &&
                        ($tot_datos_cony['total'] > 0) && ($tot_encargo_ini['total'] > 0) && ($tot_rem_mens['total'] > 0) && ($tot_rem_anio['total'] > 0) &&
                        ($tot_bienes_in['total'] > 0) && ($tot_vehi['total'] > 0) && ($tot_biene_mue['total'] > 0) && ($tot_cuentas['total'] > 0) &&
                        ($tot_adeudos['total'] > 0) && ($tot_conf['total'] > 0) && ($tot_conf_ec['total'] > 0)
                    ) : ?>
                        <button type="submit" name="add_obs_acla" class="btn btn-primary btn-md" onclick="return confirmarEnvio()">Terminar Declaración</button>
                    <?php else : ?>
                        <p style="font-weight: bold; font-size: 15px; color:darkblue; text-align: center; margin-top: 2%;">El botón para concluir la declaración se mostrará una vez que hayas llenado todos los módulos de tu declaración patrimonial.</p>
                    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmarEnvio() {
        if (confirm("¿Seguro que deseas continuar? Una vez mandado el formulario no se podrá editar. Si estas seguro da clic en 'Aceptar' para concluir tu declaración.")) {
            // Si el usuario confirma, enviar el formulario
            document.getElementById("miFormulario").submit();
            return true;
        } else {
            // Si el usuario cancela, no enviar el formulario
            return false;
        }
    }
</script>
<?php include_once('layouts/footer.php'); ?>