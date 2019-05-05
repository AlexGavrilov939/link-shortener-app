<?php

namespace App\Models;

use App\Models\Entities\UrlEntity;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class UrlModel
 * @package App\Models
 */
class UrlModel
{
    /**
     * @param $urlId
     * @return Builder|Model|object|null
     */
    public static function getById($urlId)
    {
        return UrlEntity::query()
            ->where(['id' => $urlId])
            ->first();
    }

    /**
     * @param $code
     * @return Builder|Model|object|null
     */
    public static function getByCode($code)
    {
        return UrlEntity::query()
            ->where(['code' => $code])
            ->first();
    }

    /**
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public static function getPaginationList($limit = 10)
    {
        $query = UrlEntity::query()
            ->orderBy('id', 'desc')
            ->select(['id', 'url', 'code', 'counter', 'created_at']);

        return $query->paginate($limit);
    }

    /**
     * @param UrlEntity $urlEntity
     * @param array $urlData
     * @return mixed
     */
    public static function push(UrlEntity $urlEntity, array $urlData = [])
    {
        $urlEntity->url = $urlData['url'];
        $urlEntity->code = $urlData['code'];
        $urlEntity->save();

        return $urlEntity;
    }

    /**
     * @param UrlEntity $urlEntity
     * @return bool|null
     */
    public static function remove(UrlEntity $urlEntity)
    {
        $status = false;
        try {
            $status = $urlEntity->delete();
        } catch (Exception $e) {}

        return $status;
    }

    /**
     * @param int $hashLength
     * @return bool|string
     */
    public static function generateHash($hashLength = 7)
    {
        $chars = str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', $hashLength);
        return substr(str_shuffle($chars), 0, $hashLength);
    }
}