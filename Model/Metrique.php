<?php
/**
* 
*/
class Model_Metrique extends Model_Data_Base
{
    protected $type = "metrique";
    protected $table = "metrique";

    public static $type_metrique = array("nb_visits","nb_uniq_visitors","avg_time_on_site","bounce_rate","nb_pageviews","nb_uniq_pageviews","nb_downloads");
    
    const TYPE_GLOBAL 	= "global_data";
	const TYPE_GRAPH 	= "graph_data";

	public function checkIfDataExist($idSite, $begin_at, $finish_at, $type){
		$req = 'SELECT * FROM `metrique` WHERE `site_id` = :site_id AND `begin_at` = :begin_at AND `finish_at` = :finish_at AND type = ":type"';
		$r = $this->_wrapper->query($req,array(":site_id" => (int)$idSite,":finish_at" => $finish_at,":begin_at" => $begin_at, ":type" => $type));
		if(empty($r))
			return false;
		else
			return $r;
	}

	public function prepareArrayFromBase($params){
		$r = array();
		foreach ($params as $param) {
			$r[$param["property"]] = $param["value"];
		}
		return $r;
	}
	
	public function deleteAll(){
		$this->_wrapper->delete("metrique");
	}
	
	public function deleteBy($params){
		$this->_wrapper->delete("metrique",$params);
	}
}