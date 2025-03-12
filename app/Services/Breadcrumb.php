<?php

namespace App\Services;

class Breadcrumb
{
    public static function generate($hierarchy)
    {
        $breadcrumb = [];

        foreach ($hierarchy as $item) {
            $breadcrumb[] = [
                'name' => $item['name'],
                'url' => $item['url'],
            ];
        }

        return $breadcrumb;
    }
}