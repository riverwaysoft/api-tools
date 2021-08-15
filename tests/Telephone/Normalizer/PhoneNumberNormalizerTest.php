<?php

/*
 * This file is part of the Symfony2 PhoneNumberBundle.
 *
 * (c) University of Cambridge
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Riverwaysoft\ApiTools\Tests\Telephone\Normalizer;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Riverwaysoft\ApiTools\ApiPlatform\Serializer\TelephoneObjectNormalizer;
use Riverwaysoft\ApiTools\Telephone\TelephoneObject;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Serializer;

/**
 * Phone number serialization test.
 */
class PhoneNumberNormalizerTest extends TestCase
{
    protected function setUp(): void
    {
        if (!class_exists(Serializer::class)) {
            $this->markTestSkipped('The Symfony Serializer is not available.');
        }
    }

    public function testSupportNormalization()
    {
        $normalizer = new TelephoneObjectNormalizer();

        $this->assertTrue($normalizer->supportsNormalization(TelephoneObject::fromRawInput('')));
        $this->assertFalse($normalizer->supportsNormalization(new \stdClass()));
    }

    public function testNormalize()
    {
        $phoneNumber = TelephoneObject::fromRawInput('+33193166989');

        $normalizer = new TelephoneObjectNormalizer();

        $this->assertEquals('+33193166989', $normalizer->normalize($phoneNumber));
    }

    public function testSupportDenormalization()
    {
        $normalizer = new TelephoneObjectNormalizer();

        $this->assertTrue($normalizer->supportsDenormalization('+33193166989', TelephoneObject::class));
        $this->assertFalse($normalizer->supportsDenormalization('+33193166989', 'stdClass'));
    }

    public function testDenormalize()
    {
        $phoneNumber = TelephoneObject::fromRawInput('+33193166989');

        $normalizer = new TelephoneObjectNormalizer();

        $this->assertEquals((string)$phoneNumber, (string)$normalizer->denormalize('+33193166989', $phoneNumber::class));
    }

    public function testItDenormalizeNullToNull()
    {

        $normalizer = new TelephoneObjectNormalizer();

        $this->assertNull($normalizer->denormalize(null, TelephoneObject::class));
    }
}
