<?php

class index extends Controller{
    public $private_area = false;

    public function indexAction(){
        $this->layout->setTitle("Sample Starter kit");
        $mFlashMessenger= new Session_FlashMessenger();
        $mFlashMessenger->setMessage("Sample Starter kit info");
        $mFlashMessenger->setMessage("Sample Starter kit error","error");
        $mFlashMessenger->setMessage("Sample Starter kit error 2","error");
        $mFlashMessenger->setMessage("Sample Starter kit warning","warning");
        $mFlashMessenger->setMessage("Sample Starter kit success","success");
        $this->loadView("index/index");
    }
}
