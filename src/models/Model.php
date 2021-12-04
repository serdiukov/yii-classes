<?php

namespace serdiukov\yii\models;


use serdiukov\yii\exceptions\ValidateException;

abstract class Model extends \yii\base\Model
{
    public static function getInstance()
    {
        return \Yii::createObject(static::class);
    }

    public function loadValidate(array $params = []) : bool
    {
        if ($this->load($params, '') && $this->validate()) {
            return true;
        }

        return false;
    }

    public function afterValidate() : void
    {
        parent::afterValidate();

        if ($this->errors) {
            throw (new ValidateException('VALIDATE_ERROR', 400))
                ->setErrors($this->errors);
        }
    }
}
