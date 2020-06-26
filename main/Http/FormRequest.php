<?php

namespace Main\Http;

use Main\Http\Exceptions\AppException;
use Main\Http\Exceptions\ValidateException;
use Main\Services\File;

abstract class FormRequest extends Request
{
    /**
     * Flag checking failed request
     * @type boolean
     */
    private $isFailed = false;

    /**
     * List of failed messages
     * @type array
     */
    private $fails = [];

    /**
     * Overriding parent __construct method
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Abstract function for overriding
     * verify authorize
     *
     * @return boolean
     */
    abstract public function authorize();

    /**
     * Abstract function for overriding
     * setting up rules
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Abstract function for overriding
     * setting up messages
     *
     * @return array
     */
    abstract public function messages();

    /**
     * Execute verify request method
     *
     * @return void
     */
    public function executeValidate()
    {
        if (!$this->authorize()) {
            throw new AppException("This request is not authorized !", 401);
        }
        foreach ($this->rules() as $ruleKey => $ruleValue) {
            if (in_array($ruleKey, $this->all()) || empty($this->all()[$ruleKey]) || $this->all()[$ruleKey] === '') {
                $this->makeFails();
                $this->fails[$ruleKey][] = $this->buildErrorMsg($ruleKey);
                continue;
            }
            foreach ($this->all() as $paramKey => $paramValue) {
                if ($paramKey === $ruleKey) {
                    $this->handleValidate($paramValue, $ruleValue, $ruleKey);
                }
            }
        }
        if ($this->isFailed()) {
            app()->fails = $this->fails();
            throw new ValidateException("Unprocessable Entity.", 422);
        }
    }

    /**
     * Handle validation parameters
     *
     * @param string $value
     * @param array $rules
     * @param string $ruleKey
     *
     * @return void
     */
    private function handleValidate($value, $rules, $ruleKey)
    {
        $rules = explode('|', $rules);
        $errors = [];
        foreach ($rules as $key => $rule) {
            $fails = false;
            $currentRule = $rule;
            if (strpos($rule, ':') !== false) {
                $rule = explode(':', $rule);
                $rule = array_shift($rule);
            }
            switch ($rule) {
                case 'required':
                    $fails = $this->handleValidateRequired($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'min':
                    $fails = $this->handleValidateMin($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'max':
                    $fails = $this->handleValidateMax($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'number':
                    $fails = $this->handleValidateNumber($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'string':
                    $fails = $this->handleValidateString($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'file':
                    $fails = $this->handleValidateFile($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'image':
                    $fails = $this->handleValidateImage($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'video':
                    $fails = $this->handleValidateVideo($ruleKey, $value, $currentRule, $rules);
                    break;
                case 'audio':
                    $fails = $this->handleValidateAudio($ruleKey, $value, $currentRule, $rules);
                    break;
                default:
                    throw new AppException("The rule {$rule} is not supported !");
            }
            if ($fails) {
                array_push($errors, $fails);
            }
        }
        if (!empty($errors)) {
            $this->makeFails();
            $this->fails[$ruleKey] = $errors;
        }
    }

    /**
     * Build error message for failed request parameters
     *
     * @param string $ruleKey
     * @param string $rule
     * @param string $value
     *
     * @return string
     */
    private function buildErrorMsg($ruleKey, $rule = 'required', $value = null)
    {
        $type = false;
        switch ($rule) {
            case 'required':
                $endpoint = 'required';
                break;
            case 'min_num':
                $endpoint = 'min';
                $type = 'number';
                break;
            case 'min_str':
                $endpoint = 'min';
                $type = 'string';
                break;
            case 'max_num':
                $endpoint = 'max';
                $type = 'number';
                break;
            case 'max_str':
                $endpoint = 'max';
                $type = 'string';
                break;
            case 'number':
                $endpoint = 'number';
                break;
            case 'string':
                $endpoint = 'string';
                break;
            case 'file':
                $endpoint = 'file';
                break;
        }
        return $this->fetchMessage($ruleKey, $endpoint, $type, $value);
    }

    /**
     * Fetching message from compare endpoint
     * 
     * @param string $ruleKey
     * @param string $endpoint
     * @param string $type
     * 
     * @return string
     */
    private function fetchMessage($ruleKey, $endpoint, $type, $endpointValue = null)
    {
        if (isset($this->messages()["$ruleKey.$endpoint"])) {
            return $this->messages()["$ruleKey.$endpoint"];
        }
        if ($type) {
            return trans("validation.$endpoint.$type", ['attribute' => $ruleKey, $endpoint => $endpointValue]);
        }
        return trans("validation.$endpoint", ['attribute' => $ruleKey]);
    }

    /**
     * Handle validate for required rule.
     *
     * @param string $ruleKey
     * @param string $value
     *
     * @return void/string
     */
    private function handleValidateRequired($ruleKey, $value)
    {
        if (!(isset($value) && $value !== '' && $value !== null && !empty($value))) {
            return $this->buildErrorMsg($ruleKey, 'required');
        }
    }

    /**
     * Handle validate for min rule.
     *
     * @param string $ruleKey
     * @param string $value
     * @param string $ruleValue
     * @param array $rules
     *
     * @return void/string
     */
    private function handleValidateMin($ruleKey, $value, $ruleValue, $rules)
    {
        list($notUsing, $min) = explode(':', $ruleValue);
        if (in_array('number', $rules)) {
            if ((integer) $min > (integer) $value) {
                return $this->buildErrorMsg($ruleKey, 'min_num', $min);
            }
        } else {
            if (strlen((string) $value) < $min) {
                return $this->buildErrorMsg($ruleKey, 'min_str', $min);
            }
        }
    }

    /**
     * Handle validate for min rule.
     *
     * @param string $ruleKey
     * @param string $value
     * @param string $ruleValue
     * @param array $rules
     *
     * @return void/string
     */
    private function handleValidateMax($ruleKey, $value, $ruleValue, $rules)
    {
        list($notUsing, $max) = explode(':', $ruleValue);
        if (in_array('number', $rules)) {
            if ((integer) $max < (integer) $value) {
                return $this->buildErrorMsg($ruleKey, 'max_num', $max);
            }
        } else {
            if (strlen((string) $value) > $max) {
                return $this->buildErrorMsg($ruleKey, 'max_str', $max);
            }
        }
    }

    /**
     * Handle validate for number rule.
     *
     * @param string $ruleKey
     * @param string $value
     *
     * @return void/string
     */
    private function handleValidateNumber($ruleKey, $value)
    {
        if (!is_numeric($value)) {
            return $this->buildErrorMsg($ruleKey, 'number');
        }
    }

    /**
     * Handle validate for string rule.
     *
     * @param string $ruleKey
     * @param string $value
     * @param string $ruleValue
     * @param array $rules
     *
     * @return void/string
     */
    private function handleValidateString($ruleKey, $value)
    {
        if (!is_string($value)) {
            return $this->buildErrorMsg($ruleKey, 'string');
        }
    }

    /**
     * Handle validate for file rule.
     *
     * @param string $ruleKey
     * @param string $value
     *
     * @return void/string
     */
    private function handleValidateFile($ruleKey, $value)
    {
        if (!$value instanceof File) {
            return $this->buildErrorMsg($ruleKey, 'file');
        }
    }

    /**
     * Handle validate for image rule.
     *
     * @param string $ruleKey
     * @param string/object $value
     *
     * @return void/string
     */
    private function handleValidateImage($ruleKey, $value)
    {
        if (!$value instanceof File || strpos($value->type, 'image/') !== true) {
            return $this->buildErrorMsg($ruleKey, 'file');
        }
    }

    /**
     * Handle validate for image rule.
     *
     * @param string $ruleKey
     * @param string/object $value
     *
     * @return void/string
     */
    private function handleValidateVideo($ruleKey, $value)
    {
        if (!$value instanceof File || strpos($value->type, 'video/') !== true) {
            return $this->buildErrorMsg($ruleKey, 'video');
        }
    }

    /**
     * Handle validate for image rule.
     *
     * @param string $ruleKey
     * @param string/object $value
     *
     * @return void/string
     */
    private function handleValidateAudio($ruleKey, $value)
    {
        if (!$value instanceof File || strpos($value->type, 'audio/') !== true) {
            return $this->buildErrorMsg($ruleKey, 'audio');
        }
    }

    /**
     * Checking if successfully method
     *
     * @return boolean
     */
    public function isSucceeded()
    {
        return !$this->isFailed;
    }

    /**
     * Checking if failed method
     *
     * @return boolean
     */
    public function isFailed()
    {
        return $this->isFailed;
    }

    /**
     * Get list of failed messages
     *
     * @return array
     */
    public function fails()
    {
        return $this->fails;
    }

    /**
     * Make this request failed
     *
     * @return void
     */
    private function makeFails()
    {
        $this->isFailed = true;
    }
}
