<?php

if (!function_exists('esAdmin')) {
    function esAdmin()
    {
        return session('rol') === 'ADMIN';
    }
}

if (!function_exists('esAnalista')) {
    function esAnalista()
    {
        return session('rol') === 'ANALISTA';
    }
}