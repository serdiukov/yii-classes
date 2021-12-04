<?php

namespace serdiukov\yii\exceptions;


class ValidateException extends \RuntimeException
{
    /**
     * @var array
     */
    protected $errors = [];
    /**
     * @var string
     */
    protected $errorName = 'VALIDATE_ERROR';

    /**
     * @param string $name
     * @return $this
     */
    public function setErrorName(string $name)
    {
        $this->errorName = $name;
        return $this;
    }

    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors = [])
    {
        $this->errors = $errors;

        if ($this->message == $this->errorName) {
            array_unshift($errors, $this->message);
            $this->message = serialize($errors);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}
