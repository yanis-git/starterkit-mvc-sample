<?php
function __autoload($class_name) {
    $className = explode('_', $class_name);
    $path = "";
    foreach ($className as $key => $val) {
        $path.= $val . "/";
    }
    $path = substr($path, 0, strlen($path) - 1);
    $path = str_replace('\\', '/', $path);
    require_once ($path . ".php");
}

require_once 'Router/php-router.php';

class Application
{
    protected $_router = null;
    protected $_dispatcher = null;
    protected $_params = array();
    protected $module = "front";
    protected $currentController = null;
    protected $currentAction = null;
    protected $_urlParams = array();

    protected static $_instance = null;

    public static function getInstance() {
        if (self::$_instance === null) {
            $classe = __CLASS__;
            self::$_instance = new $classe();
        }

        return self::$_instance;
    }

    public function __construct() {
        $this->_router = new Router;
        $this->_dispatcher = new Dispatcher;
    }

    public function getRouter() {
        return $this->_router;
    }

    public function getDispatcher() {
        return $this->_dispatcher;
    }

    public function getModuleName() {
        return $this->module;
    }

    public function setModuleName($name) {
        $this->module = $name;
        return $this;
    }

    public function run() {
        $this->callRoute();

        try {
            if (substr($_SERVER["REQUEST_URI"], strlen($_SERVER["REQUEST_URI"]) - 1, strlen($_SERVER["REQUEST_URI"])) == "/") {
                $_SERVER["REQUEST_URI"] = substr($_SERVER["REQUEST_URI"], 0, strlen($_SERVER["REQUEST_URI"]) - 1);
            }

            $found_route = $this->getRouter()->findRoute(urldecode($_SERVER['REQUEST_URI']));

            $method = $found_route->getMapMethod();
            $method.= "Action";

            //on rajoute le suffixe Action

            if (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                //on ajoute le prefix ajax_
                $method = "ajax_" . $method;
            }
            $found_route->setMapMethod($method);

            //ajout des parametres de route
            $this->setParams($found_route->getMapArguments());
            $this->setUrlParams($found_route->getMapArguments());

            //ajout des variables request
            $this->setParams($_REQUEST);

            $this->callbootstrap();
            $this->getDispatcher()->dispatch($found_route);
        }
        catch(RouteNotFoundException $exception) {

            //handle Exception
            // header('HTTP/1.1 500 Internal Server Error');
            // Debug::dump($exception);
            header("HTTP/1.0 404 Not Found");
            include (PATH_PUBLIC . "/404.php");
            exit;
        }
        catch(badClassNameException $exception) {

            //handle Exception
            header('HTTP/1.1 500 Internal Server Error');

            // Debug::dump($exception);
            include (PATH_PUBLIC . "/404.php");
            exit;
        }
        catch(classFileNotFoundException $exception) {

            //handle Exception
            header('HTTP/1.1 500 Internal Server Error');
            // var_dump($this);
            // Debug::dump($exception);

            include (PATH_PUBLIC . "/404.php");
            exit;
        }
        catch(classNameNotFoundException $exception) {

            //handle Exception
            header('HTTP/1.1 500 Internal Server Error');
            include (PATH_PUBLIC . "/404.php");
            exit;
        }
        catch(classMethodNotFoundException $exception) {

            //handle Exception
            header('HTTP/1.1 500 Internal Server Error');
            include (PATH_PUBLIC . "/404.php");
            exit;
        }
        catch(classNotSpecifiedException $exception) {

            //handle Exception
            header('HTTP/1.1 500 Internal Server Error');
            include (PATH_PUBLIC . "/404.php");
            exit;
        }
        catch(methodNotSpecifiedException $exception) {

            //handle Exception
            header('HTTP/1.1 500 Internal Server Error');
            include (PATH_PUBLIC . "/404.php");
            exit;
        }
        catch(RouterException $e) {
            self::getHeaderStatutCode($e->getCode());
            include (PATH_PUBLIC . "/404.php");
        }
    }

    /*
    instancie la class boostrap et appelle toute les methodes initXXXX()
    */
    public function callbootstrap() {

        //TODO : Créer une classe boostrap dans la librairie, y les traitements d'init de route, bdd, session, and co
        // exit;$tb = new Mo_Boostrap();exit;
        $boostrap = "Bootstrap";
        if ($this->module == "admin") {
            $boostrap = "BootstrapAdmin";
        }

        $t = get_class_methods($boostrap);
        $boostrap = $boostrap::getInstance();
        foreach ($t as $method) {
            if (substr($method, 0, 4) == "init") {
                $boostrap->$method();
            }
        }
    }

    public function callRoute() {

        // Mise en place des routes par default

        if (substr($_SERVER["REQUEST_URI"], 1, 5) == "admin") {
            $this->module = "admin";
            $this->setAdminRoute();
        } else {
            $this->setDefaultRoute();

            //recuperer les routes Customs
        }
    }

    public function getParams() {
        return $this->_params;
    }
    public function getParam($key) {
        if (!isset($this->_params[$key])) {
            throw new Exception("param $key n'est pas définis.", 1);
        }
        return $this->_params[$key];
    }
    public function setParams(array $params) {
        $this->_params = array_merge($this->_params, $params);
    }

    public function getUrlParams() {
        return $this->_urlParams;
    }
    public function getUrlParam($key) {
        if (!isset($this->_urlParams[$key])) {
            throw new Exception("param $key n'est pas définis.", 1);
        }
        return $this->_urlParams[$key];
    }
    public function setUrlParams(array $params) {
        $this->_urlParams = array_merge($this->_urlParams, $params);
    }

    protected function setAdminRoute() {
        $controllerPath = dirname(__FILE__) . "/../controllers/admin/";
        $this->_dispatcher->setClassPath($controllerPath);
        $boostrap = BootstrapAdmin::getInstance();
        if (method_exists($boostrap, "CustomRoute")) {
            $boostrap->CustomRoute();
        }

        //Set up your default route:
        $default_route = new Route('/admin');
        $default_route->setMapClass('index')->setMapMethod('index');

        $this->_router->addRoute('default', $default_route);

        //Set up your default route:
        $default_action = new Route('/admin/:class');
        $default_action->addDynamicElement(':class', ':class')->setMapMethod('index');
        $this->_router->addRoute('controller-default', $default_action);

        $route = new Route('/admin/:class/:method');
        $route->addDynamicElement(':class', ':class')->addDynamicElement(':method', ':method');
        $this->_router->addRoute('controller-class', $route);
    }

    protected function setDefaultRoute() {
        $controllerPath = dirname(__FILE__) . "/../controllers/front/";
        $this->_dispatcher->setClassPath($controllerPath);

        $boostrap = Bootstrap::getInstance();
        if (method_exists($boostrap, "CustomRoute")) {
            $boostrap->CustomRoute();
        }

        //Set up your default route:
        $default_route = new Route('/');
        $default_route->setMapClass('index')->setMapMethod('index');

        $this->_router->addRoute('default', $default_route);

        //Set up your default route:
        $default_action = new Route('/:class');
        $default_action->addDynamicElement(':class', ':class')->setMapMethod('index');
        $this->_router->addRoute('controller-default', $default_action);

        $route = new Route('/:class/:method');
        $route->addDynamicElement(':class', ':class')->addDynamicElement(':method', ':method');
        $this->_router->addRoute('controller-class', $route);
        if (substr($_SERVER["REQUEST_URI"], strlen($_SERVER["REQUEST_URI"]) - 1, strlen($_SERVER["REQUEST_URI"])) == "/") {
            $_SERVER["REQUEST_URI"] = substr($_SERVER["REQUEST_URI"], 0, strlen($_SERVER["REQUEST_URI"]) - 1);
        }

        $uri = explode("/", substr($_SERVER["REQUEST_URI"], 1));
        $save = "";
        for ($i = 0; $i <= 1; $i++) {

            // on sauvegarde les élements de l'uri utile pour le router. Petit hack car le router ne gère pas les parametres à géométrie variable
            if (isset($uri[$i])) {
                $save.= "/" . $uri[$i];
                if ($i == 0) $this->currentController = $uri[$i];
                elseif ($i == 1) $this->currentAction = $uri[$i];

                unset($uri[$i]);
            }
        }

        if (count($uri) % 2 != 0) {
            end($uri);
            unset($uri[key($uri) ]);
        }
        $isKey = true;
        $nameOfKey = "";
        $params = array();
        foreach ($uri as $key => $value) {

            // on prend en compte les parametres à la zend like
            if ($isKey) {
                $nameOfKey = $value;
            } else {
                $params[$nameOfKey] = $value;
            }
            $isKey = !$isKey;
        }
        $this->setParams($params);
        $this->setUrlParams($params);

        //    Application::getInstance()->setParams($params); // on les set à l'application
        $_SERVER["REQUEST_URI"] = $save;

        // on redéfinie l'uri pour qu'il colle au pattern du router.
        if ($_SERVER["REQUEST_URI"] == "/") $_SERVER["REQUEST_URI"] = "/index/";
    }

    public function isLogged() {
        return (isset($_SESSION["FRAMEWORK"]["user"])) ? true : false;
    }

    public function setUser($user) {
        $_SESSION["FRAMEWORK"]["user"] = $user;
    }

    public function getUser() {
        return $_SESSION["FRAMEWORK"]["user"];
    }

    public function getCurrentController() {
        return (empty($this->currentController)) ? "index" : $this->currentController;
    }

    public function getCurrentAction() {
        return (empty($this->currentAction)) ? "index" : $this->currentAction;
    }

    public static function getHeaderStatutCode($statusCode) {
        $status_codes = array(
                        100 => 'Continue',
                        101 => 'Switching Protocols',
                        102 => 'Processing',
                        200 => 'OK',
                        201 => 'Created',
                        202 => 'Accepted',
                        203 => 'Non-Authoritative Information',
                        204 => 'No Content',
                        205 => 'Reset Content',
                        206 => 'Partial Content',
                        207 => 'Multi-Status',
                        300 => 'Multiple Choices',
                        301 => 'Moved Permanently',
                        302 => 'Found',
                        303 => 'See Other',
                        304 => 'Not Modified',
                        305 => 'Use Proxy',
                        307 => 'Temporary Redirect',
                        400 => 'Bad Request',
                        401 => 'Unauthorized',
                        402 => 'Payment Required',
                        403 => 'Forbidden',
                        404 => 'Not Found',
                        405 => 'Method Not Allowed',
                        406 => 'Not Acceptable',
                        407 => 'Proxy Authentication Required',
                        408 => 'Request Timeout',
                        409 => 'Conflict',
                        410 => 'Gone',
                        411 => 'Length Required',
                        412 => 'Precondition Failed',
                        413 => 'Request Entity Too Large',
                        414 => 'Request-URI Too Long',
                        415 => 'Unsupported Media Type',
                        416 => 'Requested Range Not Satisfiable',
                        417 => 'Expectation Failed',
                        422 => 'Unprocessable Entity',
                        423 => 'Locked',
                        424 => 'Failed Dependency',
                        426 => 'Upgrade Required',
                        500 => 'Internal Server Error',
                        501 => 'Not Implemented',
                        502 => 'Bad Gateway',
                        503 => 'Service Unavailable',
                        504 => 'Gateway Timeout',
                        505 => 'HTTP Version Not Supported',
                        506 => 'Variant Also Negotiates',
                        507 => 'Insufficient Storage',
                        509 => 'Bandwidth Limit Exceeded',
                        510 => 'Not Extended'
            );

        if (!array_key_exists($statusCode, $status_codes)) {
            $statusCode = 500;
        }

        $status_string = $statusCode . ' ' . $status_codes[$statusCode];
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $status_string, true, $statusCode);
        return $status_string;
    }
}
