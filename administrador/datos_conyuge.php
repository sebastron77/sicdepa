<?php
// error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Datos del Cónyuge';
require_once('includes/load.php');
?>
<?php
$user = current_user();
if ($user['user_level'] <= 2) {
    $all_detalles = find_all_conyuge();
}
if ($user['user_level'] >= 3) {
    $all_detalles2 = find_by_id_all_cony($user['id_detalle_user']);
}

$verficia_dec_ant1 = find_all_dec_conc((int)$id_detalle_usuario);
$id_last_dec = $verficia_dec_ant1['id_rel_declaracion'];
$cuenta_total = count_by_id_tablas('rel_detalle_estudios', 'id_rel_declaracion', (int)$id_last_dec);
$total = $cuenta_total['total'];

page_require_level(3);
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

if (isset($_POST['boton'])) {
    $verficia_dec_ant = find_all_dec_conc((int)$id_detalle_usuario);
    $last_dec = $verficia_dec_ant['id_rel_declaracion'];
    $total = count_by_id_tablas('rel_datos_pub_dec', 'id_rel_declaracion', (int)$last_dec);
    echo "last_dec: " . $last_dec . "\n";
    $declaracion_actual = find_by_id_dec((int)$id_detalle_usuario);
    $id_dec_actual = $declaracion_actual['id_rel_declaracion'];
    echo "id_dec_actual: " . $id_dec_actual . "\n";
    // Realizar la consulta SELECT
    $sql_select = "SELECT * FROM rel_datos_pub_dec WHERE id_rel_datos_dec_pub = '$last_dec'";
    echo $sql_select . "\n";
    $result_select = $conn->query($sql_select);
    echo "Rows: " . $result_select->num_rows . "\n\n\n";

    // Insertar los datos obtenidos en otra tabla
    if ($result_select->num_rows > 0) {
        while ($row = $result_select->fetch_assoc()) {
            // echo "- " . count($row) . " -\n";
            $col1 = $row['de_acuerdo'];
            $col2 = $row['ingresos_netos'];
            $col3 = $row['bienes_inmuebles'];
            $col4 = $row['bienes_muebles'];
            $col5 = $row['vehiculos'];
            $col6 = $row['inversiones'];
            $col7 = $row['adeudos'];

            $sql_insert = "INSERT INTO rel_datos_pub_dec (id_detalle_usuario, id_rel_declaracion, de_acuerdo, ingresos_netos, bienes_inmuebles, 
                                        bienes_muebles, vehiculos, inversiones, adeudos, fecha_creacion, estatus_detalle) 
                            VALUES ('$id_detalle_usuario', '$id_dec_actual', '$col1', '$col2', '$col3', '$col4', '$col5', '$col6', '$col7', NOW(), 1)";
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
                <span style="font-weight: bold; color: firebrick;">IMPORTANTE:</span> Si no harás ninguna modificación o adición de tus datos públicos, presiona el botón "Continuar" para que se agreguen automáticamente los datos ingresados en declaraciones anteriores a la declaración actual. Si agregarás información para que sea pública, haz clic en "Continuar" y, posteriormente en "Agregar información" registra la nueva información.
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
                    <span style="font-size: 15px;">Información del Cónyuge, Concubina o Concubinario y/o Dependientes Económicos (Situación Actual)</span>
                </strong>
                <a href="add_datos_conyuge.php" class="btn btn-info pull-right">Agregar información</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;"">
                            <th style=" width: 1%;" class="text-center">#</th>
                            <th style="width: 10%;" class="text-center">Declarante</th>
                            <th style="width: 10%;" class="text-center">Nombre dependiente económico</th>
                            <th style="width: 1%;" class="text-center">Parentesco</th>
                            <th style="width: 1%;" class="text-center">Depen. Económico</th>
                            <th style="width: 1%;" class="text-center">Fecha Creación</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($user['user_level'] >= 3) : ?>
                            <?php foreach ($all_detalles2 as $a_detalle2) : ?>
                                <?php if ($a_detalle2['ninguno'] == 0) : ?>
                                    <tr>
                                        <td class="text-center"><?php echo remove_junk(ucwords($a_detalle2['id_rel_detalle_cony_dependientes'])) ?></td>
                                        <td><?php echo remove_junk(ucwords($a_detalle2['nombre'] . ' ' . $a_detalle2['apellido_paterno'] . ' ' . $a_detalle2['apellido_materno'])) ?></td>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['nombre_completo']) ?></td>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['parentesco']) ?></td>
                                        <?php if ($a_detalle2['dependiente_econ'] == 0) : ?>
                                            <td class="text-center">No</td>
                                        <?php endif; ?>
                                        <?php if ($a_detalle2['dependiente_econ'] == 1) : ?>
                                            <td class="text-center">Sí</td>
                                        <?php endif; ?>
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['fecha_creacion']) ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="edit_exp_laboral.php?id=<?php echo (int)$a_detalle2['id_rel_detalle_cony_dependientes']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                    <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($a_detalle2['ninguno'] == 1) : ?>
                                    <tr>
                                        <td class="text-center"><?php echo $a_detalle2['id_rel_detalle_cony_dependientes'] ?></td>
                                        <td><?php echo remove_junk(ucwords($a_detalle2['nombre'] . ' ' . $a_detalle2['apellido_paterno'] . ' ' . $a_detalle2['apellido_materno'])) ?></td>
                                        <td class="text-center"><?php echo '-' ?></td>
                                        <td class="text-center"><?php echo '-' ?></td>
                                        <td class="text-center"><?php echo '-' ?></td>
                                        <!-- <td class="text-center"><?php echo '-' ?></td> -->
                                        <td class="text-center"><?php echo remove_junk($a_detalle2['fecha_creacion']) ?></td>
                                        <td class="text-center">
                                            <!-- <div class="btn-group">
                                                <a href="edit_exp_laboral.php?id=<?php echo (int)$a_detalle2['id_rel_exp_lab']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                    <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                                </a>
                                            </div> -->
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($user['user_level'] <= 2) : ?>
                            <?php foreach ($all_detalles as $a_detalle) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['id_rel_detalle_cony_dependientes'])) ?></td>
                                    <td><?php echo remove_junk(ucwords($a_detalle['nombre'] . ' ' . $a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['nombre_completo']) ?></td>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['parentesco']) ?></td>
                                    <?php if ($a_detalle['dependiente_econ'] == 0) : ?>
                                        <td class="text-center">No</td>
                                    <?php endif; ?>
                                    <?php if ($a_detalle['dependiente_econ'] == 1) : ?>
                                        <td class="text-center">Sí</td>
                                    <?php endif; ?>
                                    <td class="text-center"><?php echo remove_junk($a_detalle['fecha_creacion']) ?></td>
                                    <td class="text-center">
                                        <?php if ($a_detalle['ninguno'] == 0) : ?>
                                            <div class="btn-group">
                                                <a href="edit_datos_conyuge.php?id=<?php echo (int)$a_detalle['id_rel_detalle_cony_dependientes']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                    <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>