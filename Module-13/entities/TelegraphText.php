<?php
class TelegraphText
{
    private $title;// заголовок текста;
    private $text; //текст;
    private $author;// имя автора;
    private $published;// дата и время последнего изменения текста;
    public $slug;//уникальное имя для файла, в котором будет храниться текст (например, test_text_file).

    public function __construct($author, $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date("d.m.Y H:i:s");
    }

    public function storeText($text)
    {
        $data = array('text' => $text, 'title' => $this->title, 'author' => $this->author, 'published' => $this->published);
        file_put_contents($this->slug, serialize($data));
    }

    public function loadText($slug)
    {
        if (filesize($this->slug) > 0) {
            $data = unserialize(file_get_contents($slug));
            $this->title = $data['title'];
            $this->text = $data['text'];
            $this->author = $data['author'];
            $this->published = $data['published'];
        }
        return $this->text;
    }

    public function editText(string $title, string $text)
    {
        $this->title = $title;
        $this->text = $text;
    }
}
