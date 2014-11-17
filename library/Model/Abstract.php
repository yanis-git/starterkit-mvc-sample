<?php
abstract class Model_Abstract{
    protected $_data = array();
	protected $url = "";

	public function __set($name, $value)
	{
		 $this->_data[$name] = $value;
	}

	public function __get($name)
	{
		if (!array_key_exists($name, $this->_data)) {
			throw new Exception("l'attribut $name n'existe pas.", 1);
        }
        return $this->_data[$name];
	}

	public function setFromArray($data)
	{
	    foreach ($data as $columnName => $value) {
		   	$this->__set($columnName, $value);
	    }
	}

	public function toArray()
	{
		return $this->_data;
	}

    public function needRest($return,$datas = null){
        if((empty($return) or $return == false) and $save = $this->readRest($this->type,$datas)){
            $this->save($save);
            return true;
        }
        elseif($return != false)
            return $return;
        return false;
    }

    protected function readRest($type,$datas){
        if(!class_exists("Model_Api")){
            throw new Exception("La classe Model_Api n'est pas définie.", 1);
        }
        $cli = Model_Api::getInstance();
        if(Registry::getInstance()->isRegistered("document"))
            $document = Registry::getInstance()->get("document");
        else
            $document = Application::getInstance()->getParams();
        $params = array(
            "entity" => $type,
            "document_id" => $document["document_id"]
        );
        if(!is_null($datas)){
            // if(is_array($datas)){
            //     foreach ($datas as $key => $value) {
            //         $params[$key] = $value;
            //     }
            // }
            // else
            //     $params["id"] = $datas;
        }
        else
            $params["entity"] = $params["entity"]."s";
        return $cli->get($params);
    }

	abstract public function find($id);
	abstract public function findBy(array $params);
	abstract public function fetchAll();
	abstract public function save($data = null);
}