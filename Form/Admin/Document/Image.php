<?php

/**
* 
*/
class Form_Admin_Document_Image extends Form
{
	
	function __construct($id = "documentImage")
	{
		parent::__construct($id);

				$this->addElement(new Element_HTML('
			<h2>Images de la WebApp</h2>
			<div class="row">
				<div class="span3">
'));

		$this->addElement(new Element_File("", "icone",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
			<button id="button-1" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Icône de l\'application</button>
			<p id="description-button-1" class="label">Aucune image</p>
				</div>
				<div class="span3">
'));

		$this->addElement(new Element_File("", "icone_video",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
			<button id="button-3" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Icône des vidéos</button>
			<p id="description-button-3" class="label">Aucune image</p>
				</div>
				</div>
				<div class="row">
				<div class="span3">
'));

		$this->addElement(new Element_File("", "splashscreen_v",array("class" => "")));
		$this->addElement(new Element_HTML('
			<button id="button-5" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Splashscreen vertical</button>
			<p id="description-button-5" class="label">Aucune image</p>

				</div>
				<div class="span3">
'));

		$this->addElement(new Element_File("", "splashscreen_h",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
				<button id="button-7" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Splashscreen horizontal</button>
							<p id="description-button-7" class="label">Aucune image</p>

				</div>
				</div>
				<div class="row">
				<div class="span3">
'));

		$this->addElement(new Element_File("", "couverture_v",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
				<button id="button-9" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Image de couverture verticale</button>
							<p id="description-button-9" class="label">Aucune image</p>
				</div>
				<div class="span3">'));
		$this->addElement(new Element_File("", "couverture_h",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
				<button id="button-11" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Image de couverture horizontale</button>
							<p id="description-button-11" class="label">Aucune image</p>
				</div>
				</div>'));
		$this->addElement(new Element_Hidden("lorem","ipsum"));
		$this->addElement(new Element_Button("Enregistrer les images",null,array("class" => "btn btn-success")));

	}
}