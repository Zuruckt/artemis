<?php


use JetBrains\PhpStorm\NoReturn;

if (!function_exists('dd')) {
    #[NoReturn] function dd(): void
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
        die();
    }
}