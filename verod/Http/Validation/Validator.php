<?php

namespace Midun\Http\Validation;

use Midun\Http\Request;
use Midun\Traits\Validator\Verify;

class Validator
{
    use Verify;

    /**
     * List of accept rules
     * 
     * @var array
     */
    protected $rules = [];

    /**
     * List of custom rules
     * 
     * @var array
     */
    protected $customRules = [];

    /**
     * List of custom messages
     * 
     * @var array
     */
    protected $customMessages = [];

    /**
     * List of custom messages
     * 
     * @var array
     */
    protected $messages = [];

    /**
     * Flag checking failed request
     * 
     * @var bool
     */
    protected $isFailed = false;

    /**
     * List of errors message
     * 
     * @var array
     */
    protected $failedMessages = [];

    /**
     * Instance of passable request
     * 
     * @var array
     */
    protected $passable;

    /**
     * File validation using for multiple languages
     * 
     * @var string
     */
    protected $validationFile = 'validation';

    /**
     * Current parameter working
     * 
     * @var string
     */
    protected $current;

    /**
     * Specific character used to parse validation
     * 
     * @var string
     */
    const SPECIFIC_SEPARATOR = '.';

    /**
     * Get list of available rules
     * 
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * Get list of custom messages
     * 
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Set validation file
     * 
     * @param string $file
     * 
     * @return void
     */
    public function setValidateFile(string $file)
    {
        $this->validationFile = $file;
    }

    /**
     * Push one rule to list of rules
     * 
     * @param string $rule
     * @param \Closure $handle
     * @param string $message
     * 
     * @return void
     */
    public function setRule(string $rule, \Closure $handle, string $message = '')
    {
        $this->customRules[$rule] = $handle;
        $this->customMessages[$rule] = $message;
    }

    /**
     * Replace all list of rule
     * 
     * @param array $rules
     * 
     * @return void
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Check request is failed
     * 
     * @return bool
     */
    public function isFailed()
    {
        return $this->isFailed;
    }

    /**
     * Check request is successfully
     * 
     * @return bool
     */
    public function isSucceeded()
    {
        return !$this->isFailed();
    }

    /**
     * Get list of errors message
     * 
     * @return array
     */
    public function errors()
    {
        return $this->failedMessages;
    }

    /**
     * Set list of parameters
     * 
     * @param \Midun\Http\Request $passable
     * 
     * @return void
     */
    public function setPassable(Request $passable)
    {
        $this->passable = $passable;
    }

    /**
     * Set list of custom message
     * 
     * @param array $messages
     * 
     * @return void
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * Check isset on custom rules
     * 
     * @param string $rule
     * 
     * @return bool
     */
    public function isCustom(string $rule)
    {
        return isset($this->customRules[$rule]);
    }

    /**
     * Get handle custom rule
     * 
     * @param string $rule
     * 
     * @return \Closure
     */
    public function getCustom(string $rule)
    {
        return $this->customRules[$rule];
    }

    /**
     * Making request validation
     * 
     * @param \Midun\Http\Request $request
     * @param array $validateRules
     * @param array $messages
     * 
     * @return self
     * 
     * @throws ValidationException
     */
    public function makeValidate(Request $request, array $validateRules, array $messages = [])
    {
        $this->setPassable($request);
        $this->setMessages($messages);

        foreach ($validateRules as $param => $rules) {
            $rules = explode('|', $rules);
            $ruleValue = null;

            foreach ($rules as $rule) {
                if (strpos($rule, ':') !== false) {
                    list($rule, $ruleValue) = explode(':', $rule);
                }

                if (!in_array($rule, $this->rules()) && !$this->isCustom($rule)) {
                    throw new ValidationException("Rule {$rule} is not valid.");
                }
                $this->current = $param;
                $this->verify($rule, $rules, $ruleValue);
            }
        }

        return $this;
    }

    /**
     * Verify param by param of request
     * 
     * @param string $rule
     * @param array $rules
     * @param mixed $ruleValue
     * 
     * @throws ValidationException
     * 
     * @return void
     */
    public function verify(string $rule, array $rules, $ruleValue)
    {
        $value = isset($this->passable->all()[$this->current]) ? $this->passable->all()[$this->current] : null;

        switch (true) {
            case $rule === 'required':
            case $rule === 'number':
            case $rule === 'string':
            case $rule === 'file':
            case $rule === 'image':
            case $rule === 'video':
            case $rule === 'audio':
            case $rule === 'email':
                $this->$rule($value);
                break;
            case $rule === 'min':
            case $rule === 'max':
                $this->$rule($value, $rules, $ruleValue);
                break;
            case $rule === 'unique':
                $this->$rule($value, $ruleValue);
                break;
            case $this->isCustom($rule):
                $this->handleCustomRule($rule);
                break;
            default:
                throw new ValidationException("The rule {$rule} is not supported !");
        }

        if (!empty($this->errors())) {
            $this->makeFailed();
        }
    }

    /**
     * Build error message
     * 
     * @param string|array $param
     * @param string $rule
     * @param array $options = []
     * 
     * @return string
     */
    public function buildErrorMessage($param, string $rule, array $options = [])
    {
        if (is_array($param)) {
            list($param, $type) = $param;
        }
        $declaringMessage = $this->getDeclaringMessage($param, $rule);

        $msg =
            !is_null($declaringMessage)
            ? $declaringMessage
            : trans(
                $this->validationFile . Validator::SPECIFIC_SEPARATOR . $rule . (isset($type)
                    ? Validator::SPECIFIC_SEPARATOR . $type
                    : ''),
                array_merge($options, [
                    'attribute' => $param
                ])
            );

        return is_string($msg) ? $msg : json_encode($msg);
    }

    /**
     * Push to global error messages
     * 
     * @param string $key
     * @param string $message
     * 
     * @return void
     */
    public function pushErrorMessage(string $key, string $message)
    {
        $this->failedMessages[$key][] = $message;
    }

    /**
     * Get isset error messages registered
     * 
     * @param string $param
     * @param string $rule
     * 
     * @return void
     */
    public function getDeclaringMessage($param, $rule)
    {
        $currentMessageKey = $param . Validator::SPECIFIC_SEPARATOR . $rule;
        return isset($this->messages[$currentMessageKey]) ? $this->messages[$currentMessageKey] : null;
    }

    /**
     * Make this request is failed
     * 
     * @return void
     */
    public function makeFailed()
    {
        $this->isFailed = true;
    }
}
