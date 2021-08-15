<?php


namespace Riverwaysoft\ApiTools\Telephone\Validator\Constraints;

use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Riverwaysoft\ApiTools\Telephone\ParseTelephoneException;
use Riverwaysoft\ApiTools\Telephone\TelephoneObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TelephoneNumberValidator extends ConstraintValidator
{
    /**
     * @var PhoneNumberUtil
     */
    private $phoneUtil;

    /**
     * @var string
     */
    private $defaultRegion;

    public function __construct(string $defaultRegion = PhoneNumberUtil::UNKNOWN_REGION)
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
        $this->defaultRegion = $defaultRegion;
    }

    /**
     *
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        /**
         * @var TelephoneNumber $constraint
         */
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (false === $value instanceof TelephoneObject) {
            $value = (string)$value;

            try {
                TelephoneObject::fromString($value, $constraint->defaultRegion ?? $this->defaultRegion);
            } catch (ParseTelephoneException $e) {
                $this->addViolation($value, $constraint);

                return;
            }
        } else {
            $phoneNumber = $value;
            $value = $phoneNumber->format();
        }
        $validTypes = [];
        foreach ($constraint->getTypes() as $type) {
            switch ($type) {
                case TelephoneNumber::FIXED_LINE:
                    $validTypes[] = PhoneNumberType::FIXED_LINE;
                    $validTypes[] = PhoneNumberType::FIXED_LINE_OR_MOBILE;
                    break;
                case TelephoneNumber::MOBILE:
                    $validTypes[] = PhoneNumberType::MOBILE;
                    $validTypes[] = PhoneNumberType::FIXED_LINE_OR_MOBILE;
                    break;
                case TelephoneNumber::PAGER:
                    $validTypes[] = PhoneNumberType::PAGER;
                    break;
                case TelephoneNumber::PERSONAL_NUMBER:
                    $validTypes[] = PhoneNumberType::PERSONAL_NUMBER;
                    break;
                case TelephoneNumber::PREMIUM_RATE:
                    $validTypes[] = PhoneNumberType::PREMIUM_RATE;
                    break;
                case TelephoneNumber::SHARED_COST:
                    $validTypes[] = PhoneNumberType::SHARED_COST;
                    break;
                case TelephoneNumber::TOLL_FREE:
                    $validTypes[] = PhoneNumberType::TOLL_FREE;
                    break;
                case TelephoneNumber::UAN:
                    $validTypes[] = PhoneNumberType::UAN;
                    break;
                case TelephoneNumber::VOIP:
                    $validTypes[] = PhoneNumberType::VOIP;
                    break;
                case TelephoneNumber::VOICEMAIL:
                    $validTypes[] = PhoneNumberType::VOICEMAIL;
                    break;
            }
        }

        $validTypes = array_unique($validTypes);

        if (0 < \count($validTypes)) {
            $vendorPhoneNumber = $this->phoneUtil->parse($value, $constraint->defaultRegion ?? $this->defaultRegion);

            $type = $this->phoneUtil->getNumberType($vendorPhoneNumber);

            if (!\in_array($type, $validTypes, true)) {
                $this->addViolation($value, $constraint);
            }
        }
    }

    /**
     * Add a violation.
     *
     * @param mixed $value the value that should be validated
     * @param Constraint $constraint the constraint for the validation
     */
    private function addViolation($value, Constraint $constraint)
    {
        /**
         * @var TelephoneNumber $constraint
         */
        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('{{ types }}', implode(', ', $constraint->getTypeNames()))
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setCode(TelephoneNumber::INVALID_PHONE_NUMBER_ERROR)
            ->addViolation();
    }
}
