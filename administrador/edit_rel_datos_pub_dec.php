<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Editar Datos Patrimoniales Públicos';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$id_rel_declaracion = find_by_id_dec((int)$id_detalle_usuario);
$e_detalle = find_by_id('rel_datos_pub_dec', $_GET['id'], 'id_rel_datos_dec_pub');
page_require_level(3);
?>

<?php
if (isset($_POST['update'])) {

    if (empty($errors)) {
        $id = (int)$e_detalle['id_rel_datos_dec_pub'];
        $declaracion = (int)$id_rel_declaracion['id_rel_declaracion'];
        $de_acuerdo = remove_junk($db->escape($_POST['de_acuerdo']));
        $ingresos_netos = $db->escape($_POST['ingresos_netos']);
        $bienes_inmuebles = $db->escape($_POST['bienes_inmuebles']);
        $bienes_muebles = $db->escape($_POST['bienes_muebles']);
        $vehiculos = $db->escape($_POST['vehiculos']);
        $inversiones = $db->escape($_POST['inversiones']);
        $adeudos = $db->escape($_POST['adeudos']);

        $sql = "UPDATE rel_datos_pub_dec SET id_rel_declaracion='{$declaracion}', de_acuerdo='{$de_acuerdo}', ingresos_netos='{$ingresos_netos}', 
                bienes_inmuebles='{$bienes_inmuebles}', bienes_muebles='{$bienes_muebles}', vehiculos='{$vehiculos}', inversiones='{$inversiones}', 
                adeudos='{$adeudos}' WHERE id_rel_datos_dec_pub = '{$db->escape($id)}'";
        $result = $db->query($sql);

        $sql2 = "UPDATE bandera_continuacion SET fecha_actualizacion = NOW() WHERE id_rel_declaracion ='{$db->escape($declaracion)}'";
        $result2 = $db->query($sql2);

        if (($result && $db->affected_rows() === 1) && ($result2 && $db->affected_rows() === 1)) {
            //sucess
            $session->msg('s', "La información de hacer datos patrimoniales públicos ha sido editada con éxito. Continúa con Datos del cónyuge." . $sql);
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó datos_pub_dec.: ' . $id . '.', 2);
            redirect('datos_conyuge.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo editar la información.');
            redirect('edit_rel_datos_pub_dec.php?id=' . (int)$e_detalle['id_rel_datos_dec_pub'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_rel_datos_pub_dec.php?id=' . (int)$e_detalle['id_rel_datos_dec_pub'], false);
    }
}
?>
<?php
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="form-group clearfix">
    <a href="rel_datos_pub_dec.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
        Regresar
    </a>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>
            <span class="material-symbols-outlined" style="color: #3a3d44">
                school
            </span>
            <p style="margin-top: -22px; margin-left: 32px;">Editar si esta de acuerdo en hacer públicos sus datos patrimoniales.</p>
        </strong>
    </div>

    <div class="panel-body">
        <form method="post" action="edit_rel_datos_pub_dec.php?id=<?php echo (int)$e_detalle['id_rel_datos_dec_pub']; ?>">
            <div class="row" style="margin-left:30%">
                <div class="col-md-6">
                    <input type="checkbox" name="ingresos_netos" value="1" <?php if($e_detalle['ingresos_netos'] == '1') echo "checked";?>> En ingresos netos, los correspondientes a los recibidos por actividad industrial y/o comercial, financiera y otros, así como el monto total de los ingresos considerados a los antes citados.<br><br>
                    <input type="checkbox" name="bienes_inmuebles" value="1" <?php if($e_detalle['bienes_inmuebles'] == '1') echo "checked";?>> En bienes inmuebles, el valor de la contra prestación y moneda.<br><br>
                    <input type="checkbox" name="bienes_muebles" value="1" <?php if($e_detalle['bienes_muebles'] == '1') echo "checked";?>> En bienes muebles, el valor de la contraprestación y moneda.<br><br>
                    <input type="checkbox" name="vehiculos" value="1" <?php if($e_detalle['vehiculos'] == '1') echo "checked";?>> En vehículos, el valor de la contraprestación y moneda. <br><br>
                    <input type="checkbox" name="inversiones" value="1" <?php if($e_detalle['inversiones'] == '1') echo "checked";?>> En inversiones, cuentas bancarias y otro tipo de valores, el saldo.<br><br>
                    <input type="checkbox" name="adeudos" value="1" <?php if($e_detalle['adeudos'] == '1') echo "checked";?>> En adeudos, el monto original, el saldo y el monto de los pagos realizados.<br>
                </div>
            </div><br>
            <button type="submit" name="update" class="btn btn-primary btn-md">Guardar</button>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>