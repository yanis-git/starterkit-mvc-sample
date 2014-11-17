<?php
/**
* 
*/
class Model_Graph extends Model_Data_Base
{
    protected $type = "graph";
    protected $table = "graph";
    
    const TYPE_GLOBAL 	= "global_data";
	const TYPE_GRAPH 	= "graph_data";

	public function checkIfDataExist($idSite, $begin_at, $finish_at, $type){
		$req = 'SELECT * FROM `graph` WHERE `site_id` = :site_id AND `begin_at` = :begin_at AND `finish_at` = :finish_at AND `type` = :type ORDER BY `date`';
		$r = $this->_wrapper->query($req,array(":site_id" => (int)$idSite,":finish_at" => $finish_at,":begin_at" => $begin_at, ":type" => $type));
		if(empty($r))
			return false;
		else
			return $r;
	}

	public function prepareArrayFromBase($params){
		$r = array();
		foreach ($params as $param) {
			$r[] = array(
				"date" => date('Y-m-d',strtotime($param["date"])),//Switch to english format,
				"value" => $param["value"]
			);
		}
		return $r;
	}
	
	public function deleteAll(){
		$this->_wrapper->delete("graph");
	}
	
	public function deleteBy($params){
		$this->_wrapper->delete("graph",$params);
	}
}
