<?php
interface EventListenerInterface {
    public function attachEvent($nameFunction, $callback);
    public function detouchEvent($nameFunction);
}

