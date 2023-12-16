<?php

namespace Basko\Validation;

abstract class GroupValidation implements ValidationInterface
{
    /**
     * @var ValidationInterface[]
     */
    protected $validations;

    /**
     * Constructor for GroupValidation.
     *
     * ```php
     * new AndValidation(new Validation1(), new Validation2());
     * new AndValidation([new Validation1(), new Validation2()]);
     * ```
     *
     * @param mixed $validations Either an array of ValidationInterface or individual ValidationInterface objects.
     * @throws \InvalidArgumentException If any item in the specifications array is not an instance
     *                                    of the Specification class.
     */
    public function __construct($validations)
    {
        $validations = $this->flatten(\func_get_args());

        foreach ($validations as $validation) {
            if (!$validation instanceof ValidationInterface) {
                throw new \InvalidArgumentException(sprintf(
                    "Expected '%s', got '%s'",
                    ValidationInterface::class,
                    gettype($validation)
                ));
            }
        }

        $this->validations = $validations;
    }

    /**
     * @param array|\Traversable $list
     * @return array
     */
    private function flatten($list)
    {
        $result = [];
        foreach ($list as $value) {
            if (\is_array($value) || $value instanceof \Traversable) {
                $result = \array_merge($result, $this->flatten($value));
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }
}
