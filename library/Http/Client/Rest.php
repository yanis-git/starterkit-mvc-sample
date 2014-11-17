<?php
abstract class Http_Client_Rest{
	protected $user = false;
	protected $password = false;
	protected $typeAuth = false;

	public function get(array $params, $customTab = true){
		//TODO prépare Url.
		$url = $this->prepareUrl($params);

		$request = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
		if($this->user and $this->password and $this->typeAuth)
			$request->setAuth($this->user,$this->password,$this->typeAuth);

		$json = $request->send();
		//Debug::dump($request->getBody());exit;
        //Debug::dump($request);exit;
        if($json->getStatus() == 200){
        	// Debug::dump(json_decode($json->getBody(),true));
            $tab = json_decode($json->getBody(),true);
            if($customTab)
            	return $this->prepareTab($tab);
            else
            	return $tab;
        }
        else{
            return false;
        }
	}

	public function post(array $params)
	{
		$url = $this->prepareUrl($params);
		$request = new HTTP_Request2($url,HTTP_Request2::METHOD_POST);
		if($this->user and $this->password and $this->typeAuth)
			$request->setAuth($this->user,$this->password,$this->typeAuth);
		
		$json = $request->send();
		return ($json->getStatus() == 200)?true:false;
	}

	public function prepareTab($tab){
		$newtab = array();
		foreach ($tab as $val) {
			if(isset($val["id"]))
				$newtab[$val["id"]] = $val;
			else
				$newtab[] = $val;
		}
		return $newtab;
	}
	abstract protected function prepareUrl(array $params);
}