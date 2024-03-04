<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos Curriculares del Declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$cat_periodos = find_all('cat_periodos_cursados');
$cat_estatus = find_all('cat_estatus_estudios');
$cat_documentos = find_all('cat_documento_obtenido');
$id_cat_esc = (int) $_GET['id'];
?>

<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['documento_prim_sec_bac'])) {

    if (empty($errors)) {
        // $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id = remove_junk($db->escape($_POST['id_cat_escolaridad']));;
        $inst_educativa = remove_junk($db->escape($_POST['inst_educativa']));
        $id_cat_periodo_cursado = remove_junk($db->escape($_POST['id_cat_periodo_cursado']));
        $id_cat_documento_obtenido = remove_junk($db->escape($_POST['id_cat_documento_obtenido']));
        $id_cat_estatus_estudios = remove_junk($db->escape($_POST['id_cat_estatus_estudios']));

        $query = "INSERT INTO rel_detalle_estudios (";
        $query .= "id_detalle_usuario, id_cat_escolaridad, inst_educativa, id_cat_periodo_cursado, id_cat_documento_obtenido, id_cat_estatus_estudios, 
                    fecha_creacion, estatus_detalle";
        $query .= ") VALUES (";
        $query .= " '{$id_detalle_usuario}', '{$id}', '{$inst_educativa}', '{$id_cat_periodo_cursado}', '{$id_cat_documento_obtenido}',     
                    '{$id_cat_estatus_estudios}', NOW(), '1'";
        $query .= ")";

        if ($db->query($query)) {
            //sucess
            $session->msg('s', "La información curricular ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó su escolaridad: ' . $id . '.', 1);
            redirect('datos_curri_declarante.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar la información.');
            redirect('add_datos_curri_declarante.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_datos_curri_declarante.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8'); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<link rel="stylesheet" href="libs/css/main.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://harvesthq.github.io/chosen/chosen.css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-body" style="margin-top: 2%;">
            <p style="margin-top: -10px; font-weight: bold;">SI ES PRIMARIA, SECUNDARIA O BACHILLERATO ESPECIFIQUE:</p>
            <form method="post" action="documento_prim_sec_bac.php?id=<?php echo $id_cat_esc; ?>">
                <div class="row">
                    <input type="hidden" class="form-control" id="id_cat_escolaridad" name="id_cat_escolaridad" value="<?php echo $id_cat_esc; ?>">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="inst_educativa">Institución Educativa</label>
                            <input type="text" class="form-control" name="inst_educativa" required>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_periodo_cursado">Periodos Cursados</label>
                            <select class="form-control" id="id_cat_periodo_cursado" name="id_cat_periodo_cursado">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_periodos as $cat_periodo) : ?>
                                    <option value="<?php echo $cat_periodo['id_cat_periodo_cursado']; ?>"><?php echo ucwords($cat_periodo['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_documento_obtenido">Documento Obtenido</label>
                            <select class="form-control" id="id_cat_documento_obtenido" name="id_cat_documento_obtenido">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($cat_documentos as $cat_documento) : ?>
                                    <option value="<?php echo $cat_documento['id_cat_documento_obtenido']; ?>"><?php echo ucwords($cat_documento['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <button type="submit" name="documento_prim_sec_bac" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>