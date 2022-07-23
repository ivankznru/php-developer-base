<?php
abstract class View {
    public $storage;
    public function __contructor(Storage $storage) {
        $this->storage = $storage;
    }
    abstract function displayTextById($id);
    abstract function displayTextByUrl($url);
}
