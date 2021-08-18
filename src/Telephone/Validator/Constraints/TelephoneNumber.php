<?php

namespace Riverwaysoft\ApiTools\Telephone\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class TelephoneNumber extends Constraint
{
    public const ANY = 'any';
    public const FIXED_LINE = 'fixed_line';
    public const MOBILE = 'mobile';
    public const PAGER = 'pager';
    public const PERSONAL_NUMBER = 'personal_number';
    public const PREMIUM_RATE = 'premium_rate';
    public const SHARED_COST = 'shared_cost';
    public const TOLL_FREE = 'toll_free';
    public const UAN = 'uan';
    public const VOIP = 'voip';
    public const VOICEMAIL = 'voicemail';

    public const INVALID_PHONE_NUMBER_ERROR = 'ca23f4ca-38f4-4325-9bcc-eb570a4abe7f';

    protected static $errorNames = [
        self::INVALID_PHONE_NUMBER_ERROR => 'INVALID_PHONE_NUMBER_ERROR',
    ];

    public $message = null;
    public $type = self::ANY;
    public $defaultRegion = null;

    public function getType(): ?string
    {
        @trigger_error(__METHOD__.' is deprecated and will be removed in 4.0. Use "getTypes" instead.', E_USER_DEPRECATED);

        $types = $this->getTypes();
        if (0 === \count($types)) {
            return null;
        }

        return reset($types);
    }

    public function getTypes(): array
    {
        if (\is_array($this->type)) {
            return $this->type;
        }

        return [$this->type];
    }

    public function getMessage(): string
    {
        if (null !== $this->message) {
            return $this->message;
        }

        $types = $this->getTypes();
        if (1 === \count($types)) {
            $typeName = $this->getTypeName($types[0]);

            return "This value is not a valid $typeName.";
        }

        return 'This value is not a valid number.';
    }

    public function getTypeNames(): array
    {
        $types = \is_array($this->type) ? $this->type : [$this->type];

        $typeNames = [];
        foreach ($types as $type) {
            $typeNames[] = $this->getTypeName($type);
        }

        return $typeNames;
    }

    private function getTypeName(string $type): string
    {
        switch ($type) {
            case self::FIXED_LINE:
                return 'fixed-line number';
            case self::MOBILE:
                return 'mobile number';
            case self::PAGER:
                return 'pager number';
            case self::PERSONAL_NUMBER:
                return 'personal number';
            case self::PREMIUM_RATE:
                return 'premium-rate number';
            case self::SHARED_COST:
                return 'shared-cost number';
            case self::TOLL_FREE:
                return 'toll-free number';
            case self::UAN:
                return 'UAN';
            case self::VOIP:
                return 'VoIP number';
            case self::VOICEMAIL:
                return 'voicemail access number';
            case self::ANY:
                return 'phone number';
        }

        throw new \InvalidArgumentException("Unknown phone number type \"$type\".");
    }
}
