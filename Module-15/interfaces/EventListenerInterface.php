<?php

interface EventListenerInterface
{
    public function attachEvent($className, callable $callback);
    public function detouchEvent( $className);
}
