<?php

namespace Basko\Validation;

final class OrValidation extends GroupValidation
{
    /**
     * @param $data
     * @param array $context
     * @return \Basko\Validation\ValidationResult
     */
    public function validate($data, array $context = [])
    {
        $errors = [];

        foreach ($this->validations as $validation) {
            $result = $validation->validate($data, $context);
            if ($result->isValid()) {
                return $result;
            } else {
                $errors = array_merge($errors, $result->getErrorMessages());
            }
        }

        if (empty($errors)) {
            return ValidationResult::valid($data);
        }

        return ValidationResult::errors($errors);
    }
}
