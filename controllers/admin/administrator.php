<?php
class administrator extends Controller{

	public function loginAction(){
		// throw new RouterException("Fuck la poliiiiice",409);
		$this->loadView("administrator/login");
	}

	public function ajax_loginAction(){

	}

	public function logoutAction(){
		session_destroy();
		$this->redirectTo(array("controller" => "administrator", "action" => "login"));
	}
}