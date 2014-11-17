<?php 
class Form_Admin_Document_Commentaire extends Form
{
	
	function __construct($id = "commentaire",$params = false){
		parent::__construct($id);
		// Debug::dump($params);exit;
		if($params["commentaire"])
			$this->addElement(new Element_TinyMCE("Commentaire : ", "commentaire",array("value" => $params["commentaire"],"shortDesc" => "Derniere édition le : ".$params["updated_at"])));
		else
			$this->addElement(new Element_TinyMCE("Commentaire : ", "commentaire"));
		$this->addElement(new Element_Button("Sauvegarder",null,array("class" => "btn btn-success")));
	}
}