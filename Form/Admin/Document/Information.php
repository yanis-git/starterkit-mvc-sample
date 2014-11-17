<?php

/**
* 
*/
class Form_Admin_Document_Information extends Form
{
	
	function __construct($id = "information")
	{
		parent::__construct($id);

		$this->addElement(new Element_HTML('<div class="control-group">
				<div clas="controls">
					<button id="button-1" style="margin-left:20px;" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Pdf balisé du document</button> 
					<p id="description-button-1" class="label">Aucun pdf</p>
				</div>
			</div>'));
		$this->addElement(new Element_File("", "pdf",array("class" => "")));
		
		$this->addElement(new Element_YesNo("vocalisation :", "has_vocalisation"));
		$this->addElement(new Element_Button("Editer les propriétés",null,array("class" => "btn btn-success")));
	}
}