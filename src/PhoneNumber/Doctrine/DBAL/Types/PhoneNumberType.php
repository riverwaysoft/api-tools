<?php

/*
 * This file is part of the Symfony2 PhoneNumberBundle.
 *
 * (c) University of Cambridge
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Riverwaysoft\ApiTools\PhoneNumber\Doctrine\DBAL\Types;

use Riverwaysoft\ApiTools\PhoneNumber\ParseTelephoneException;
use Riverwaysoft\ApiTools\PhoneNumber\TelephoneObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Phone number Doctrine mapping type.
 */
class PhoneNumberType extends Type
{
    /**
     * Phone number type name.
     */
    public const NAME = 'phone_number';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL(['length' => 35]);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof TelephoneObject) {
            $class = TelephoneObject::class;
            throw new ConversionException("Expected {$class} got " . \gettype($value));
        }

        $object = TelephoneObject::fromRawInput($value);
        return $object->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof TelephoneObject) {
            return $value;
        }

        try {
            return TelephoneObject::fromString($value);
        } catch (ParseTelephoneException) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
