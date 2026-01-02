<?php

use Illuminate\Support\Facades\File;

if (! function_exists('magazine_base_path')) {
    function magazine_base_path(string $append = ''): string
    {
        $root = rtrim(base_path('../public_html/magazine'), '/');
        return $append ? $root.'/'.ltrim($append, '/') : $root;
    }
}

if (! function_exists('magazine_base_url')) {
    function magazine_base_url(string $append = ''): string
    {
        $base = rtrim(url('magazine'), '/');
        return $append ? $base.'/'.ltrim($append, '/') : $base;
    }
}

if (! function_exists('ensure_dir')) {
    function ensure_dir(string $path, int $mode = 0775): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, $mode, true, true);
        }
    }
}
