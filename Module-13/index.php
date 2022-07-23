<?php
include_once 'autoload.php';


$objectOne =array();
$objectFSOne = new FileStorage();
$objectFSOne->create($objectOne);
$objectFSOne->delete('_07.21.2022_1.txt');


