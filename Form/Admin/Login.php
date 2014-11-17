<?php

/**
* 
*/
class Form_Admin_Login extends Form
{
	
	function __construct($id = "login", $params)
	{
		parent::__construct($id);
		$this->addElement(new Element_HTML('<legend>Login</legend>'));
		$this->addElement(new Element_Hidden("form", "login"));
		$this->addElement(new Element_Email("Email Address:", "Email", array(
		    "required" => 1
		)));
		$this->addElement(new Element_Password("Password:", "Password", array(
		    "required" => 1
		)));
		// $this->addElement(new Element_Checkbox("", "Remember", array(
		//     "1" => "Remember me"
		// )));
		if($params["captcha"]){

			$before = '<div class="control-group">
<label class="control-label"><span class="required">*</span>Captcha:</label>
	<div class="controls">';
			$after = '	</div>
</div>';
			$this->addElement(new Element_HTML($before.$params["captcha"].$after));

		}
		$this->addElement(new Element_Button("Login"));
		$this->addElement(new Element_Button("Cancel", "button", array(
		    "onclick" => "history.go(-1);"
		)));
	}
}