<?php
$page_title = 'Principal';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel" style="background-color: #A338A8; border-radius: 15px;">
      <div class="jumbotron text-center"
        style="background: #A338A8; border-radius: 15px; border: 1px solid #A338A8;">
        <h1 style="color: white;">Página principal</h1>
        <h4 style="color: white">Sistema Interno de Control de Declaración Patrimonial de la CEDH (SICDEPA)</h4>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>