<?php

namespace Riverwaysoft\ApiTools\Tests\Telephone\Validator\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Riverwaysoft\ApiTools\Telephone\TelephoneObject;
use Riverwaysoft\ApiTools\Telephone\Validator\Constraints\TelephoneNumber;
use Riverwaysoft\ApiTools\Telephone\Validator\Constraints\TelephoneNumberValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;

/**
 * Phone number validator test.
 */
class TelephoneNumberValidatorTest extends TestCase
{
    /**
     * @var \Symfony\Component\Validator\Context\ExecutionContextInterface|MockObject
     */
    protected $context;

    /**
     * @var ?TelephoneNumberValidator
     */
    protected $validator = null;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);

        $this->validator = new TelephoneNumberValidator();
        $this->validator->initialize($this->context);
    }

    /**
     * @dataProvider validateProvider
     */
    public function testValidate($value, $violates, $type = null, $defaultRegion = null)
    {
        $constraint = new TelephoneNumber();

        if (null !== $type) {
            $constraint->type = $type;
        }

        if (null !== $defaultRegion) {
            $constraint->defaultRegion = $defaultRegion;
        }
        $validator = Validation::createValidatorBuilder()->getValidator();
        $res = $validator->validate($value, [$constraint]);
        if (!$violates) {
            $this->assertEquals(0, $res->count());
        }else{
            $this->assertEquals(1, $res->count());
        }
    }

    /**
     * 0 => Value
     * 1 => Violates?
     * 2 => Type (optional)
     * 3 => Default region (optional).
     */
    public function validateProvider()
    {
        return [
            [null, false],
            ['', false],
            [TelephoneObject::fromRawInput('+441234567890'), false],
            [TelephoneObject::fromRawInput('+441234567890'), true, 'mobile'],
            ['+441234567890', false],
            ['+441234567890', false, 'fixed_line'],
            ['+441234567890', true, 'mobile'],
            ['+441234567890', false, ['mobile', 'fixed_line']],
            ['+441234567890', true, ['mobile', 'voip']],
            ['+44123456789', true],
            ['+44123456789', true, 'mobile'],
            ['+12015555555', false],
            ['+12015555555', false, 'fixed_line'],
            ['+12015555555', false, 'mobile'],
            ['+12015555555', false, ['mobile', 'fixed_line']],
            ['+12015555555', true, ['pager', 'voip', 'uan']],
            ['+447640123456', false, 'pager'],
            ['+441234567890', true, 'pager'],
            ['+447012345678', false, 'personal_number'],
            ['+441234567890', true, 'personal_number'],
            ['+449012345678', false, 'premium_rate'],
            ['+441234567890', true, 'premium_rate'],
            ['+441234567890', true, 'shared_cost'],
            ['+448001234567', false, 'toll_free'],
            ['+441234567890', true, 'toll_free'],
            ['+445512345678', false, 'uan'],
            ['+441234567890', true, 'uan'],
            ['+445612345678', false, 'voip'],
            ['+441234567890', true, 'voip'],
            ['+41860123456789', false, 'voicemail'],
            ['+441234567890', true, 'voicemail'],
            ['2015555555', false, null, 'US'],
            ['2015555555', false, 'fixed_line', 'US'],
            ['2015555555', false, 'mobile', 'US'],
            ['foo', true],
        ];
    }
}
