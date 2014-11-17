<?php

/**
 * bootstrap
 * toute les methodes public initXXX (ou XXX peut être n'importe quoi) seront appelée
 */

class Bootstrap extends Mo_Bootstrap
{

    public function initDate() {
        date_default_timezone_set("Europe/Paris");
    }

    public function initDefaultModule() {
        Application::getInstance()->setModuleName("front");
    }

    public function initCache() {
    }

    public function initTokenPiwik() {
    }
}
