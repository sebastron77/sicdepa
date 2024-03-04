<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Datos Curriculares del Declarante';
require_once('includes/load.php');

$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];
$cat_periodos = find_all('cat_periodos_cursados');
$cat_estatus = find_all('cat_estatus_estudios');
$cat_documentos = find_all('cat_documento_obtenido');
$entidades = find_all('cat_entidad_fed');
$municipios = find_all('cat_municipios');
$id_cat_esc = (int) $_GET['id'];
?>

<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['documento_otro'])) {

    if (empty($errors)) {
        // $id_cat_escolaridad = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $id = remove_junk($db->escape($_POST['id_cat_escolaridad']));
        $inst_educativa = remove_junk($db->escape($_POST['inst_educativa']));
        $id_cat_periodo_cursado = remove_junk($db->escape($_POST['id_cat_periodo_cursado']));
        $id_cat_documento_obtenido = remove_junk($db->escape($_POST['id_cat_documento_obtenido']));
        $ubic_inst = remove_junk($db->escape($_POST['ubic_inst']));
        $id_cat_ent_fed = remove_junk($db->escape($_POST['id_cat_ent_fed']));
        $id_cat_mun = remove_junk($db->escape($_POST['id_cat_mun']));
        $carrera_area_con = remove_junk($db->escape($_POST['carrera_area_con']));
        $id_cat_estatus_estudios = remove_junk($db->escape($_POST['id_cat_estatus_estudios']));
        $num_ced_prof = remove_junk($db->escape($_POST['num_ced_prof']));

        $query = "INSERT INTO rel_detalle_estudios (";
        $query .= "id_detalle_usuario, id_cat_escolaridad, inst_educativa, id_cat_periodo_cursado, id_cat_documento_obtenido, ubic_inst, id_cat_ent_fed, 
                    id_cat_mun, carrera_area_con, id_cat_estatus_estudios, num_ced_prof, fecha_creacion, estatus_detalle";
        $query .= ") VALUES (";
        $query .= " '{$id_detalle_usuario}', '{$id}', '{$inst_educativa}', '{$id_cat_periodo_cursado}', '{$id_cat_documento_obtenido}', 
                    '{$ubic_inst}', '{$id_cat_ent_fed}', '{$id_cat_mun}', '{$carrera_area_con}', '{$id_cat_estatus_estudios}', '{$num_ced_prof}', NOW(), '1'";
        $query .= ")";

        if ($db->query($query)) {
            //sucess
            $session->msg('s', "La información curricular ha sido agregada con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó su escolaridad: ' . $id . '.', 1);
            // redirect('datos_curri_declarante.php', false);

?>
            <script language="javascript">
                parent.location.reload();
            </script>
<?php } else {
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
            <?php if ($id_cat_esc == 4) { ?>
                <p style="margin-top: -10px; font-weight: bold;">SI ES CARRERA TÉCNICA ESPECIFIQUE:</p>
            <?php }
            if ($id_cat_esc == 5) { ?>
                <p style="margin-top: -10px; font-weight: bold;">SI ES LICENCIATURA, MAESTRÍA O DIPLOMADO ESPECIFIQUE:</p>
            <?php }
            if ($id_cat_esc == 6) { ?>
                <p style="margin-top: -10px; font-weight: bold;">SI ES DIPLOMADO ESPECIFIQUE:</p>
            <?php } ?>
            <form method="post" action="documento_otro.php?id=<?php echo $id_cat_esc; ?>">
                <div class="row">
                    <input type="hidden" class="form-control" id="id_cat_escolaridad" name="id_cat_escolaridad" value="<?php echo $id_cat_esc; ?>">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ubic_inst">Lugar donde se ubica la Institución Educativa</label>
                            <input type="text" class="form-control" name="ubic_inst" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_cat_ent_fed">Entidad Federativa</label>
                            <select class="form-control" id="id_cat_ent_fed" name="id_cat_ent_fed">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($entidades as $entidad) : ?>
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
                                <?php foreach ($municipios as $municipio) : ?>
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
                            <input type="text" class="form-control" name="inst_educativa" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="carrera_area_con">Carrera o área del conocimiento</label>
                            <input type="text" class="form-control" name="carrera_area_con" required>
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
                            <input type="text" class="form-control" name="num_ced_prof" required>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <button type="submit" name="documento_otro" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>