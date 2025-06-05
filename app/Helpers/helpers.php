<?php

use App\Models\Translations;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

if (!function_exists('translate')) {
    function translate($key)
    {
        return Cache::rememberForever("translation_{$key}_" . app()->getLocale(), function () use ($key) {
            $translation = Translations::where('key', $key)->first();
            return $translation->translations[app()->getLocale()] ?? $key;
        });
    }
}

if (!function_exists('descriptionProduct')) {
    function descriptionProduct($string, $length = 50)
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length).'...';
        }
        return $string;
    }
}
if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes, $decimals = 2) 
    {
        $size = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
    }
}

if (!function_exists('aboutUs')) {
    function aboutUs() 
    {
        return DB::table('about_us')->get();
    }
}