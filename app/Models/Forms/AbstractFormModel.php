<?php

namespace App\Models\Forms;

use Illuminate\Support\Facades\Validator;

/**
 * Class AbstractFormModel
 * @package App\Models\Forms
 */
abstract class AbstractFormModel
{
    protected $attributes;

    protected $errors;

    public function __construct()
    {
        $this->registerValidators();
    }

    protected function registerValidators(){}

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $validator = Validator::make($this->attributes, $this->rules(), $this->messages());
        $this->setErrors($validator->errors()->getMessages());

        return ! $validator->fails();
    }

    /**
     * @param array $data
     */
    public function setAttributes(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
                $this->attributes[$key] = $value;
            }
        }

        $this->attributes['ip_address'] = request()->ip();
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->getAttributes();
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function addError($errorType, $errorMsg)
    {
        $this->errors[$errorType][] = $errorMsg;
    }

    protected function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}