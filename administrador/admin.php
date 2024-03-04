<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'SICDEPA';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
	page_require_level(2);
}
if ($nivel_user == 7) {
	page_require_level_exacto(7);
}
if ($nivel_user > 2 && $nivel_user < 7) :
	redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 21) :
	redirect('home.php');
endif;
if ($nivel_user == 21) :
	page_require_level_exacto(21);
endif;
?>
<?php
$year = date("Y");
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
	<div class="col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
</div>

<div class="container-fluid">
	<div class="full-box tile-container">
		<a style="text-decoration:none;" <?php if ($nivel_user <= 2 || $nivel_user == 7) : ?> href="areas.php" <?php endif; ?> class="tile">
			<div class="tile-tittle">Áreas</div>
			<div class="tile-icon">
				<span class="material-symbols-outlined" style="font-size:95px;">domain</span>
				<p> <?php echo $c_areas['total']; ?> Registradas</p>
			</div>
		</a>


		<a style="text-decoration:none;" <?php if ($nivel_user <= 2 || $nivel_user == 7) : ?> href="users.php" <?php endif; ?> class="tile">
			<div class="tile-tittle">Trabajadores</div>
			<div class="tile-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="100px" height="100px" fill="#455a64" class="bi bi-person-video3" viewBox="0 0 16 16">
					<path d="M14 9.5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm-6 5.7c0 .8.8.8.8.8h6.4s.8 0 .8-.8-.8-3.2-4-3.2-4 2.4-4 3.2Z" />
					<path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h5.243c.122-.326.295-.668.526-1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v7.81c.353.23.656.496.91.783.059-.187.09-.386.09-.593V4a2 2 0 0 0-2-2H2Z" />
				</svg>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_user['total']; ?> Registrados</p>
			</div>
		</a>
	</div>
</div>

<?php include_once('layouts/footer.php'); ?>