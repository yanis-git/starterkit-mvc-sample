<?php

/**
 * Moufasa
 * Class Layout
 */
class Layout
{
    protected $styles = array();
    protected $scripts = array();
    protected $content = array();
    protected $captureStyle = array();
    protected $captureScript = array();

    protected $description;
    protected $title;
    public $data;

    protected $defaultView = array('footer', 'header', 'menu', 'left', 'right');

    public function __construct($data) {
        $this->data = $data;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function addTitle($title, $seperator = " - ") {
        $this->title.= $seperator . $title;
    }

    public function addStyle($path, $option = null) {
        if ($option == null) $this->styles[] = $path;
        else $this->styles[] = array($path, $option);
        return $this;
    }

    public function addScript($path) {
        $this->scripts[] = $path;
        return $this;
    }

    public function setContent($contener, $content) {
        $this->content[$contener] = $content;
    }

    public function styles() {
        $affiche = "";
        foreach ($this->styles as $style) {
            if (is_array($style)) {
                var_dump($style);
                $affiche.= "<link href=\"/css/" . $style[0] . "\" " . $style[1] . "rel=\"stylesheet\">\n";
            } else {
                if (preg_match("#^//#", $style)) {
                    $affiche.= "<link href=\"" . $style . "\" rel=\"stylesheet\">\n";
                } else {
                    $affiche.= "<link href=\"/css/" . $style . "\" rel=\"stylesheet\">\n";
                }
            }
        }

        foreach ($this->captureStyle as $style) {
            $affiche.= $style . "\n";
        }
        return $affiche;
    }

    public function scripts() {
        $affiche = "";
        foreach ($this->scripts as $script) {
            if (preg_match("#^//#", $script)) {
                $affiche.= "<script src=\"" . $script . "\"></script>\n";
            }else{
                $affiche.= "<script src=\"/js/" . $script . "\"></script>\n";
            }
        }
        foreach ($this->captureScript as $script) {
            $affiche.= $script . "\n";
        }

        return $affiche;
    }

    public function getContent($contener = "body") {
        if (isset($this->content[$contener])) return $this->content[$contener];
        else return null;
    }

    public function render() {
        foreach ($this->defaultView as $view) {
            if ($this->checkView($view)) {
                ob_start();
                include ("../views/layout/$view.phtml");
                $this->setContent($view, ob_get_contents());
                ob_end_clean();
            }
        }
        $module = Application::getInstance()->getModuleName();
        include ("../views/$module/layout.phtml");
    }

    public function checkView($pathName) {
        $module = Application::getInstance()->getModuleName();
        if (file_exists("../views/$module/layout/$pathName.phtml")) {
            return true;
        } else {
            return false;
        }
    }

    public function captureStyleStart() {
        ob_start();
    }

    public function captureStyleStop() {
        $this->captureStyle[] = ob_get_contents();
        ob_end_clean();
    }

    public function captureScriptStart() {
        ob_start();
    }

    public function captureScriptStop() {
        $this->captureScript[] = ob_get_contents();
        ob_end_clean();
    }
}
