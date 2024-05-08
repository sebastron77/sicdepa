<?php
$page_title = 'Hacer públicos los datos patrimoniales';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_detalle_usuario = $user['id_detalle_user'];

if ($user['user_level'] <= 2) {
    $all_detalles = find_all_pub_dec();
}
if ($user['user_level'] >= 3) {
    $all_detalles2 = find_by_id_pub_dec($user['id_detalle_user']);
}
page_require_level(3);

$verficia_dec_ant1 = find_all_dec_conc((int)$id_detalle_usuario);
$id_last_dec = $verficia_dec_ant1['id_rel_declaracion'];
$cuenta_total = count_by_id_tablas('rel_detalle_estudios', 'id_rel_declaracion', (int)$id_last_dec);
$total = $cuenta_total['total'];
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
                    <span style="font-size: 15px;">Datos públicos de la declaración patrimonial</span>
                </strong>
                <a href="add_rel_datos_pub_dec.php" class="btn btn-info pull-right">Agregar información</a>
            </div>

            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;"">
                            <th style=" width: 1%;" class="text-center">#</th>
                            <th style="width: 5%;" class="text-center">Declarante</th>
                            <th style="width: 5%;" class="text-center">Público</th>
                            <th style="width: 5%;" class="text-center">Ingresos Netos</th>
                            <th style="width: 5%;" class="text-center">Bienes Inmuebles</th>
                            <th style="width: 5%;" class="text-center">Bienes Muebles</th>
                            <th style="width: 5%;" class="text-center">Vehículos</th>
                            <th style="width: 5%;" class="text-center">Inversiones</th>
                            <th style="width: 5%;" class="text-center">Adeudos</th>
                            <th style="width: 5%;" class="text-center">Fecha Creación</th>
                            <th style="width: 1%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($user['user_level'] >= 3) : ?>
                            <?php foreach ($all_detalles2 as $a_detalle2) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle2['id_rel_datos_dec_pub'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle2['nombre'] . ' ' . $a_detalle2['apellido_paterno'] . ' ' . $a_detalle2['apellido_materno'])) ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle2['de_acuerdo'] == 0) ? 'No' : 'Sí';
                                                            echo $res; ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle2['ingresos_netos'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle2['bienes_inmuebles'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle2['bienes_muebles'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle2['vehiculos'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle2['inversiones'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle2['adeudos'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php echo $a_detalle2['fecha_creacion'] ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_rel_datos_pub_dec.php?id=<?php echo (int)$a_detalle2['id_rel_datos_dec_pub']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($user['user_level'] <= 2) : ?>
                            <?php foreach ($all_detalles as $a_detalle) : ?>
                                <tr>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['id_rel_datos_dec_pub'])) ?></td>
                                    <td class="text-center"><?php echo remove_junk(ucwords($a_detalle['nombre'] . ' ' . $a_detalle['apellido_paterno'] . ' ' . $a_detalle['apellido_materno'])) ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle['de_acuerdo'] == 0) ? 'No' : 'Sí';
                                                            echo $res; ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle['ingresos_netos'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle['bienes_inmuebles'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle['bienes_muebles'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle['vehiculos'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle['inversiones'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php $res = ($a_detalle['adeudos'] == 0) ?  'No' :  'Sí';
                                                            echo $res;  ?></td>
                                    <td class="text-center"><?php echo $a_detalle['fecha_creacion'] ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="edit_rel_datos_pub_dec.php?id=<?php echo (int)$a_detalle['id_rel_datos_dec_pub']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 32px; width: 32px;">
                                                <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 2px; margin-left: -3px;">edit</span>
                                            </a>
                                        </div>
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