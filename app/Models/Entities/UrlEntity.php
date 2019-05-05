<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UrlEntity
 * @property string url
 * @property string code
 * @property integer id
 * @property string|null title
 * @property string|null description
 * @property string created_at
 * @package App\Models\Entities
 */
class UrlEntity extends Model
{
    /**
     * @var string
     */
    protected $table = 'urls';

    /**
     * @var array
     */
    protected $fillable = ['url', 'code',];

    /**
     * @return false|string
     */
    public function getCreatedAtFormatAttribute()
    {
        return date('d.m.Y H:i', strtotime($this->created_at));
    }

    /**
     * @return string
     */
    public function getShortUrlAttribute()
    {
        return route('url.redirect', ['code' => $this->code], true);
    }
}