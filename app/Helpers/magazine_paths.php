<?php

use Illuminate\Support\Facades\File;

if (! function_exists('magazine_base_path')) {
    function magazine_base_path(string $append = ''): string
    {
       // $root = rtrim(base_path('../public_html/magazine'), '/');
               $configuredRoot = env('MAGAZINE_PUBLIC_PATH', base_path('../public_html'));
        $root = rtrim($configuredRoot, '/');
        return $append ? $root.'/'.ltrim($append, '/') : $root;
    }
}

if (! function_exists('magazine_base_url')) {
    function magazine_base_url(string $append = ''): string
    {
        //$base = rtrim(config('app.url'), '/') . '/magazine';
         $configuredUrl = env('MAGAZINE_PUBLIC_URL', rtrim(config('app.url'), '/'));
        $base = rtrim($configuredUrl, '/');

        if ($append && preg_match('#^https?://#i', $append)) {
            return $append;
        }
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
