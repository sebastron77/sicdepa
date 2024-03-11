<?php
require_once('load.php');

/*--------------------------------------------------------------*/
/* Funcion para encontrar en una tabla toda la informacion
/*--------------------------------------------------------------*/
function find_all($table)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table));
  }
}

function find_all_order($table, $order)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table) . " ORDER BY " . $db->escape($order));
  }
}

function find_all_det_estudios()
{
  $sql = "SELECT de.id_rel_detalle_estudios, de.id_detalle_usuario, esc.descripcion as escolaridad, de.inst_educativa, pc.descripcion as periodo_cursado,
          dob.descripcion as doc_obt, de.ubic_inst, ef.descripcion as ent_fed, m.descripcion as municipio, de.carrera_area_con, ee.descripcion as estatus_est, de.num_ced_prof, us.nombre, us.apellido_paterno, us.apellido_materno, de.estatus_detalle
          FROM rel_detalle_estudios de
          LEFT JOIN cat_escolaridad esc
          ON de.id_cat_escolaridad = esc.id_cat_escolaridad
          LEFT JOIN cat_periodos_cursados pc
          ON de.id_cat_periodo_cursado = pc.id_cat_periodo_cursado
          LEFT JOIN cat_documento_obtenido dob
          ON de.id_cat_documento_obtenido = dob.id_cat_documento_obtenido 
          LEFT JOIN cat_entidad_fed ef
          ON de.id_cat_ent_fed = ef.id_cat_ent_fed
          LEFT JOIN cat_estatus_estudios ee
          ON de.id_cat_estatus_estudios = ee.id_cat_estatus_estudios
          LEFT JOIN cat_municipios m
          ON de.id_cat_mun = m.id_cat_mun
          LEFT JOIN detalles_usuario us
          ON de.id_detalle_usuario = us.id_det_usuario
          ORDER BY de.id_detalle_usuario ASC";
  $result = find_by_sql($sql);
  return $result;
}

function find_all_exp_laboral()
{
  $sql = "SELECT el.id_rel_exp_lab, el.id_detalle_usuario, el.ninguno, el.id_cat_sector, cs.descripcion as sector, el.id_cat_poder, cp.descripcion as poder, 
          el.id_cat_ambito, ca.descripcion as ambito, el.nombre_inst_empresa, el.unidad_admin_area, el.puesto_cargo, el.funcion_principal, el.ingreso, 
          el.egreso, du.nombre, du.apellido_paterno, du.apellido_materno, el.fecha_creacion
          FROM rel_exp_laboral el
          LEFT JOIN cat_sector cs
          ON el.id_cat_sector = cs.id_cat_sector
          LEFT JOIN cat_poder cp
          ON el.id_cat_poder = cp.id_cat_poder
          LEFT JOIN cat_ambito ca
          ON el.id_cat_ambito = ca.id_cat_ambito
          LEFT JOIN detalles_usuario du
          ON el.id_detalle_usuario = du.id_det_usuario
          ORDER BY el.id_detalle_usuario ASC";
  $result = find_by_sql($sql);
  return $result;
}

function find_all_cat_localidades()
{
  $sql = "SELECT * FROM cat_localidades WHERE estatus=1 ORDER BY descripcion ASC";
  $result = find_by_sql($sql);
  return $result;
}
function find_all_cat_municipios()
{
  $sql = "SELECT * FROM cat_municipios WHERE estatus=1 ORDER BY descripcion  ASC";
  $result = find_by_sql($sql);
  return $result;
}
function find_all_cat_entidad()
{
  $sql = "SELECT * FROM cat_entidad_fed WHERE estatus=1 ORDER BY descripcion  ASC";
  $result = find_by_sql($sql);
  return $result;
}

/*--------------------------------------------------------------*/
/* Funcion para llevar a cabo queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
  return $result_set;
}
/*--------------------------------------------------------------*/
/*  Funcion para encontrar datos por su id en una tabla
/*--------------------------------------------------------------*/
function find_by_id($table, $id, $nombre_id)
{
  global $db;
  $id = (int)$id;
  if (tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$db->escape($nombre_id)}='{$db->escape($id)}' LIMIT 1");
    if ($result = $db->fetch_assoc($sql))
      return $result;
    else
      return null;
  }
}

/*--------------------------------------------------------------*/
/*  Funcion para encontrar datos por su id en una tabla
/*--------------------------------------------------------------*/
function find_by_id_user($table, $id, $nombre_id)
{
  global $db;
  $id = (int)$id;
  if (tableExists($table)) {
    $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$db->escape($nombre_id)}='{$db->escape($id)}' LIMIT 1");
    if ($result = $db->fetch_assoc($sql))
      return $result;
    else
      return null;
  }
}
/*---------------------------------------------------------------------------------*/
/* Funcion para encontrar el cargo de un detalle de usuario (trabajador) por su ID */


function find_by_id_detalle($id)
{
  global $db;
  $sql = $db->query("SELECT d.id_det_usuario, d.nombre, d.apellido_paterno, d.apellido_materno, d.curp, d.rfc, d.correo_laboral, d.correo_personal, 
                      d.id_cat_estado_civil, cec.descripcion as estado_civil, d.id_cat_regimen_matrimonial, crm.descripcion as regimen_matrimonial, d.pais_nac,
                      d.nacionalidad, n.descripcion as nacionalidad, d.entidad_nac, en.descripcion as ent_nac, d.telefono, d.lugar_ubica_dom, d.calle_num,
                      d.colonia, d.municipio, m.descripcion as mun, d.tel_part, d.entidad_resid, en.descripcion as ent_resid, d.cod_post, d.estatus_detalle
  FROM detalles_usuario d 
  LEFT JOIN cat_estado_civil cec 
  ON d.id_cat_estado_civil = cec.id_cat_estado_civil 
  LEFT JOIN cat_regimen_matrimonial crm
  ON d.id_cat_regimen_matrimonial = crm.id_cat_regimen_matrimonial
  LEFT JOIN cat_nacionalidades n
  ON d.nacionalidad = n.id_cat_nacionalidad
  LEFT JOIN cat_entidad_fed en
  ON d.entidad_nac = en.id_cat_ent_fed
  LEFT JOIN cat_municipios m
  ON d.municipio = m.id_cat_mun
  WHERE d.id_det_usuario='{$db->escape($id)}' LIMIT 1");
  if ($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_by_detalle_tabla($tabla, $id_detalle)
{
  global $db;
  $sql = $db->query("SELECT COUNT(id_detalle_usuario) as total FROM {$db->escape($tabla)} WHERE id_detalle_usuario='{$db->escape($id_detalle)}'");
  if ($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

/*-----------------------------------------------------------------------------------------------*/
/* Funcion para cuando se elimina un cargo, poner en los detalles de usuario que estan Sin cargo */
/*-----------------------------------------------------------------------------------------------*/
function cargo_default($id)
{
  global $db;
  $sql = "UPDATE detalles_usuario SET id_cargo = 1";
  $sql .= " WHERE id_cargo=" . $db->escape($id);
  $db->query($sql);
  return ($db->affected_rows() >= 1) ? true : false;
}
/*-----------------------------------------------------*/
/* Funcion para eliminar datos de una tabla, por su ID */
/*-----------------------------------------------------*/
function delete_by_id($table, $id, $nombre_id)
{
  global $db;
  if (tableExists($table)) {
    $sql = "DELETE FROM " . $db->escape($table);
    $sql .= " WHERE " . $db->escape($nombre_id) . "=" . $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*-----------------------------------------------------*/
/* Funcion para eliminar datos de una tabla, por su ID */
/*-----------------------------------------------------*/
function delete_by_folio_queja($table, $folio)
{
  global $db;
  if (tableExists($table)) {
    $sql = "DELETE FROM " . $db->escape($table);
    $sql .= " WHERE folio = '{$db->escape($folio)}'";
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*------------------------------------------------------*/
/* Funcion para inactivar datos de una tabla, por su ID */
/*------------------------------------------------------*/
function inactivate_by_id($table, $id, $campo_estatus, $nombre_id)
{
  global $db;
  if (tableExists($table)) {
    $sql = "UPDATE " . $db->escape($table) . " SET ";
    $sql .= $db->escape($campo_estatus) . "=0";
    $sql .= " WHERE " . $db->escape($nombre_id) . "=" . $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*--------------------------------------------------------------*/
/* Funcion para inactivar datos de una tabla, por su ID
/*--------------------------------------------------------------*/
function inactivate_by_id_user($table, $id, $campo_estatus)
{
  global $db;
  if (tableExists($table)) {
    $sql = "UPDATE " . $db->escape($table) . " SET ";
    $sql .= $db->escape($campo_estatus) . "=0";
    $sql .= " WHERE id_detalle_user=" . $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}

/*--------------------------------------------------------------*/
/* Funcion para inactivar cargos en funcion del area inactivada */
/*--------------------------------------------------------------*/
function inactivate_area_cargo($id)
{
  global $db;
  $sql = "UPDATE cargos SET estatus_cargo = 0";
  $sql .= " WHERE id_area=" . $db->escape($id);
  $db->query($sql);
  return ($db->affected_rows() > 0) ? true : false;
}


/*---------------------------------*/
/* Funcion para inactivar un grupo */
/*---------------------------------*/
function inactivate_grupo($table, $id, $campo_estatus)
{
  global $db;
  if (tableExists($table)) {
    $sql = "UPDATE " . $db->escape($table) . " SET ";
    $sql .= $db->escape($campo_estatus) . "=0";
    $sql .= " WHERE nivel_grupo=" . $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*--------------------------------------------------------------*/
/* Funcion para inactivar users en funcion del grupo inactivado */
/*--------------------------------------------------------------*/
function inactivate_user_group($table, $nivel, $campo_estatus)
{
  global $db;

  $sql2 = "UPDATE " . $db->escape($table) . " SET ";
  $sql2 .= $db->escape($campo_estatus) . "=0";
  $sql2 .= " WHERE user_level=" . $db->escape($nivel);
  $db->query($sql2);

  return ($db->affected_rows() >= 0) ? true : false;
}
/*----------------------------------------------------*/
/* Funcion para activar datos de una tabla, por su ID */
/*----------------------------------------------------*/
function activate_by_id($table, $id, $campo_estatus, $nombre_id)
{
  global $db;
  if (tableExists($table)) {
    $sql = "UPDATE " . $db->escape($table) . " SET ";
    $sql .= $campo_estatus . "=1";
    $sql .= " WHERE " . $db->escape($nombre_id) . "=" . $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*--------------------------------------------------------------------------*/
/* Funcion para activar un usuario, en funcion del trabajador que se activó */
/*--------------------------------------------------------------------------*/
function activate_by_id_user($table, $id, $campo_estatus)
{
  global $db;
  if (tableExists($table)) {
    $sql = "UPDATE " . $db->escape($table) . " SET ";
    $sql .= $db->escape($campo_estatus) . "=1";
    $sql .= " WHERE id_detalle_user=" . $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}
/*--------------------------------------------------------------*/
/* Funcion para activar usuario en función del cargo inactivado */
/*--------------------------------------------------------------*/
function activate_cargo_user($table, $id, $campo_estatus)
{
  global $db;
  $id = (int)$id;
  $id_asig = "SELECT id_cargo FROM detalles_usuario WHERE id_cargo = '{$db->escape($id)}'";
  $id_buscado = find_by_sql($id_asig);

  foreach ($id_buscado as $id_encontrado) {
    $sql2 = "UPDATE " . $db->escape($table) . " SET ";
    $sql2 .= $db->escape($campo_estatus) . "=1";
    $sql2 .= " WHERE id_detalle_user=" . $db->escape($id_encontrado['id_cargo']);
    $db->query($sql2);
  }
  return ($db->affected_rows() >= 0) ? true : false;
}
/*-----------------------------------------------------------------------*/
/* Funcion para activar detalle de usuario en funcion del cargo activado */
function activate_cargo_trabajador($table, $id, $campo_estatus)
{
  global $db;
  if (tableExists($table)) {
    $sql = "UPDATE " . $db->escape($table) . " SET ";
    $sql .= $db->escape($campo_estatus) . "=1";
    $sql .= " WHERE id_cargo=" . $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() > 0) ? true : false;
  }
}

/*----------------------------------------------------------*/
/* Funcion para activar cargos en funcion del area activada */
/*----------------------------------------------------------*/
function activate_area_cargo($id)
{
  global $db;
  $sql = "UPDATE cargos SET estatus_cargo = 1";
  $sql .= " WHERE id_area=" . $db->escape($id);
  $db->query($sql);
  return ($db->affected_rows() > 0) ? true : false;
}

function activate_grupo($table, $id, $campo_estatus)
{
  global $db;
  if (tableExists($table)) {
    $sql = "UPDATE " . $db->escape($table) . " SET ";
    $sql .= $db->escape($campo_estatus) . "=1";
    $sql .= " WHERE nivel_grupo=" . $db->escape($id);
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}

/*----------------------------------------------------------*/
/* Funcion para activar users en funcion del grupo activada */
/*----------------------------------------------------------*/
function activate_user_group($id)
{
  global $db;
  $sql = "UPDATE users SET status = 1";
  $sql .= " WHERE user_level=" . $db->escape($id);
  $db->query($sql);
  return ($db->affected_rows() > 0) ? true : false;
}

/*------------------------------------------------------------------------*/
/* Funcion para contar los ID de algun campo para saber su cantidad total */
/*------------------------------------------------------------------------*/
function count_by_id($table, $nombre_id)
{
  global $db;
  if (tableExists($table)) {
    $sql    = "SELECT COUNT(" . $db->escape($nombre_id) . ") AS total FROM " . $db->escape($table);
    $result = $db->query($sql);
    return ($db->fetch_assoc($result));
  }
}
/*-----------------------------------*/
/* Determina si unaa tabla ya existe */
/*-----------------------------------*/
function tableExists($table)
{
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM ' . DB_NAME . ' LIKE "' . $db->escape($table) . '"');
  if ($table_exit) {
    if ($db->num_rows($table_exit) > 0)
      return true;
    else
      return false;
  }
}
/*-----------------------------------------------*/
/* Login con la informacion proporcionada en el
/* $_POST, que proviene del formulario del login */
/*-----------------------------------------------*/
function authenticate($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = "SELECT id_user,username,password,user_level,status FROM users WHERE username = '{$username}' LIMIT 1";
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password'] && $user['status'] != 0) {
      return $user['id_user'];
    }
  }
  return false;
}
/*-----------------------------------------------------*/
/* Login con la información proporcionada en el $_POST,
   proveniente del formulario de login_v2.php. */
/*----------------------------------------------------*/
function authenticate_v2($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password']) {
      return $user['id'];
    }
  }
  return false;
}
/*--------------------------------------------------------------------------*/
/* Encuentra el usuario logueado actualmente en la sesion por el ID de esta */
/*--------------------------------------------------------------------------*/
function current_user()
{
  static $current_user;
  global $db;
  if (!$current_user) {
    if (isset($_SESSION['user_id'])) :
      $user_id = intval($_SESSION['user_id']);
      $current_user = find_by_id_user('users', $user_id, 'id_user');
    endif;
  }
  return $current_user;
}
/*----------------------------------------------------------------------------------------*/
/* Encuentra todos los usuarios haciendo union entre users con la tabla de grupo_usuarios */
/*----------------------------------------------------------------------------------------*/
function find_all_cuentas()
{
  global $db;
  $results = array();
  $sql = "SELECT u.id_user,u.id_detalle_user,d.nombre,d.apellidos,u.username,u.user_level,u.status,u.ultimo_login,";
  $sql .= "g.nombre_grupo ";

  $sql .= "FROM users u ";
  $sql .= "LEFT JOIN detalles_usuario d ON d.id_det_usuario = u.id_detalle_user ";
  $sql .= "LEFT JOIN grupo_usuarios g ";
  $sql .= "ON g.nivel_grupo=u.user_level ORDER BY d.nombre";
  $result = find_by_sql($sql);
  return $result;
}

function find_by_id_all_exp($id)
{
  $id = (int)$id;
  $sql = "SELECT el.id_rel_exp_lab, el.id_detalle_usuario, el.ninguno, el.id_cat_sector, cs.descripcion as sector, el.id_cat_poder, cp.descripcion as poder, 
  el.id_cat_ambito, ca.descripcion as ambito, el.nombre_inst_empresa, el.unidad_admin_area, el.puesto_cargo, el.funcion_principal, el.ingreso, 
  el.egreso, du.nombre, du.apellido_paterno, du.apellido_materno, el.fecha_creacion
  FROM rel_exp_laboral el
  LEFT JOIN cat_sector cs
  ON el.id_cat_sector = cs.id_cat_sector
  LEFT JOIN cat_poder cp
  ON el.id_cat_poder = cp.id_cat_poder
  LEFT JOIN cat_ambito ca
  ON el.id_cat_ambito = ca.id_cat_ambito
  LEFT JOIN detalles_usuario du
  ON el.id_detalle_usuario = du.id_det_usuario
  WHERE id_detalle_usuario = $id
  ORDER BY el.id_detalle_usuario ASC";
  $result = find_by_sql($sql);
  return $result;
}
/*-------------------------------------------------------------------------------------------------------------------------------*/
/* Funcion que encuentra todos los cargos y se relaciona con la tabla areas, para obtener el nombre de esta en funcion del cargo */
/*-------------------------------------------------------------------------------------------------------------------------------*/
function find_all_cargos()
{
  $sql = "SELECT u.id_cargos,u.nombre_cargo,u.id_area,u.estatus_cargo,a.nombre_area ";

  $sql .= "FROM cargos u ";
  $sql .= "LEFT JOIN area a ";
  $sql .= "ON u.id_area=a.id_area ORDER BY a.nombre_area";
  $result = find_by_sql($sql);
  return $result;
}
function find_all_cargos2()
{
  $sql = "SELECT c.id_cargos, c.nombre_cargo, a.id_area, a.nombre_area ";
  $sql .= "FROM cargos as c LEFT JOIN area as a ON c.id_area = a.id_area ";
  $sql .= "ORDER BY c.nombre_cargo ";
  $result = find_by_sql($sql);
  return $result;
}
/*----------------------------------------------*/
/* Funcion que encuentra todos los trabajadores */
/*----------------------------------------------*/
function find_all_trabajadores()
{
  $sql = "SELECT d.id_det_usuario as detalleID,d.nombre,d.apellido_paterno,d.apellido_materno,d.correo_laboral,d.correo_personal,d.tel_part,d.estatus_detalle ";
  $sql .= "FROM detalles_usuario d ORDER BY d.nombre";
  $result = find_by_sql($sql);
  return $result;
}
/*----------------------------------------------------------------------------*/
/* Funcion para actualizar la fecha del ultimo inicio de sesion de un usuario */
/*----------------------------------------------------------------------------*/

function updateLastLogIn($user_id)
{
  global $db;
  $date = make_date();
  $sql = "UPDATE users SET ultimo_login='{$date}' WHERE id_user ='{$user_id}' LIMIT 1";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1 ? true : false);
}
/*---------------------------------------------------*/
/* Encuentra todos los nombres de grupos de usuarios */
/*---------------------------------------------------*/
function find_by_groupName($val)
{
  global $db;
  $sql = "SELECT nombre_grupo FROM grupo_usuarios WHERE nombre_grupo = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*-----------------------------------------------------------*/
/* Encuentra todos los nombres de todas las areas de trabajo */
/*-----------------------------------------------------------*/
function find_by_areaName($val)
{
  global $db;
  $sql = "SELECT nombre_area FROM area WHERE nombre_area = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*-------------------------------------------*/
/* Encuentra todos los nombres de los cargos */
/*-------------------------------------------*/
function find_by_cargoName($val)
{
  global $db;
  $sql = "SELECT nombre_cargo FROM cargos WHERE nombre_cargo = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*--------------------------------*/
/* Encuentra los niveles de grupo */
/*--------------------------------*/
function find_by_groupLevel($level)
{
  global $db;
  $sql = "SELECT nivel_grupo, estatus_grupo FROM grupo_usuarios WHERE nivel_grupo = '{$db->escape($level)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? true : false);
}
/*----------------------------------------------------------------------*/
/* Funcion para checar cual nivel de usuario tiene acceso a cada pagina */
/*----------------------------------------------------------------------*/
function page_require_level($require_level)
{
  global $session;
  $current_user = current_user();
  $login_level = find_by_groupLevel($current_user['user_level']);
  //si el usuario no esta logueado
  if (!$session->isUserLoggedIn(true)) :
    $session->msg('d', 'Por favor, inicia sesión...');
    redirect('index.php', false);
  //si estatus de grupo de usuario esta desactivado
  elseif (@$login_level['estatus_grupo'] === 0) : //Si se quita el arroba muestra un notice
    $session->msg('d', 'Este nivel de usuario esta inactivo!');
    redirect('home.php', false);
  //checa si el nivel de usuario es menor o igual al requerido
  elseif ($current_user['user_level'] <= (int)$require_level) :
    return true;
  else :
    $session->msg("d", "¡Lo siento! no tienes permiso para ver la página.");
    redirect('home.php', false);
  endif;
}
/*----------------------------------------------------------------------*/
/* Funcion para checar cual nivel de usuario tiene acceso a cada pagina */
/*----------------------------------------------------------------------*/
function page_require_level_exacto($require_level)
{
  global $session;
  $current_user = current_user();
  $login_level = find_by_groupLevel($current_user['user_level']);
  //si el usuario no esta logueado
  if (!$session->isUserLoggedIn(true)) :
    $session->msg('d', 'Por favor, inicia sesión...');
    redirect('index.php', false);
  //si estatus de grupo de usuario esta desactivado
  elseif (@$login_level['estatus_grupo'] === 0) : //Si se quita el arroba muestra un notice
    $session->msg('d', 'Este nivel de usuario esta inactivo!');
    redirect('home.php', false);
  //checa si el nivel de usuario es menor o igual al requerido
  elseif ($current_user['user_level'] == $require_level) :
    return true;
  else :
    $session->msg("d", "¡Lo siento! no tienes permiso para ver la página.");
    redirect('home.php', false);
  endif;
}

/*--------------------------------------------------------------*/
/* Funcion para encontrar el detalle de usuario que le pertenece a un usuario */
/*--------------------------------------------------------------*/
function midetalle($id)
{
  global $db;
  $sql  = "SELECT d.id_det_usuario FROM detalles_usuario d INNER JOIN users u ON u.id_detalle_user = d.id_det_usuario WHERE u.id_user = {$id} LIMIT 1";
  return find_by_sql($sql);
}

/*---------------------------------------------------------*/
/* Funcion que encuentra todas los trabajadores de un área */
/*---------------------------------------------------------*/
function find_all_trabajadores_area($area)
{
  $sql = "SELECT d.id_det_usuario,d.nombre, d.apellidos, a.nombre_area 
  FROM detalles_usuario as d 
  LEFT JOIN cargos as c ON c.id_cargos = d.id_cargo 
  LEFT JOIN area as a ON a.id_area = c.id_area 
  WHERE a.id_area = '{$area}' 
  ORDER BY d.nombre ASC";
  $result = find_by_sql($sql);
  return $result;
}

function find_all_localidades($id)
{
  $sql = "SELECT * FROM cat_localidades WHERE id_cat_municipios = {$id} ORDER BY nnombre_localidad ASC";
  $result = find_by_sql($sql);
  return $result;
}

/*---------------------------------------------------------*/
/* Funcion que encuentra todas las subáreas de un área */
/*---------------------------------------------------------*/
function find_all_subarea_area($id)
{
  $sql = "SELECT nombre_area FROM area WHERE area_padre = {$id} ORDER BY nombre_area ASC";
  $result = find_by_sql($sql);
  return $result;
}

function find_all_areas($id)
{
  $sql = "SELECT * FROM area WHERE area_padre = 0 ORDER BY nombre_area ASC";
  $result = find_by_sql($sql);
  return $result;
}
function find_all_areas2($id)
{
  $sql = "SELECT * FROM area WHERE area_padre = '{$id}' ORDER BY nombre_area ASC";
  $result = find_by_sql($sql);
  return $result;
}


/*------------------------------------------------------------------*/
/* Funcion para encontrar el ultimo id de orientaciones y canalizaciones
   para despues sumarle uno y que el nuevo registro tome ese valor */
/*------------------------------------------------------------------*/
function last_id_folios()
{
  global $db;
  $sql = "SELECT * FROM folios ORDER BY id_folio DESC LIMIT 1";
  $result = find_by_sql($sql);
  return $result;
}

/*------------------------------------------------------------------*/
/* Funcion para encontrar el ultimo id de orientaciones y canalizaciones
   para despues sumarle uno y que el nuevo registro tome ese valor */
/*------------------------------------------------------------------*/
function last_id_folios_general()
{
  global $db;
  $sql = "SELECT * FROM folios ORDER BY id_folio DESC LIMIT 1";
  $result = find_by_sql($sql);
  return $result;
}

/* ------------------------------------------------------------------------------*/
/* Función para obtener el grupo de usuario al que pertenece el usuario logueado */
/* ------------------------------------------------------------------------------*/
function area_usuario($id_usuario)
{
  global $db;
  $id_usuario = (int)$id_usuario;

  $sql = $db->query("SELECT g.nivel_grupo, a.id_area  
                      FROM  grupo_usuarios g
                      LEFT JOIN users u ON u.user_level = g.nivel_grupo
                      LEFT JOIN detalles_usuario d ON u.id_detalle_user = d.id_det_usuario 
                      LEFT JOIN cargos c ON c.id_cargos= d.id_cargo 
                      LEFT JOIN area a ON a.id_area = c.id_area 
                      WHERE u.id_user = '{$db->escape($id_usuario)}' LIMIT 1");
  if ($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

/* ------------------------------------------------------------------------------*/
/* Función para obtener el grupo de usuario al que pertenece el usuario logueado */
/* ------------------------------------------------------------------------------*/
function nombre_usuario($id_usuario)
{
  global $db;
  $id_usuario = (int)$id_usuario;

  $sql = $db->query("SELECT d.nombre, d.apellidos
                      FROM  detalles_usuario d
                      LEFT JOIN users u ON u.user_level = d.id
                      WHERE u.id = '{$db->escape($id_usuario)}' LIMIT 1");
  if ($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

/* --------------------------------------------------------------------*/
/* Función para obtener el area a la que pertenece el usuario logueado */
/* --------------------------------------------------------------------*/
function area_usuario2($id_usuario)
{
  global $db;
  $id_usuario = (int)$id_usuario;

  $sql = $db->query("SELECT a.nombre_area, a.id_area
                      FROM detalles_usuario d
                      LEFT JOIN users u ON u.id_detalle_user = d.id_det_usuario
                      LEFT JOIN cargos c ON c.id_cargos = d.id_cargo
                      LEFT JOIN area a ON a.id_area = c.id_area
                      WHERE u.id_user = '{$db->escape($id_usuario)}' LIMIT 1");
  if ($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

/* -------------------------------------------------------------------*/
/* Función para obtener el cargo al que pertenece el usuario logueado */
/* -------------------------------------------------------------------*/
function cargo_usuario($id_usuario)
{
  global $db;
  $id_usuario = (int)$id_usuario;

  $sql = $db->query("SELECT c.nombre_cargo FROM  area g LEFT JOIN users u ON u.user_level = g.id LEFT JOIN detalles_usuario d ON u.id_detalle_user = d.id 
                      LEFT JOIN cargos c ON c.id = d.id_cargo LEFT JOIN area a ON a.id = c.id_area WHERE u.id = '{$db->escape($id_usuario)}' LIMIT 1");
  if ($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}


/*----------------------------------------------------------------------*/
/* Funcion para checar cual nivel de usuario tiene acceso a cada pagina */
/*----------------------------------------------------------------------*/
function page_require_area($require_area)
{
  global $session;
  $current_user = current_user();
  // $id_user = $current_user['id'];
  $area = area_usuario($current_user['id_user']);
  $id_area = $area['id_area'];

  // Le puse || $id_area==2, para que los que son de sistemas
  // si puedan ver todos los módulos
  if (($id_area == $require_area) || ($id_area <= 2)) {
    return true;
  } else {
    $session->msg("d", "¡Lo siento! tu área no tiene permiso para ver esta página.");
    redirect('home.php', false);
  }
}

function insertAccion($user_id, $accion, $id_accion)
{
  global $db;
  $sql = "INSERT INTO registro_actividades (id_usuarios, fecha_accion, descripcion, accion) VALUES ({$user_id}, NOW(),'{$accion}', {$id_accion});";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1 ? true : false);
}

function niv_est($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT oc.nivel_estudios, es.descripcion, COUNT(oc.id_or_can) as total, es.color_estadistica ";
  $sql  .= "FROM orientacion_canalizacion oc  ";
  $sql  .= "LEFT JOIN cat_escolaridad es ON es.id_cat_escolaridad = oc.nivel_estudios ";
  $sql  .= "WHERE oc.tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY oc.nivel_estudios;";
  return find_by_sql($sql);
}

function genero($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT oc.sexo, gen.descripcion, COUNT(oc.id_or_can) as total, gen.color_estadistica ";
  $sql  .= "FROM orientacion_canalizacion oc  ";
  $sql  .= "LEFT JOIN cat_genero gen ON gen.id_cat_gen = oc.sexo ";
  $sql  .= "WHERE oc.tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY oc.sexo;";
  return find_by_sql($sql);
}

function lengua($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT lengua, COUNT(id_or_can) as total ";
  $sql  .= "FROM orientacion_canalizacion ";
  $sql  .= "WHERE tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY lengua;";
  return find_by_sql($sql);
}

function grupos_vuln($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT oc.grupo_vulnerable, gv.descripcion, COUNT(oc.id_or_can) as total, gv.color_estadistica ";
  $sql  .= "FROM orientacion_canalizacion oc  ";
  $sql  .= "LEFT JOIN cat_grupos_vuln gv ON gv.id_cat_grupo_vuln = oc.grupo_vulnerable ";
  $sql  .= "WHERE oc.tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY oc.grupo_vulnerable;";
  return find_by_sql($sql);
}

function entidad($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT oc.entidad, ef.descripcion, COUNT(oc.id_or_can) as total, ef.color_estadistica ";
  $sql  .= "FROM orientacion_canalizacion oc ";
  $sql  .= "LEFT JOIN cat_entidad_fed ef ON ef.id_cat_ent_fed = oc.entidad ";
  $sql  .= "WHERE oc.tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY oc.entidad;";
  return find_by_sql($sql);
}

function municipio($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT municipio_localidad, COUNT(id_or_can) as total ";
  $sql  .= "FROM orientacion_canalizacion ";
  $sql  .= "WHERE tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY municipio_localidad;";
  return find_by_sql($sql);
}

function ocupacion($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT oc.ocupacion, co.descripcion, COUNT(oc.id_or_can) as total, co.color_estadistica ";
  $sql  .= "FROM orientacion_canalizacion oc ";
  $sql  .= "LEFT JOIN cat_ocupaciones co ON co.id_cat_ocup = oc.ocupacion ";
  $sql  .= "WHERE oc.tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY oc.ocupacion;";
  return find_by_sql($sql);
}

function autoridad($tipo)
{
  global $db;
  $tipo = (int)$tipo;
  $sql  = "SELECT oc.institucion_canaliza, ca.nombre_autoridad as descripcion, COUNT(oc.id_or_can) as total ";
  $sql  .= "FROM orientacion_canalizacion oc ";
  $sql  .= "LEFT JOIN cat_autoridades ca ON ca.id_cat_aut = oc.institucion_canaliza ";
  $sql  .= "WHERE oc.tipo_solicitud = '{$db->escape($tipo)}' ";
  $sql  .= "GROUP BY oc.institucion_canaliza;";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Funcion para sacar realacion area-usuario
/*--------------------------------------------------------------*/
function find_area_usuario()
{
  global $db;
  $sql  = "SELECT d.nombre, d.apellidos, a.nombre_area, a.id_area ";
  $sql .= "FROM detalles_usuario d ";
  $sql .= "LEFT JOIN cargos c ON d.id_cargo = c.id_cargos ";
  $sql .= "LEFT JOIN area a ON a.id_area = c.id_area ";
  $sql .= "ORDER BY d.nombre";
  return $db->query($sql);
}


/*------------------------------------------------------------------*/
/* Obtiene Datos generales del usuario*/
/*------------------------------------------------------------------*/
function find_gralesUser($user)
{
  global $db;
  $query = "SELECT id_user, id_detalle_user	, nombre, apellidos, sexo, id_cargo, nombre_cargo, d.id_area, nombre_area    
FROM  users b  
LEFT JOIN detalles_usuario c ON b.id_detalle_user= c.id_det_usuario  
LEFT JOIN cargos d ON d.id_cargos= c.id_cargo
LEFT JOIN area e ON e.id_area= d.id_area
WHERE b.id_user= " . $user;

  $sql = $db->query($query);
  if ($result = $db->fetch_assoc($sql))
    return $result;
}
function find_localidadesOC($tipo)
{
  $sql = "SELECT DISTINCT municipio_localidad FROM `orientacion_canalizacion` WHERE tipo_solicitud=" . $tipo . " GRoup BY municipio_localidad  ORDER BY municipio_localidad";
  $result = find_by_sql($sql);
  return $result;
}

function find_all_area_orden($table)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table) . " ORDER BY nombre_area");
  }
}


/*------------------------------------------------------------------*/
/* Funcion para encontrar el ultimo id la tabla */
/*------------------------------------------------------------------*/
function last_id_table($table, $nombre_id)
{
  $sql = "SELECT * FROM {$table} ORDER BY {$nombre_id} DESC LIMIT 1";
  $result = find_by_sql($sql);
  return $result;
}

/*----------------------------------------------*/
/* Funcion que encuentra los grupos vulnerables de una capacitacion */
/*----------------------------------------------*/
function find_all_grupos($capacitacion)
{
  global $db;
  $results = array();
  $sql = "SELECT 	id_rel_capacitacion_grupos,   id_capacitacion,  id_cat_grupo_vuln,  descripcion,  no_asistentes 
		FROM  rel_capacitacion_grupos a LEFT JOIN cat_grupos_vuln USING(id_cat_grupo_vuln)  
		WHERE id_capacitacion =" . $capacitacion;
  $result = find_by_sql($sql);
  return $result;
}

/*--------------------------------------------*/
/* Funcion que encuentra la descripcion de un di */
/*--------------------------------------------*/
function find_campo_id($table, $id, $nombre_id, $columna)
{
  global $db;
  if (tableExists($table)) {
    $sql = ("SELECT {$db->escape($columna)} FROM {$db->escape($table)} WHERE {$db->escape($nombre_id)}='{$db->escape($id)}'");
    $result = $db->query($sql);
    return ($db->fetch_assoc($result));
  }
}

function find_by_id_accion($id_dif)
{
  global $db;
  $sql = $db->query("SELECT oa.id_otra_accion, oa.folio, oa.fecha, oa.accion, oa.tema, oa.area_solicita, oa.archivo, coa.descripcion as otra_ac, a.nombre_area
                    FROM otras_acciones oa
                    LEFT JOIN cat_otras_acciones coa ON coa.id_cat_otra_accion = oa.accion
                    LEFT JOIN area a ON a.id_area = oa.area_solicita                    
                    WHERE oa.id_otra_accion = '{$id_dif}' LIMIT 1");
  if ($result = $db->fetch_assoc($sql))
    return $result;
  else
    return null;
}

function find_all_status($table)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table) . " WHERE estatus = 1");
  }
}
