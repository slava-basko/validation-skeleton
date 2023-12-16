<?php

namespace Basko\Validation;

interface ValidationInterface
{
    /**
     * @param $data
     * @param array $context
     * @return \Basko\Validation\ValidationResult
     */
    public function validate($data, array $context = []);
}
