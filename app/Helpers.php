<?php

function activeMenu($uri = '') {
    $active = '';
    if (Request::is(Request::segment(1) . '/' . $uri . '/*') || Request::is(Request::segment(1) . '/' . $uri) || Request::is($uri)) {
        $active = 'active';
    }
    return $active;
}

function setActive(string $path, string $class_name = "active")
{
    return Request::segment(1) === $path ? $class_name : "";
}

function formatRupiah($amount = 0)
{
    return 'Rp. '. number_format(intval($amount), 0, '', '.') . ',-';
}