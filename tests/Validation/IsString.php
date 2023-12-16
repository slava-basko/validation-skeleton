<?php

namespace Basko\ValidationTest\Validation;

use Basko\Validation\ValidationInterface;
use Basko\Validation\ValidationResult;

class IsString implements ValidationInterface
{
    public function validate($data, array $context = [])
    {
        if (!\is_string($data)) {
            return ValidationResult::errors(['Value must be a string']);
        }

        return ValidationResult::valid($data);
    }
}
