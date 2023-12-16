<?php

namespace Basko\ValidationTest\Validation;

use Basko\Validation\ValidationInterface;
use Basko\Validation\ValidationResult;

class NotEmptyString implements ValidationInterface
{
    public function validate($data, array $context = [])
    {
        if (!\is_string($data) || $data === '') {
            return ValidationResult::errors(['Value must be a non-empty string']);
        }

        return ValidationResult::valid($data);
    }
}
