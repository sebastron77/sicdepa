<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'SICDEPA';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_detalle_usuario = $user['id_detalle_user'];
$declaracion = find_by_id_dec((int)$id_detalle_usuario);
$bandera = find_by_id_bandera((int)$declaracion['id_rel_declaracion']);
$all_declaraciones = find_by_id_all_dec((int)$id_detalle_usuario);
page_require_level(3);
?>
<script>
	function generarPDF(id) {
		// Realizar una solicitud AJAX al servidor al hacer clic en el botón
		var xhr = new XMLHttpRequest();
		var declaracionID = id;
		xhr.open("GET", "generar_pdf.php?id=" + declaracionID, true);
		xhr.responseType = "blob"; // La respuesta será un archivo binario (el PDF)
		xhr.onload = function() {
			if (this.status === 200) {
				// Crear un enlace para descargar el PDF
				var blob = this.response;
				var link = document.createElement("a");
				link.href = window.URL.createObjectURL(blob);
				link.download = "datos.pdf"; // Nombre del archivo PDF
				document.body.appendChild(link);
				link.click();
				document.body.removeChild(link);
			}
		};
		xhr.send();
	}
</script>
<?php include_once('layouts/header.php'); ?>
<style>
	/* Estilos generales */
	/* Contenedor de pestañas */
	.tabs {
		display: flex;
		cursor: pointer;
	}

	/* Pestañas */
	.tab {
		flex: 1;
		padding: 10px;
		text-align: center;
		/* border: 1px solid #ccc; */
		background-color: #f4f4f4;
		transition: background-color 0.3s;
	}

	/* Pestaña activa */
	.tab.active {
		background-color: #ddd;
	}

	/* Contenedor de contenido */
	.content {
		border: 1px solid #ccc;
		padding: 20px;
		display: none;
	}

	/* Contenido activo */
	.content.active {
		display: block;
	}
</style>

<div class="row">
	<div class="col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
</div>

<div class="panel panel-default" style="width: 80%; margin-left: 10%">
	<div class="panel-body">
		<div class="tabs">
			<div class="tab active" data-tab="1">Declaraciones pendientes</div>
			<div class="tab" data-tab="2">Declaraciones presentadas</div>
		</div>

		<div id="content-1" class="content active">
			<table class="table table-bordered">
				<thead>
					<tr style="height: 10px;">
						<th style=" width: 15%;" class="text-center">Declaración</th>
						<th style=" width: 15%;" class="text-center">Fecha de encargo/ejercicio</th>
						<th style="width: 5%;" class="text-center">Presentar</th>
					</tr>
				</thead>
				<tbody style="background-color: #EDF8FE;">
					<td class="text-center">Nueva Declaración</td>
					<td class="text-center"></td>
					<td class="text-center">
						<?php if ($declaracion['concluida'] == 1) : ?>
							<a href="add_rel_declaracion.php" class="btn btn-success btn-md" title="Presentar" data-toggle="tooltip">Presentar</a>
						<?php endif; ?>
						<?php if ($declaracion['concluida'] == 0) : ?>
							<a href="<?php echo $bandera['ultimo_archivo']; ?>" class="btn btn-success btn-md" title="Continuar" data-toggle="tooltip">Continuar</a>
						<?php endif; ?>
					</td>
				</tbody>
			</table>
		</div>

		<div id="content-2" class="content">
			<table class="datatable table table-bordered table-striped">
				<thead>
					<tr style="height: 10px;">
						<th style=" width: 15%;" class="text-center">Tipo de Declaración</th>
						<th style=" width: 15%;" class="text-center">Fecha de encargo/ejercicio</th>
						<th style="width: 5%;" class="text-center">Fecha de presentación</th>
						<th style="width: 5%;" class="text-center">Delcaración</th>
						<th style="width: 5%;" class="text-center">Acuse</th>
						<th style="width: 5%;" class="text-center">Nota aclaratoria</th>
					</tr>
				</thead>
				<tbody style="background-color: #EDF8FE;">
					<?php foreach ($all_declaraciones as $a_dec) : ?>
						<tr>
							<td class="text-center">
								<?php
								if ($a_dec['tipo_declaracion'] == 1) {
									echo 'Inicial';
								} elseif ($a_dec['tipo_declaracion'] == 2) {
									echo 'Modificación';
								} elseif ($a_dec['tipo_declaracion'] == 3) {
									echo 'Conclusión';
								}
								?>
							</td>
							<td class="text-center"><?php echo $a_dec['periodo'] ?></td>
							<td class="text-center"><?php echo $a_dec['fecha_actualizacion'] ?>
								<!-- Sería la fecha de la última actualización de la declaración-->
							<td class="text-center">
								<button id="descargar-pdf" data-declaracion-id="<?php echo $a_dec['id_rel_declaracion'] ?>" onclick="generarPDF(<?php echo $a_dec['id_rel_declaracion'] ?>)">
									<span class="material-symbols-outlined" style="color:#349491">description</span>
								</button>
							</td>
							<td class="text-center">
								<button id="descargar-pdf1">
									<span class="material-symbols-outlined" style="color:#CD282E">description</span>
								</button>
							</td>
							<td class="text-center">
								<button id="descargar-pdf2">
									<span class="material-symbols-outlined">description</span>
								</button>
							</td>
						</tr>

					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	// Obtener todas las pestañas
	const tabs = document.querySelectorAll('.tab');

	// Obtener todo el contenido
	const contents = document.querySelectorAll('.content');

	// Función para manejar el cambio de pestañas
	function changeTab(event) {
		// Desactivar todas las pestañas
		tabs.forEach(tab => tab.classList.remove('active'));
		// Desactivar todo el contenido
		contents.forEach(content => content.classList.remove('active'));
		// Activar la pestaña seleccionada
		event.target.classList.add('active');
		// Mostrar el contenido correspondiente
		const tabId = event.target.getAttribute('data-tab');
		document.getElementById(`content-${tabId}`).classList.add('active');
	}

	// Añadir evento de clic a todas las pestañas
	tabs.forEach(tab => {
		tab.addEventListener('click', changeTab);
	});
</script>
<?php include_once('layouts/footer.php'); ?>