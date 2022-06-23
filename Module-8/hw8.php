<?php

class TelegraphText {
private $title ;// заголовок текста;
private $text; //текст;
private $author ;// имя автора;
private $published ;// дата и время последнего изменения текста;
private $slug ;//уникальное имя для файла, в котором будет храниться текст (например, test_text_file).
private $data = [];

//3.Опишите конструктор для класса TelegraphText. При создании объекта данного класса будет передаваться имя автора и
// уникальное имя файла. В конструкторе задайте соответствующие значения полям $author,$slug и $published.
// Присвойте текущее время и дату полю $published.

public function __construct($author,$slug){
    $this -> author = $author;
    $this -> slug = $slug;
    $this -> published = date("d.m.Y H:i:s");
}


//4. Опишите метод storeText для записи текста в файл.
// Создайте ассоциированный массив с ключами text, title, author, published и присвойте соответствующим
// элементам значения полей $title, $text, $author и $published.
// Сериализуйте массив с помощью встроенной функции serialize и запишите его в файл. Имя файла хранится в поле $slug.

 public function storeText ($text){
    $data = array('text'=>$text, 'title'=>$this->title, 'author'=>$this->author, 'published'=>$this->published);
    file_put_contents($this->slug, serialize($data));
 }


//5. Опишите метод loadText для загрузки текста из файла. Имя файла хранится в поле $slug.
// По аналогии с методом storeText десереализуйте содержимое файла (если файл не пуст) в массив,
// а затем присвойте полям $title, $text, $author и $published значения соответствующих элементов массива.
// Метод должен возвращать $text.

    public function loadText ($slug)
    {
        if (filesize($this->slug) > 0)
    {
    $data = unserialize(file_get_contents($slug));
    $this->title = $data['title'];
    $this->text = $data['text'];
    $this->author = $data['author'];
    $this->published = $data['published'];
    }
     return $this->text;
    }


//6. Опишите метод editText для редактирования текста.
// Метод должен принимать текстовые параметры: заголовок и текст.
// Метод назначает новые значения полям $text и $title.

    public function editText (string $title, string $text){
    $this->title = $title;
    $this->text = $text;
    }

}
//7. Создайте объект класса TelegraphText, передав необходимые для конструктора параметры.
$objectOne = new TelegraphText('Me','test_text_file' );
//8. Вызовите методы editText, а затем storeText.
$objectOne->editText('test_text_file', 'мой текст');
$objectOne->storeText("Never new I could feel like this \nLike I never see in the sky before");
//9. Вызовите метод loadText и выведите значение, которое вернёт этот метод, на экран.
echo ($objectOne->loadText('test_text_file'));
