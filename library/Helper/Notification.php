<?php
/**
* Notification Helper
*/
class Helper_Notification {

public static function render($callBack){
        $allMessageFlash = Session_FlashMessenger::getInstance()->getAllMessages();
        foreach ($allMessageFlash as $type => $messages) {
             foreach ($messages as $message) {
                $callBack($type,$message);
            }
        }
}
}