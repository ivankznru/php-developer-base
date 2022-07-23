<?php
require_once 'interfaces/EventListenerInterface.php';
require_once 'interfaces/LoggerInterface.php';

abstract class Storage implements LoggerInterface, EventListenerInterface {
    static $dir = __DIR__. '\\test\\';
    protected $logs = [], $events = [];
    abstract function create(array $object);
    abstract function read(string $slug);
    abstract function update(string $slug, array $object);
    abstract function delete(string $slug);
    abstract function list();

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
    public function logMessage($error) {
        if(isset($this->events[__FUNCTION__])) {
            $this->{$this->events[__FUNCTION__]}();
        }
        $this->logs[] = $error;
        return true;
    }
    public function lastMessages($count) {
        if(isset($this->events[__FUNCTION__])) {
            $this->{$this->events[__FUNCTION__]}();
        }
        $out = [];
        for($i=1; $i<=$count; $i++) {
            $out[] = $this->logs[count($this->logs)-$i];
        }
        return $out;
    }
}
