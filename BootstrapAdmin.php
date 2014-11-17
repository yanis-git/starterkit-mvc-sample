<?php

class BootstrapAdmin extends Mo_Bootstrap
{
	public function CustomRoute(){
	  if(substr($_SERVER["REQUEST_URI"], strlen($_SERVER["REQUEST_URI"]) - 1,strlen($_SERVER["REQUEST_URI"])) == "/"){
          $_SERVER["REQUEST_URI"] = substr($_SERVER["REQUEST_URI"], 0, strlen($_SERVER["REQUEST_URI"]) - 1);
       }

       $uri =explode("/", substr($_SERVER["REQUEST_URI"],1));
       $save = "";
       // Debug::dump($uri);exit;
       for ($i=0; $i < 3; $i++){ // on sauvegarde les élements de l'uri utile pour le router. Petit hack car le router ne gère pas les parametres à géométrie variable
          if(isset($uri[$i])){
            $save .= "/".$uri[$i];
            unset($uri[$i]);
          }
       }
       if(count($uri) % 2 != 0){
          end($uri);
          unset($uri[key($uri)]);
       }
       $isKey = true;
       $nameOfKey = "";
       $params = array();
       foreach ($uri as $key => $value) { // on prend en compte les parametres à la zend like
            if($isKey){
                $nameOfKey = $value;
            }else{
                $params[$nameOfKey] = $value;
            }
            $isKey = !$isKey;
       }

       Application::getInstance()->setParams($params); // on les set à l'application
       $_SERVER["REQUEST_URI"] = $save; // on redéfinie l'uri pour qu'il colle au pattern du router.
	}
}