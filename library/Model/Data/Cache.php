<?php
abstract class Model_Data_Cache extends Model_Abstract{
    protected $type = "";
    protected $dir = "";

    public function find($id){
        $return = $this->readCache($this->type,$id);
        if($return)
            $this->setFromArray($return);
        return $return;
    }

    public function findBy(array $params){
        $return = $this->readCache($this->type,$params);
        return $return;
    }

    public function fetchAll($order_by = null){
        $return = $this->readCache($this->type,null);
        if(is_null($order_by))
            return $return;
        if(is_string($order_by)){
            $classedCollection = array();
            $refClassed = array();
            foreach ($return as $key => $data) {
                if(!isset($data[$order_by]))
                    throw new Exception("la clé $order_by n'existe pas dans toutes les entrés", 1);
                $refClassed[$key] = $data[$order_by];
            }
            asort($refClassed);
            // var_dump($refClassed);
            foreach ($refClassed as $index => $ordered) {
                $classedCollection[] = $return[$index];
            }
            return $classedCollection;
        }
    }

    public function save($datas = null){
        if($datas != null and !is_array($datas))
            $datas = array(0 => $datas);
        $this->deleteCache($this->type,$datas);
        $this->writeCache($datas);
    }

    public function delete($datas = array()){
        $this->deleteCache($this->type,$datas);
    }

    protected function writeCache($datas){
        $entities[$this->type] = $datas;
		$this->manager->setEntities($entities);
		$this->manager->save();
    }

    protected function readCache($type,$datas){
        if($type == null)
            return $this->manager->fetchAll();
        else
            return $this->manager->find($type,$datas);
    }

    protected function deleteCache($type,$datas){
    	if($type == null)
			$this->manager->clearDossier($this->manager->getBaseDir());
		else{
            if(!empty($datas)){
                foreach($datas as $key => $data){
                    if(is_string($data)){
                        $this->manager->clearFile($this->manager->getBaseDir().$type."/".$data.".cache");
                    }
                    else{
                        $this->manager->clearFile($this->manager->getBaseDir().$type."/".$key.".cache");
                    }
                }
            }
            else
                $this->manager->clearDossier($this->manager->getBaseDir().$type."/");
        }
    }
}
