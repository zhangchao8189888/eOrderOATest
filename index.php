<?php
ob_start ();
session_start ();
require_once ("./common/common.inc");
header("Content-type: text/html; charset=utf-8");
$actionPath = $_REQUEST ['action'];
global $actionPath;
date_default_timezone_set ( "PRC" );
$user = $_SESSION ['admin'];
$loginusername = $user ['name'];

if (empty ( $user ) && $_REQUEST ['mode'] != "checklogin" && $_GET['mode'] !='fileUpload') {
    $actionrealpath = "tpl/login.php";
}  else {
	global $loginusername;

    $admin_uid = $user['id'];
	global $admin_uid;
	if (empty ( $actionPath )) {
		$actionrealpath = "module/action/AdminAction.class.php";
		$actionPath = "Admin";
		$firstLoginType = 1;
	} else {//sdsd
		$actionrealpath = "module/action/{$actionPath}Action.class.php";
	}
}
require_once ($actionrealpath);
