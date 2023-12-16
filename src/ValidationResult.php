<?php

namespace Basko\Validation;

/**
 * @template T
 * @template E
 */
final class ValidationResult
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var T|null
     */
    private $data;

    /**
     * @var E[]
     */
    private $errorMessages;

    /**
     * @param bool $isValid
     * @param T|null $data
     * @param E[] $errorMessages
     */
    private function __construct($isValid, $data, array $errorMessages) {
        $this->isValid = $isValid;
        $this->data = $data;
        $this->errorMessages = $errorMessages;
    }

    protected function assertReturnType($result)
    {
        if (!$result instanceof self) {
            throw new \LogicException(\sprintf(
                "Return type should be '%s', got '%s'",
                self::class,
                \is_object($result) ? \get_class($result) : \gettype($result)
            ));
        }
    }

    /**
     * @param T $data
     * @return \Basko\Validation\ValidationResult
     */
    public static function valid($data)
    {
        return new self(true, $data, []);
    }

    /**
     * @param E[] $messages
     * @return \Basko\Validation\ValidationResult
     */
    public static function errors(array $messages)
    {
        return new self(false, null, $messages);
    }

    /**
     * @param callable(T): void $valid
     * @param callable(E[]): void $errors
     */
    public function match(callable $valid, callable $errors) {
        if ($this->isValid) {
            \call_user_func($valid, $this->data);
        } else {
            \call_user_func($errors, $this->errorMessages);
        }
    }

    /**
     * @template B
     * @param callable(T): B $f
     * @return \Basko\Validation\ValidationResult
     */
    public function map(callable $f)
    {
        if ($this->isValid) {
            return self::valid(\call_user_func($f, $this->data));
        }

        return self::errors($this->errorMessages);
    }

    /**
     * @template B
     * @param callable(T): B $f
     * @return \Basko\Validation\ValidationResult
     */
    public function mapErrors(callable $f)
    {
        if ($this->isValid) {
            return self::valid($this->data);
        }

        return self::errors(\call_user_func($f, $this->errorMessages));
    }

    /**
     * @param callable(T): \Basko\Validation\ValidationResult $f
     * @return \Basko\Validation\ValidationResult
     */
    public function flatMap(callable $f)
    {
        if ($this->isValid) {
            $result = \call_user_func($f, $this->data);
        } else {
            $result = self::errors($this->errorMessages);
        }

        $this->assertReturnType($result);

        return $result;
    }

    /**
     * @param callable(T): \Basko\Validation\ValidationResult $f
     * @return \Basko\Validation\ValidationResult
     */
    public function flatMapErrors(callable $f)
    {
        if ($this->isValid) {
            $result = self::valid($this->data);
        } else {
            $result = \call_user_func($f, $this->errorMessages);
        }

        $this->assertReturnType($result);

        return $result;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * @return E[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return E
     */
    public function getLastErrorMessage()
    {
        return end($this->errorMessages);
    }
}
