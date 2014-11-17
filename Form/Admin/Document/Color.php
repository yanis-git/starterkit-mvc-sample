<?php

/**
* 
*/
class Form_Admin_Document_Color extends Form
{
	
	function __construct($id = "documentColor")
	{
		parent::__construct($id);
				// BASEPATH

		$exempleHtml = '<section>
			<h2>3. Couleurs de la WebApp</h2>
			<div class="row">';
		$exempleHtml .= include(BASEPATH."/views/admin/document/exemple.phtml");
		$this->addElement(new Element_HTML($exempleHtml.'<div class="span4" id="pickers">
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

		$this->addElement(new Element_Button("Valider et créer la WebApp",null,array("class" => "btn btn-success")));
	}
}