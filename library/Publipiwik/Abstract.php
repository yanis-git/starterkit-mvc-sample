<?php
/** 
 * Base Class for implemente any Piwik entity
**/

use Knp\PiwikClient\Connection\HttpConnection;
use Knp\PiwikClient\Client;


abstract class Publipiwik_Abstract{
	protected static $_instance = array();
	protected $client = null;
	protected static $__CLASS__ = __CLASS__;
	protected static $_token;
	protected static $_url;

	const ACCESSVIEWLEVEL = "view";

	public static function getInstance(){
		$currentClass = get_called_class();
		if(empty(self::$_instance[$currentClass])){
			$currentModel = self::getClass();
			$config = include(BASEPATH."/config/piwik.php");

			self::$_url = $config['url'];
			self::$_token = $config['token'];
			self::$_instance[$currentClass] = new $currentModel(self::$_url, self::$_token);
		}
		
		return self::$_instance[$currentClass];
	}

	public function __construct($url, $token){
		$connection = new HttpConnection($url);
		$this->client = new Client($connection, $token);
	}
	
	private static function getClass() {
        $implementing_class = static::$__CLASS__;
        $original_class = __CLASS__;

        if ($implementing_class === $original_class) {
            exit("You MUST provide a <code>protected static \$__CLASS__ = __CLASS__;</code> statement in your Singleton-class!");
        }
        
        return $implementing_class;
    }
}