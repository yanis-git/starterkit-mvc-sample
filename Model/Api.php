<?php
/**
* Manager de l'api publispeak
*/
class Model_Api extends Http_Client_Rest
{
	static protected $_instance = null;
	const BASEURL = "http://api.publispeak.com/";
	const EXTENTION = "json";

	protected $user = "webapp";
	protected $password = "c0ca616865e03e3545143a0b991df4f11d978f84";
	protected $typeAuth = HTTP_Request2::AUTH_DIGEST;

	public static function getInstance(){
		if(self::$_instance == null){
			self::$_instance = new Model_Api();
		}
		return self::$_instance;
	}

//TODO : ajouter les parametres non standard. Exemple : count/pages/
	protected function prepareUrl(array $params){
		$url = self::BASEURL;

		if(isset($params["entity"]) and $params["entity"] != "documents")
		{
	 		if(empty($params["document_id"])){
	 			throw new Exception("Le parametre document_id est obligatoire.", 1);
	 		}
	 		$url .= "document/".$params["document_id"];
	 		unset($params["document_id"]);
	 		if(!isset($params["entity"])){
	 			throw new Exception("le parametre entity est obligatoire", 1);
	 		}
	 		
	 		elseif($params["entity"] != false){
	 			$url .= '/'.strtolower($params["entity"]);
	 		}
	 	}
	 	else{
	 		$url .= "documents";
	 	}
 		unset($params["entity"]);
 		if(!empty($params["id"])){
 			$url .= "/".$params["id"];
 			unset($params["id"]);
 		}

 		//on supprime les params déjà injects et on s'occupe des optionnels.
 		foreach ($params as $key => $value) {
 			$url .= "/".$value;
 		}
 		
 		$url .= ".".self::EXTENTION;
 		// Debug::dump($url);
 		return $url;
	}


	public function query($url){
		$request = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
		if($this->user and $this->password and $this->typeAuth)
			$request->setAuth($this->user,$this->password,$this->typeAuth);

		$json = $request->send();
		return json_decode($json->getBody(),true);
	}
}