<ul>
    <li style="margin-top: 13px;">
        <a href="admin.php" style="left: 21px; top:2px">
            <svg style="width:22px;height:22px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z" />
            </svg>

            <span style="position: absolute; top: 50%; left: 50%; margin:-11px 0 0 -85px;">Declaraciones</span>
        </a>
    </li>
    <?php
    $user = current_user();
    $id_detalle_usuario = $user['id_detalle_user'];
    $id_rel_declaracion = find_by_id_dec((int)$id_detalle_usuario);
    ?>
    <?php if ($id_rel_declaracion['concluida'] == 0) : ?>
        <li style="margin-bottom: 13px; margin-left: 5px;">
            <a href="#" class="submenu-toggle" style="left: 18px; top:18px">
                <svg style="width:22px;height:22px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                </svg>

                <span style="position: absolute; top: 50%; left: 50%; margin:-11px 0 0 -85px;">Información</span>
            </a>
            <ul style="margin-top:25px" class="nav submenu">
                <li><a href="detalles_usuario.php">Datos Generales</a></li>
                <li><a href="datos_curri_declarante.php">Datos Curriculares</a></li>
                <li><a href="exp_laboral.php">Experiencia Laboral</a></li>
                <li><a href="rel_datos_pub_dec.php">Datos patrimoniales públicos</a></li>
                <li><a href="datos_conyuge.php">Datos Cónyuge</a></li>
                <li><a href="encargo_inicia.php">Encargo Inicia</a></li>
                <li><a href="rem_mens.php">Remuneración Mensual Declarante</a></li>
                <li><a href="rem_anio_ant.php">Remuneración Año Anterior</a></li>
                <li><a href="bienes_inmuebles.php">Bienes Inmuebles</a></li>
                <li><a href="vehiculos.php">Vehículos del declarante</a></li>
                <li><a href="bienes_muebles.php">Bienes muebles</a></li>
                <li><a href="cuentas.php">Cuentas bancarias e inversiones</a></li>
                <li><a href="adeudos.php">Adeudos del Declarante</a></li>
                <li><a href="conflicto.php">Posible conflictos de intereses</a></li>
                <li><a href="conflicto_econ.php">Posible conflictos de intereses económicos</a></li>
                <li><a href="obs_acla.php">Observaciones y aclaraciones</a></li>
            </ul>
        </li>
    <?php endif; ?>
</ul>