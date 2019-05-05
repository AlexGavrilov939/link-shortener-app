<?php

if ( ! function_exists('assetStatic')) {
    function assetStatic($path) {
        $httpsEnable = config('app.https');
        $staticVersion = config('app.static_version');
        $url = app('url')->asset($path, $httpsEnable);

        return "{$url}?v={$staticVersion}";
    }
}