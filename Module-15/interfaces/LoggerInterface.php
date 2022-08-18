<?php

interface LoggerInterface
{
    public function logMessage($error);
    public function lastMessages($num);
}
