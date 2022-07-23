<?php

class Text {
    private $arrayText = [];
    public function __construct(string $author, string $slug)
    {
        $this->arrayText['author'] = $author;
        $this->arrayText['slug'] = $slug;
        $this->arrayText['published'] = date("Y-m-d H:i:s");
    }

    private function storeText() {
        file_put_contents($this->arrayText['slug'], serialize($this->arrayText));
    }

    private function loadText() {
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

    public function __set($name, $value) {
        if(isset($this->$name)) {
            return false;
        }

        if($name === 'text') {
            $this->storeText();
        }

        if($name === 'slug' && pathinfo($value, PATHINFO_FILENAME) && pathinfo($value, PATHINFO_EXTENSION)) {
            preg_match_all('/[a-z0-9_.]/i',$value,$matches);
            if(count($matches)==mb_strlen($value)){
                $this->$name = $value;
                return true;
            }
            echo 'Название файла содержит запрещённые символы';
            return false;
        } elseif ($name === 'slug') {
            echo 'Проблема с базовой формой названия файла';
            return false;
        }

        if($name === 'published') {
            if(strtotime($value)>=strtotime(date("Y-m-d H:i:s"))){
                $this->$name = date("Y-m-d H:i:s",strtotime($value));
                return true;
            }
            echo 'Published меньше текущего времени';
            return false;
        }

        if($name === 'author' && mb_strlen($value)<=120) {
            $this->$name = $value;
            return true;
        } elseif ($name === 'author' && mb_strlen($value)>120) {
            echo 'Value больше 120 символов'. PHP_EOL;
            return false;
        }
        return false;
    }

    public function __get($name) {
        if(isset($this->$name)) {
            return false;
        }

        if($name === 'text') {
            $this->loadText();
        }
        return $this->$name;
    }
}

