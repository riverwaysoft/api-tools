<?php

namespace Riverwaysoft\ApiTools\Telephone;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

final class TelephoneObject implements \Stringable
{
    private TelephoneFormatEnum $_format;

    private function __construct(private PhoneNumber $_phone)
    {
        $this->_format = TelephoneFormatEnum::FORMAT_E164();
    }

    /**
     * @param string|int $code
     * @param string $number
     * @return static
     * @throws ParseTelephoneException
     */
    public static function fromCodeAndNationalNumber(string|int $code, string $number): self
    {
        $parsed = new PhoneNumber();
        $parsed->setCountryCode($code);
        $parsed->setNationalNumber($number);
        if (!PhoneNumberUtil::getInstance()->isValidNumber($parsed)) {
            throw new ParseTelephoneException("Not valid phone number: {$code}{$number}");
        }
        return new self($parsed);
    }

    /**
     * @param string $originTelephone
     * @return static
     * @throws ParseTelephoneException
     */
    public static function fromString(string $originTelephone, string|null $region = null): self
    {
        try {
            $parsed = PhoneNumberUtil::getInstance()->parse(self::cleanRaw($originTelephone), $region ?? PhoneNumberUtil::UNKNOWN_REGION);
            return new self($parsed);
        } catch (NumberParseException $e) {
            throw new ParseTelephoneException($e->getMessage(), 0, $e);
        }
    }

    public static function fromRawInput(?string $rawInput = null): self
    {
        $parsed = new PhoneNumber();
        $parsed->setRawInput(self::cleanRaw($rawInput));
        return new self($parsed);
    }

    private static function cleanRaw(?string $rawInput)
    {
        $phoneNumber = '+' . str_replace('+', '', $rawInput);
        if (str_starts_with($phoneNumber, '+00')) {
            $phoneNumber = str_replace('+00', '+', $phoneNumber);
        }
        if (str_starts_with($phoneNumber, '+044')) {
            $phoneNumber = str_replace('+044', '+44', $phoneNumber);
        }
        return $phoneNumber;
    }

    public function getCountryCode(): ?string
    {
        return PhoneNumberUtil::getInstance()->getRegionCodeForNumber($this->_phone);
    }
    public function configureFormat(TelephoneFormatEnum $format)
    {
    }
    public function format(): string
    {
        return PhoneNumberUtil::getInstance()->format($this->_phone, $this->_format->getValue());
    }

    public function isValid(): bool
    {
        return PhoneNumberUtil::getInstance()->isValidNumber($this->_phone);
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function equals(TelephoneObject $object): bool
    {
        return $this->__toString() === (string)$object;
    }
}
