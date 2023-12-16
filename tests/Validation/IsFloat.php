<?php

namespace Basko\ValidationTest\Validation;

use Basko\Validation\ValidationInterface;
use Basko\Validation\ValidationResult;

class IsFloat implements ValidationInterface
{
    public function validate($data, array $context = [])
    {
        if (!\is_float($data)) {
            return ValidationResult::errors(['Value must be a float']);
        }

        return ValidationResult::valid($data);
    }
}
