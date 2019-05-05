<?php

use App\Models\UrlModel;

Route::bind('url_id', function($urlId) {
    $urlEntity = UrlModel::getById($urlId);
    if ( ! $urlEntity) {
        abort(404);
    }

    return $urlEntity;
});