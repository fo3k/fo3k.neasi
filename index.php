<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////
// Scripted by (c) Morgion 2003 - Dotazy a pripominky posilejte na ICQ 115913018, morgion@stranky.org //
////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "kontakt #46 __ 9. 4. 2023 __  srub Hesoff";exit;
session_start();
require('./config.php');
require('./db_connect.php');
require('./auth.php');
require('./fce.php');

$isLoged = (AuthAuth() == AUTH_ERR_ALLOK) ? 1 : 0;

//Autologin
if ($_COOKIE['log'] == true && $isLoged != 1 ):
	$username = $_COOKIE['name'];
	$userpass = $_COOKIE['pass'];
	if (AuthLogin($username, $userpass) == AUTH_ERR_ALLOK ) header("location: index.php");
endif;

Header("Pragma: no-cache");
Header("Cache-control: no-cache");
Header ("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");

switch ($_GET['dir']) {
case 'admin':
	$directory = 'admin';
	break;
default:
	$directory = 'stranky';
} 
$full_directory = dirname(__FILE__).DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR;

require_once('./class_pet.eng.inc.php');

//=================== HEADER a MENU
$header = new pet;
$header->read_file('./templates/header.htm');

$header->add_content(SKIN, 'skin');
$header->add_content(PAGE_NAME, 'page_title');
$header->add_content(PAGE_NAME, 'page_name');
$header->add_content('<div id="news">', 'div');

require('./menu.php');

//=================== PROSTREDEK
if (!empty($_REQUEST['page'])):
	$_REQUEST['page'] = eregi_replace('[^0-9a-z\-\_]', '', $_REQUEST['page']);
	if (File_Exists($full_directory . $_REQUEST['page'] . '.php'))	require ($full_directory . $_REQUEST['page'] . '.php');
	else EchoError('Stránka ' . $_REQUEST['page'] . ' nenalezena!');
else:
	require ($full_directory . 'index.php');
endif;

//====================== Vypsani kodu stranky
$header->parse();
$header->output();

$obsah->parse();
$obsah->output();

//===================== Paticka stranky

$footer = new pet;
$footer->read_file('./templates/footer.htm');

if ($isLoged == 1):
	$loginText = '<a href="login.php?act=logout"  title="Logout">odhlásit</a>, <a href="index.php?dir=admin"  title="Administraèní sekce stránek">admin</a>';
	if ($directory != 'stranky' && empty($page)) $loginText = '<a href="index.php"  title="Na úvodní stránku">home</a>, '.$loginText;
	$footer->add_content($loginText, 'prihlasen');
else:
	$footer->add_content('<a href="login.php" title="Pøihlásit se">Login</a>', 'prihlasen');
endif;
$footer->parse();
$footer->output();

mysql_close($conn);
?>