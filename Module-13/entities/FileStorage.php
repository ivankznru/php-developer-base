<?php
require_once 'Storage.php';
require_once 'Text.php';

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
