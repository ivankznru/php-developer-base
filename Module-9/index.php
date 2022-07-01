<?php

abstract class Storage {

    static $dir = __DIR__. '\\test\\';
    abstract function create(array $object);
    abstract function read(string $slug);
    abstract function update(string $slug, array $object);
    abstract function delete(string $slug);
    abstract function list();
}

abstract class View {
    public $storage;
    public function __contructor(Storage $storage) {
        $this->storage = $storage;
    }
    abstract function displayTextById($id);
    abstract function displayTextByUrl($url);
}

abstract class User {
    public $id, $name, $role;
    abstract function getTextsToEdit($id);
}

class FileStorage extends Storage {
    public function create(array $object) {
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
        if(file_exists(Storage::$dir. $slug)) {
            return unserialize(file_get_contents(Storage::$dir. $slug));
        }
        echo 'Объект не найден'. PHP_EOL;
    }

    public function update(string $slug, array $object){
        if(file_exists(Storage::$dir. $slug)) {
            file_put_contents(Storage::$dir. $slug, serialize($object));
            return true;
        }
        echo 'Объект не найден'. PHP_EOL;
    }

    public function delete(string $slug) {
        if(file_exists(Storage::$dir. $slug)) {
            unlink(Storage::$dir. $slug);
            return true;
        }
        echo 'Объект не найден'. PHP_EOL;
    }

    public function list() {
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

$text = new Text('Test', 'test1');
$text->editText('title test1', 'text test1');

$storage = new FileStorage();
$storage->create($text->arrayText);
