<?php

namespace App\Models\Forms;

use Illuminate\Support\Facades\Validator;

/**
 * Class UrlForm
 * @package App\Models\Forms
 */
class UrlForm extends AbstractFormModel
{
    public $url;
    public $code;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'url' => ['required', 'string', 'is_valid_url'],
            'code' => ['string', 'unique:urls,code']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'url.required' => 'Url param is mandatory',
            'url.string' => 'Wrong url format',
            'url.is_valid_url' => 'Not valid input url',
            'code.string' => 'Wrong code format',
            'code.unique' => 'Code must be unique'
        ];
    }

    protected function registerValidators()
    {
        Validator::extend('is_valid_url', function($attribute, $url, $parameters) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        });
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'url' => $this->url,
            'code' => $this->code
        ];
    }

    /**
     * @param int $hashLength
     * @return bool|string
     */
    private static function generateHash($hashLength = 8)
    {
        $chars = str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', $hashLength);
        return substr(str_shuffle($chars), 0, $hashLength);
    }
}