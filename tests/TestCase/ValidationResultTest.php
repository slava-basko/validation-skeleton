<?php

namespace Basko\ValidationTest\TestCase;

use Basko\Validation\ValidationResult;

class ValidationResultTest extends BaseTest
{
    public function testResult()
    {
        $result = ValidationResult::valid(['foo' => 'bar']);

        $this->assertTrue($result->isValid());

        $result->match(
            function ($data) {
                $this->assertEquals(['foo' => 'bar'], $data);
            },
            function (array $errors) {
                $this->fail('Should not be called');
            }
        );

        $result->map(
            function ($data) {
                $this->assertEquals(['foo' => 'bar'], $data);
                return ['bar' => 'baz'];
            }
        )->match(
            function ($data) {
                $this->assertEquals(['bar' => 'baz'], $data);
            },
            function (array $errors) {
                $this->fail('Should not be called');
            }
        );
    }

    public function testFlatMapType() {
        $this->setExpectedException(
            \LogicException::class,
            \sprintf(
                "Return type should be '%s', got 'array'",
                ValidationResult::class
            )
        );

        ValidationResult::valid(['foo' => 'bar'])->flatMap(
            function ($data) {
                return ['bar' => 'baz'];
            }
        );
    }

    public function testFlatMap()
    {
        $result = ValidationResult::valid(['foo' => 'bar']);

        $result->flatMap(
            function ($data) {
                $this->assertEquals(['foo' => 'bar'], $data);
                return ValidationResult::valid(['bar' => 'baz']);
            }
        )->match(
            function ($data) {
                $this->assertEquals(['bar' => 'baz'], $data);
            },
            function (array $errors) {
                $this->fail('Should not be called');
            }
        );
    }

    public function testErrorsResult()
    {
        $result = ValidationResult::errors(['error1', 'error2']);

        $this->assertFalse($result->isValid());

        $result->match(
            function ($data) {
                $this->fail('Should not be called');
            },
            function (array $errors) {
                $this->assertEquals(['error1', 'error2'], $errors);
            }
        );

        $result->map(
            function ($data) {
                $this->fail('Should not be called');
            }
        )->match(
            function ($data) {
                $this->fail('Should not be called');
            },
            function (array $errors) {
                $this->assertEquals(['error1', 'error2'], $errors);
            }
        );
    }

    public function testErrorsResultHelpers()
    {
        $result = ValidationResult::errors(['error1', 'error2']);

        $this->assertEquals(['error1', 'error2'], $result->getErrorMessages());
        $this->assertEquals('error2', $result->getLastErrorMessage());
    }

    public function testMapErrors()
    {
        $result = ValidationResult::errors(['error1', 'error2']);

        $result->mapErrors(
            function (array $errors) {
                return array_map('strtoupper', $errors);
            }
        )->match(
            function ($data) {
                $this->fail('Should not be called');
            },
            function (array $errors) {
                $this->assertEquals(['ERROR1', 'ERROR2'], $errors);
            }
        );
    }

    public function testFlatMapErrors()
    {
        $result = ValidationResult::errors(['error1', 'error2']);

        $result->flatMapErrors(
            function (array $errors) {
                return ValidationResult::errors(array_map('strtoupper', $errors));
            }
        )->match(
            function ($data) {
                $this->fail('Should not be called');
            },
            function (array $errors) {
                $this->assertEquals(['ERROR1', 'ERROR2'], $errors);
            }
        );
    }

    public function testFlatMapErrorsType() {
        $this->setExpectedException(
            \LogicException::class,
            \sprintf(
                "Return type should be '%s', got 'array'",
                ValidationResult::class
            )
        );

        ValidationResult::errors(['error1', 'error2'])->flatMapErrors(
            function ($data) {
                return ['ERROR1', 'ERROR2'];
            }
        );
    }
}