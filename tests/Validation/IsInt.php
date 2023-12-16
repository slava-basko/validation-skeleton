<?php

namespace Basko\ValidationTest\Validation;

use Basko\Validation\ValidationInterface;
use Basko\Validation\ValidationResult;

class IsInt implements ValidationInterface
{
    public function validate($data, array $context = [])
    {
        if (!\is_int($data)) {
            return ValidationResult::errors(['Value must be a integer']);
        }

        return ValidationResult::valid($data);
    }
}
