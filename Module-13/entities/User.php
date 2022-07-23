<?php
require_once 'interfaces/EventListenerInterface.php';

abstract class User implements EventListenerInterface {
    public $id, $name, $role;
    protected $events = [];
    abstract function getTextsToEdit($id);
    public function attachEvent($nameFunction, $callback){
        if(method_exists($this, $nameFunction)) {
            $this->events[$nameFunction] = $callback;
            return true;
        }
        return false;
    }
    public function detouchEvent($nameFunction) {
        if(isset($this->events[$nameFunction])) {
            unset($this->events[$nameFunction]);
            return true;
        }
        return false;
    }
}
