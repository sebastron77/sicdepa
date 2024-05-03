<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Datos Curriculares';
require_once('includes/load.php');
?>
<?php
page_require_level(3);
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];

$verficia_dec_ant1 = find_all_dec_conc((int)$id_detalle_usuario);
$id_last_dec = $verficia_dec_ant1['id_rel_declaracion'];
$cuenta_total = count_by_id_tablas('rel_detalle_estudios', 'id_rel_declaracion', (int)$id_last_dec);
$total = $cuenta_total['total'];
// echo $total['total'];

if ($user['user_level'] <= 2) {
    $all_detalles = find_all_det_estudios();
}
if ($user['user_level'] >= 3) {
    $all_detalles = find_by_id_estudios($user['id_detalle_user']);
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<?php
$servername = "localhost";
$username = "suigcedh";
$password = "9DvkVuZ915H!";
$dbname = "sicdepa";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Si se hace clic en el botón
if (isset($_POST['boton'])) {
    $verficia_dec_ant = find_all_dec_conc((int)$id_detalle_usuario);
    $last_dec = $verficia_dec_ant['id_rel_declaracion'];
    $total = count_by_id_tablas('rel_detalle_estudios', 'id_rel_declaracion', (int)$last_dec);
    // echo "last_dec: " . $last_dec . "\n";
    $declaracion_actual = find_by_id_dec((int)$id_detalle_usuario);
    $id_dec_actual = $declaracion_actual['id_rel_declaracion'];
    // echo "id_dec_actual: " . $id_dec_actual . "\n";
    // Realizar la consulta SELECT
    $sql_select = "SELECT * FROM rel_detalle_estudios WHERE id_rel_declaracion = '$last_dec'";
    // echo $sql_select . "\n";
    $result_select = $conn->query($sql_select);
    // echo "Rows: " . $result_select->num_rows . "\n\n\n";

    // Insertar los datos obtenidos en otra tabla
    if ($result_select->num_rows > 0) {
        while ($row = $result_select->fetch_assoc()) {
            // echo "- " . count($row) . " -\n";
            $col1 = $row['id_cat_escolaridad'];
            $col2 = $row['inst_educativa'];
            $col3 = $row['id_cat_periodo_cursado'];
            $col4 = $row['id_cat_documento_obtenido'];
            $col5 = $row['ubic_inst'];
            $col6 = $row['id_cat_ent_fed'];
            $col7 = $row['id_cat_mun'];
            $col8 = $row['carrera_area_con'];
            $col9 = $row['id_cat_estatus_estudios'];
            $col10 = $row['num_ced_prof'];

            $sql_insert = "INSERT INTO rel_detalle_estudios (id_detalle_usuario, id_rel_declaracion, id_cat_escolaridad, inst_educativa, 
                                        id_cat_periodo_cursado, id_cat_documento_obtenido, ubic_inst, id_cat_ent_fed, id_cat_mun, carrera_area_con, 
                                        id_cat_estatus_estudios, num_ced_prof, fecha_creacion, estatus_detalle) 
                            VALUES ('$id_detalle_usuario', '$id_dec_actual', '$col1', '$col2', '$col3', '$col4', '$col5', '$col6', '$col7', '$col8', '$col9', 
                                    '$col10', NOW(), 1)";
            // Ejecutar la consulta de inserción
            echo "-----------------------------------------------------------------------------------------------------------";
            echo "\n\n" . $sql_insert . "\n\n";
            echo "-----------------------------------------------------------------------------------------------------------";
            $conn->query($sql_insert);
            // echo $conn->query($sql_insert);
            header("Location: $_SERVER[PHP_SELF]");
        }
        echo "Datos insertados correctamente.";
        exit;
    } else {
        echo "No se encontraron datos para insertar.";
    }
}
?>
<?php if ($total >= 1) : ?>
    <div class="row">
        <div class="col-md-1">
            <button class="btn btn-success pull-left" id="boton" style="width: 100%; height: 50px; font-size: 14px; font-weight: 500; margin-top: -15px; margin-bottom: 10px; margin-left: 1%;">
                <p>Continuar</p>
                <p style="margin-top: -15px;"><span class="material-symbols-outlined" style="color: white;">
                        arrow_right_alt
                    </span></p>
            </button>
        </div>

        <div class="col-md-11" style="margin-top: -16px;">
            <p class="alert alert-dark" role="alert" style="font-size: 13px; font-weight:600">
                <span style="font-weight: bold; color: firebrick;">IMPORTANTE:</span> Si no harás ninguna modificación o adición de tus datos curriculares, presiona el botón "Continuar" para que se agreguen automáticamente los datos ingresados en declaraciones anteriores a la declaración actual. Si agregarás información curricular nueva, haz clic en "Continuar" y, posteriormente en "Agregar información" registra la nueva información.
            </p>
        </div>
    </div>
<?php endif; ?>
<!-- Script para manejar el clic del botón -->
<script>
    document.getElementById("boton").addEventListener("click", function() {
        // Realizar una solicitud AJAX al servidor al hacer clic en el botón
        var xhr = new XMLHttpRequest();
        xhr.open("POST", window.location.href, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                // Manejar la respuesta del servidor (opcional)
                console.log(this.responseText);
                // Recargar la página después de la inserción
                location.reload();
            }
        };
        // Enviar la solicitud AJAX con un parámetro "boton"
        xhr.send("boton=1");
    });
</script>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span style="font-size: 15px;">Información Curricular</span>
                </strong>
                <a href="add_datos_curri_declarante.php" class="btn btn-info pull-right">Agregar información</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;"">
                            <th style=" width: 1%;" class="text-center">#</th>
                            <th style="width: 10%;" class="text-center">Nombre Completo</th>
                            <th style="width: 5%;" class="text-center">Escolaridad</th>
                            <th style="width: 5%;" class="text-center">Estatus</th>
                            <th style="width: 5%;" class="text-center">Documento Obtenido</th>
                            <th style="width: 1%;" class="text-center">Fecha Creacion</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_detalles as $a_detalle) : ?>
                            <tr>
                                <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['id_rel_detalle_estudios'])) ?></td>
                                <td><?php echo remove_junk(ucwords($a_detalle['nombre'] . ' ' . $a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                                <td><?php echo remove_junk($a_detalle['escolaridad']) ?></td>
                                <td><?php echo remove_junk($a_detalle['estatus_est']) ?></td>
                                <td><?php echo remove_junk($a_detalle['doc_obt']) ?></td>
                                <td class="text-center"><?php echo remove_junk($a_detalle['fecha_creacion']) ?></td>
                                <!-- <td class="text-center">
                                    <?php if ($a_detalle['estatus_detalle'] == '1') : ?>
                                        <span class="label label-success"><?php echo "Activo"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                                    <?php endif; ?>
                                </td> -->
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="edit_datos_curri_declarante.php?id=<?php echo (int)$a_detalle['id_rel_detalle_estudios']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                            <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
<?php
// Cerrar la conexión a la base de datos al finalizar
$conn->close();
?>