<?php
interface LoggerInterface {
    public function logMessage($error);
    public function lastMessages($count);
}

interface EventListenerInterface {
    public function attachEvent($nameFunction, $callback);
    public function detouchEvent($nameFunction);
}

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

abstract class View {
    public $storage;
    public function __contructor(Storage $storage) {
        $this->storage = $storage;
    }
    abstract function displayTextById($id);
    abstract function displayTextByUrl($url);
}

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

class FileStorage extends Storage {
    public function create(array $object) {
        if(isset($this->events[__FUNCTION__])) {
            $this->{$this->events[__FUNCTION__]}();
        }
        $name = pathinfo($object['slug'], PATHINFO_FILENAME). '_'. date("m.d.Y");
        $ext = pathinfo($object['slug'], PATHINFO_EXTENSION) ? pathinfo($object['slug'], PATHINFO_EXTENSION) : 'txt';
        if(file_exists( Storage::$dir. $name. '.'. $ext)){
            $i = 1;
            while(true) {
                $object['slug'] = $name. '_'. $i. '.'. $ext;
                if(file_exists(Storage::$dir. $object['slug'])) {
                    $i++;
                } else {
                    break;
                }
            }
            file_put_contents(Storage::$dir. $object['slug'], serialize($object));
            return $object['slug'];
        }
        $object['slug'] = $name. '.'. $ext;
        file_put_contents(Storage::$dir. $object['slug'], serialize($object));
        return $object['slug'];
    }

    public function read(string $slug) {
        if(isset($this->events[__FUNCTION__])) {
            $this->{$this->events[__FUNCTION__]}();
        }
        if(file_exists(Storage::$dir. $slug)) {
            return unserialize(file_get_contents(Storage::$dir. $slug));
        }
        echo 'Объект не найден'. PHP_EOL;
    }

    public function update(string $slug, array $object){
        if(isset($this->events[__FUNCTION__])) {
            $this->{$this->events[__FUNCTION__]}();
        }
        if(file_exists(Storage::$dir. $slug)) {
            file_put_contents(Storage::$dir. $slug, serialize($object));
            return true;
        }
        echo 'Объект не найден'. PHP_EOL;
    }

    public function delete(string $slug) {
        if(isset($this->events[__FUNCTION__])) {
            $this->{$this->events[__FUNCTION__]}();
        }
        if(file_exists(Storage::$dir. $slug)) {
            unlink(Storage::$dir. $slug);
            return true;
        }
        echo 'Объект не найден'. PHP_EOL;
    }

    public function list() {
        if(isset($this->events[__FUNCTION__])) {
            $this->{$this->events[__FUNCTION__]}();
        }
        $allFiles = scandir(Storage::$dir);
        $allObject = [];
        $i = 0;
        foreach ($allFiles as $key => $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            $allObject[$i++]= unserialize(file_get_contents(Storage::$dir. $value));
        }
        return $allObject;
    }
}

class Text {
    public $arrayText = [];
    public function __construct(string $author, string $slug)
    {
        $this->arrayText['author'] = $author;
        $this->arrayText['slug'] = $slug;
        $this->arrayText['published'] = date("Y-m-d H:i:s");
    }

    public function storeText() {
        file_put_contents($this->arrayText['slug'], serialize($this->arrayText));
    }

    public function loadText() {
        $file = file_get_contents($this->arrayText['slug']);
        if($file) {
            $this->arrayText = unserialize($file);
            return $this->arrayText['text'];
        }
        echo 'Файл пуст'.PHP_EOL;
    }

    public function editText(string $title, string $text) {
        $this->arrayText['title'] = $title;
        $this->arrayText['text'] = $text;
    }
}
