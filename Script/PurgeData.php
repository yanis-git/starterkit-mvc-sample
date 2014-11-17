<?php
session_save_path('/var/tmp');

/**
 * TODO : Gestion du Stagging / dev / prod
**/
error_reporting(E_ALL);
ini_set("display_errors", 1);


define("BASEPATH", realpath(dirname(__FILE__)."/../"));
define("PATH_PUBLIC", dirname(__FILE__)."/public_html/");

set_include_path(implode(PATH_SEPARATOR, array(
    realpath('../library'),realpath('../vendor/knplabs/knp-piwik-client/src'), realpath('../vendor/kriswallsmith/buzz/lib'),realpath('../vendor/nesbot/carbon/src'),realpath('../library/Form'),realpath('../'),get_include_path(),
)));

require 'Application.php';

/**
 * On initialise le mode script
**/
Script::getInstance()->init();


/** 
 * Let's do it !
**/

$db = DataBase::instance();
$cacheDir = BASEPATH."/Cache/";
//Case remove Every Data.
if(empty($argv[1])){
	//ON DB
	$db->delete("graph");
	$db->delete("metrique");
	//ON CACHE
	$handle = opendir($cacheDir);
	while($file = readdir($handle)){
		if(in_array($file, array("..",".",".DS_Store"))){
			continue;
		}
		system("rm -r ".$cacheDir.$file);
	}
}

