<?php

/**
* 
*/
class Form_Admin_Document_Create extends Form
{
	
	function __construct($id = "documentCreate")
	{
		parent::__construct($id);


		$params = array(
			"entity" => "documents",
			"option", "all","1",

		);

		$api = Model_Api::getInstance();
		$response = $api->get($params,true);
		krsort($response); //ORDER BY id DESC
		$options = array();
		foreach ($response as $id => $doc) {
			$options[$id] = $id. " - " .$doc["titre"];
		}

		$this->addElement(new Element_Hidden("form", "documentCreate"));
		$this->addElement(new Element_HTML('
		<section id="documentSelect">
			<h2>1. Choix de la publication</h2>
			<div class="row"><div clas="span3">
'));
		$this->addElement(new Element_Select("","document_id",$options,array("style" => "width : 400px;", "class"=> "chzn-select", "data-placeholder" => "Selectionnez le document...")));

		$this->addElement(new Element_HTML('</div></div>
			<div class="row">
			<div clas="span3">
			<button id="button-25" style="margin-left:20px;" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Pdf balisé du document</button> 
			<p id="description-button-25" class="label">Aucun pdf</p>
			</div>
			</div></section>		<section id="imageSelect">
			<h2>2. Images de la WebApp</h2>
			<div class="row">
				<div class="span3">
'));

		$this->addElement(new Element_File("", "icone",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
			<button id="button-4" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Icône de l\'application</button>
			<p id="description-button-4" class="label">Aucune image</p>
				</div>
				<div class="span3">
'));

		$this->addElement(new Element_File("", "icone_video",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
			<button id="button-6" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Icône des vidéos</button>
			<p id="description-button-6" class="label">Aucune image</p>
				</div>
				</div>
				<div class="row">
				<div class="span3">
'));

		$this->addElement(new Element_File("", "splashscreen_v",array("class" => "")));
		$this->addElement(new Element_HTML('
			<button id="button-8" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Splashscreen vertical</button>
			<p id="description-button-8" class="label">Aucune image</p>

				</div>
				<div class="span3">
'));
		$this->addElement(new Element_File("", "splashscreen_h",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
				<button id="button-10" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Splashscreen horizontal</button>
							<p id="description-button-10" class="label">Aucune image</p>

				</div>
				</div>
				<div class="row">
				<div class="span3">
'));
		$this->addElement(new Element_File("", "couverture_v",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
				<button id="button-12" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Image de couverture verticale</button>
							<p id="description-button-12" class="label">Aucune image</p>
				</div>
				<div class="span3">
'));

		$this->addElement(new Element_File("", "couverture_h",array("class" => "btn btn-inverse")));
		$this->addElement(new Element_HTML('
				<button id="button-14" class="btn btn-inverse"><i class="icon-upload icon-white"></i> Image de couverture horizontale</button>
							<p id="description-button-14" class="label">Aucune image</p>
				</div>
				</div>
				</section>
'));

		// BASEPATH
		$exempleHtml = '<section>
			<h2>3. Couleurs de la WebApp</h2>
			<div class="row">';
		$exempleHtml .= include(BASEPATH."/views/admin/document/exemple.phtml");
		$this->addElement(new Element_HTML($exempleHtml.'<div class="span5" id="pickers">
					<div id="tab" class="btn-group" data-toggle="buttons-radio">
						<a href="#home-wrapper" class="btn btn-primary active" data-toggle="tab"><i class="icon-home icon-white"></i> accueil</a>
						<a href="#list-wrapper" class="btn btn-primary" data-toggle="tab"><i class="icon-share icon-white"></i> liens</a>
					</div>					<p>1. Couleur des blocs</p>
					<div class="input-prepend color" data-color="#000000" id="cp1">
				      <span class="add-on"><i></i></span>
				      <input pattern="#[A-F,a-f,0-9]{6}" id="bckg-block" name="bckg-block" class="input-small" value="#000000" type="text">
				    </div>
				    <p>2. Couleur de fond de l\'application</p>
					<div class="input-prepend color" data-color="#ffffff" id="cp2">
				      <span class="add-on"><i></i></span>
				      <input pattern="#[A-F,a-f,0-9]{6}" id="bckg-app" name="bckg-app" class="input-small" value="#ffffff" type="text">
				    </div>
				    <p>3. Couleur de fond alternatif</p>
					<div class="input-prepend color" data-color="#dddddd" id="cp3">
				      <span class="add-on"><i></i></span>
				      <input pattern="#[A-F,a-f,0-9]{6}" id="bckg-list" name="bckg-list" class="input-small" value="#dddddd" type="text">
				    </div>
				    <p>4. Couleur de police sur les blocs</p>
					<div class="input-prepend color" data-color="#ffffff" id="cp4">
				      <span class="add-on"><i></i></span>
				      <input pattern="#[A-F,a-f,0-9]{6}" id="color-block" name="color-block" class="input-small" value="#ffffff" type="text">
				    </div>
				    <p>5. Couleur de police sur le fond de l\'application</p>
					<div class="input-prepend color" data-color="#000000" id="cp5">
				      <span class="add-on"><i></i></span>
				      <input pattern="#[A-F,a-f,0-9]{6}" id="color-app" name="color-app" class="input-small" value="#000000" type="text">
				    </div>
				    <p>6. Couleur de police sur le fond alternatif</p>
					<div class="input-prepend color" data-color="#000000" id="cp6">
				      <span class="add-on"><i></i></span>
				      <input pattern="#[A-F,a-f,0-9]{6}" id="color-list" name="color-list" class="input-small" value="#000000" type="text">
				    </div>
				    <p>7. Couleurs des liens</p>
					<div class="input-prepend color" data-color="#ffff00" id="cp7">
				      <span class="add-on"><i></i></span>
				      <input pattern="#[A-F,a-f,0-9]{6}" id="linkColor" name="linkColor" class="input-small" value="#ffff00" type="text">
				    </div>'));

		$this->addElement(new Element_Hidden("color_1","#ffffff",array("style" => "display:none;")));
		$this->addElement(new Element_Hidden("color_2","#000000",array("style" => "display:none;")));
		$this->addElement(new Element_Hidden("color_3","#dddddd",array("style" => "display:none;")));
		$this->addElement(new Element_Hidden("color_4","#000000",array("style" => "display:none;")));
		$this->addElement(new Element_Hidden("color_5","#ffffff",array("style" => "display:none;")));
		$this->addElement(new Element_Hidden("color_6","#000000",array("style" => "display:none;")));
		$this->addElement(new Element_Hidden("color_7","#ffff00",array("style" => "display:none;")));

		$this->addElement(new Element_HTML('</div></div></section>'));

		$this->addElement(new Element_File("", "pdf",array("class" => "")));

		$this->addElement(new Element_Button("Valider et créer la WebApp",null,array("class" => "btn btn-success")));
	}
}