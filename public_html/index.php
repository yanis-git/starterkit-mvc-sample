<?php
/**
 * TODO : Gestion du Stagging / dev / prod
**/
error_reporting(E_ALL);
ini_set("display_errors", 1);


define("PATH_PUBLIC", dirname(__FILE__));
define("BASEPATH", realpath(dirname(__FILE__)."/../"));
set_include_path(implode(PATH_SEPARATOR, array(
    realpath('../library'),realpath('../vendor/knplabs/knp-piwik-client/src'), realpath('../vendor/kriswallsmith/buzz/lib'),realpath('../vendor/nesbot/carbon/src'),realpath('../library/Form'),realpath('../'),get_include_path(),
)));

// require(PATH_PUBLIC."/../vendor/nesbot/carbon/src/Carbon/Carbon.php");

require 'Application.php';

Application::getInstance()
			 ->run();
