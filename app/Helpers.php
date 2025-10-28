<?php


use App\Service\SessionMessageFlasher;

if (! function_exists('flash')) {
    function flash(): SessionMessageFlasher
    {
        return SessionMessageFlasher::getInstance();
    }
}
