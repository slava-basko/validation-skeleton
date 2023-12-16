<?php

namespace Basko\ValidationTest\TestCase;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * @param $exceptionClass
     * @param $exceptionMessage
     * @param $exceptionCode
     * @return void
     */
    public function setExpectedException($exceptionClass, $exceptionMessage = '', $exceptionCode = 0)
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($exceptionClass);
            $this->expectExceptionMessage($exceptionMessage);
            $this->expectExceptionCode($exceptionCode);
        } else if (method_exists(parent::class, 'setExpectedException')) {
            parent::setExpectedException($exceptionClass, $exceptionMessage, $exceptionCode);
        } else {
            throw new \RuntimeException("Don't know how to expect exceptions");
        }
    }
}