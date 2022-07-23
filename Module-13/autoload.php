<?php
function loaderEntities ($className)
{
    if(file_exists('entities/' . $className . '.php')){
        require_once 'entities/' . $className . '.php';
    }

}

function loaderInterfaces ($className)
{
    if(file_exists('interfaces/' . $className . '.php')){
        require_once 'interfaces/' . $className . '.php';
    }

}

spl_autoload_register('loaderEntities');
spl_autoload_register('loaderInterfaces');
