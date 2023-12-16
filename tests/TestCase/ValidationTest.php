<?php

namespace Basko\ValidationTest\TestCase;

use Basko\Validation\AndValidation;
use Basko\Validation\OrValidation;
use Basko\ValidationTest\Validation\IsFloat;
use Basko\ValidationTest\Validation\IsInt;
use Basko\ValidationTest\Validation\IsString;
use Basko\ValidationTest\Validation\NotEmptyString;

class ValidationTest extends BaseTest
{
    public function testValidation()
    {
        $validation = new IsString();
        $this->assertTrue($validation->validate('string')->isValid());
    }

    public function testValidationError()
    {
        $validation = new IsString();
        $this->assertFalse($validation->validate(123)->isValid());
    }

    public function testAndValidation()
    {
        $validation = new AndValidation(new IsString(), new NotEmptyString());
        $this->assertTrue($validation->validate('string')->isValid());
        $this->assertfalse($validation->validate('')->isValid());
    }

    public function testOrValidation()
    {
        $validation = new OrValidation(new IsInt(), new IsFloat());
        $this->assertTrue($validation->validate(1)->isValid());
        $this->assertTrue($validation->validate(1.5)->isValid());
        $this->assertfalse($validation->validate('string')->isValid());
    }
}
