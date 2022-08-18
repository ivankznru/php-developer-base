<?php

require_once 'autoload.php';

const STORAGE_DIR = 'storage';
if (!file_exists('storage')) {
    mkdir('storage');
}

$newObject = new FileStorage();

//$newObject->backup();
$newObject->restoreBackup('backup_18-08-2022_13-34-45.json');


$newPost = new TelegraphText('Иван', 'test_text_file');
// $newPost->text = 'fgdfgdfgdfgdfgdfg';
// $newPost->editText('Первый пост', 'Первый текст');
// print_r($newPost);
// $newPost->storeText();
// echo $newPost->loadText();
// $newPost->published = '12.08.2022';
// echo $newPost->published;

// Создание
// $te = new TelegraphText('Иван', 'testt');
// $test = new FileStorage();
// $test->create($te);

// Чтение
// print_r($test->read('test_2022-08-08'));

// Изменение
// $test->update('test_2022-08-08', $te);

// Удаление
// $test->delete('test_2022-08-08');

// Список всех файлов
// print_r($test->list());
