<?php

class Script{
	protected static $_instance = null;

	public static function getInstance()
	{
		if (self::$_instance === null) {
            self::$_instance = new Script();
        }

        return self::$_instance;
	}

	public function init(){
		$this->initConfig();
		$this->initDb();
	}
	
	protected function initConfig()
	{
		$config = include(BASEPATH."/config/application.php");
		Registry::getInstance()->set("config",$config);
	}

	protected function initDb()
	{
		$db = DataBase::instance();
		$config = Registry::getInstance()->get("config");
		$db->configMaster($config["mysql"]["host"],$config["mysql"]["name"],$config["mysql"]["user"],$config["mysql"]["password"]);
		$db->configSlave($config["mysql"]["host"],$config["mysql"]["name"],$config["mysql"]["user"],$config["mysql"]["password"]);
	}

}