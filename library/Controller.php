<?php
class Controller{
    public $user, $title, $action;
    public $data;
    public $layout = true;
    public $params = array();
    public $translator = false;
    public $private_area = false;

    public function __construct()
    {
       if($this->layout == true and !$this->layout instanceof Layout){
            $this->layout = new Layout($this->data);
            $this->layout->setContent("controller",Application::getInstance()->getCurrentController());
            $this->layout->setContent("action",Application::getInstance()->getCurrentAction());
        }

        if(Registry::getInstance()->offsetExists("translate")){
            $translator = Translator::getInstance();
            $translator->setData(Registry::getInstance()->get("translate"));
            $translator->setLocale(Registry::getInstance()->get("locale"));
            $this->translator = $translator;
        }

        $this->setParams(Application::getInstance()->getParams());
        // Debug::dump(Application::getInstance()->isLogged());exit;
        if($this->private_area and !Application::getInstance()->isLogged()){
            //on est dans une zone protégé par un mdp, on redirige vers l'authentificator

            $url = $_SERVER["REQUEST_URI"];
            $params = $this->getParams();
            unset($params["PHPSESSID"]);
            unset($params["__atuvc"]);
            unset($params["arp_scroll_position"]);
            foreach ($params as $key => $value) {
                $url .= "/".$key."/".$value;
            }
            $tmp = array(
                "controller" => "administrator",
                "action" => "login",
            );

            $_SESSION["FRAMEWORK"]["uri_redirect"] = $url;
            $this->redirectTo($tmp,"admin");
        }

        if(method_exists($this,'init')){
            $this->init();
        }
    }

    public function redirectTo($params, $module = "default"){
        if(is_array($params)){
            if($module == "default")
                $module = Application::getInstance()->getModuleName();

            if($module == "admin"){
                $url = $this->getAdminUrl($params);
            }
            else{
                $url = $this->getUrl($params);
            }
        }
        else{
            $url = $params;
        }
        header("Location: ".$url);
        exit;
    }

    public function goTo404(){
        include("../public_html/404.php");
        exit();
    }

    public function loadView($pathName, $placeholder = 'body', $return = false){
        $module = Application::getInstance()->getModuleName();
        $pathName = $module."/".$pathName;

        if($this->checkView($pathName)){
			if($return)
            {
				ob_start();
                include("../views/$pathName.phtml");
                $retour = ob_get_contents();
                ob_end_clean();
                return $retour;
			}
            elseif($this->layout)
            {
                if(!$this->layout instanceof Layout){
                    $this->layout = new Layout($this->data);
                }
                ob_start();

                include("../views/$pathName.phtml");
                $this->layout->setContent($placeholder, ob_get_contents());
                ob_end_clean();
                $this->layout->render();
            }
            else
            {
            	include("../views/$pathName.phtml");
			}
        }else{
			if($return){
				return false;
			}else{
            	// self::f404Static();
                throw new Exception("La view $pathName n'existe pas.", 1);
			}
        }
    }

    public function checkView($pathName){
        if(file_exists("../views/$pathName.phtml")){
            return true;
        }else{
            return false;
        }
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
    public function setParam($key,$valeur){
        $this->params[$key] = $valeur;
    }

    public function hasParam($key)
    {
        return !empty($this->params[$key]);
    }

    public function getParam($key)
    {
        return $this->params[$key];
    }

    public function getParams(){
        return $this->params;
    }

    public function getTranslator(){
        if($this->translator == false){
            throw new Exception("Aucun Traducteur n'a été définie", 1);
        }
        return $this->translator;
    }

    public function translate($key){
        return $this->getTranslator()->translate($key);
    }

    public function getUrl(array $params){
        // $d = Registry::getInstance()->get("document");

        // $p = explode("/",$d["nom_chemin"]);
        // if(!isset($p[1]))
        //     $url = "/".$d["id"]."/publispeak/";
        // else
        //     $url = "/".$d["id"]."/".$p[1]."/";
        if(empty($params["controller"])){
            throw new Exception("Le parametre Controller est nécessaire.", 1);
        }

        if(empty($params["action"])){
            throw new Exception("Le parametre action est nécessaire.", 1);
        }

        $url = "/".$params["controller"]."/".$params["action"];
        unset($params["controller"]); 
        unset($params["action"]);

        foreach ($params as $key => $value) {
            $url .= "/".$key."/".$value;
        }

        return $url;
    }

    public function getAdminUrl(array $params){
        if(empty($params["controller"])){
            throw new Exception("Le parametre Controller est nécessaire.", 1);
        }

        if(empty($params["action"])){
            throw new Exception("Le parametre action est nécessaire.", 1);
        }

        $url = "/admin/".$params["controller"]."/".$params["action"];
        unset($params["controller"]); 
        unset($params["action"]);

        foreach ($params as $key => $value) {
            $url .= "/".$key."/".$value;
        }

        return $url;        
    }

    protected function prepareDataToUrl($data){
        $data = str_replace(" ", "-", $data);
        $data = preg_replace("#[^a-zA-Z0-9\/_|+ -]#", "", $data);
        return $data;
    }
}