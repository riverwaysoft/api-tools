<?php

namespace Riverwaysoft\ApiTools\ApiPlatform\Serializer;

use Riverwaysoft\ApiTools\Telephone\ParseTelephoneException;
use Riverwaysoft\ApiTools\Telephone\TelephoneObject;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TelephoneObjectNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if ('' === $data || null === $data) {
            return;
        }
        try {
            return TelephoneObject::fromString($data);
        } catch (ParseTelephoneException) {
            return TelephoneObject::fromRawInput($data);
        }
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $type === TelephoneObject::class && $data !== null;
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     * @return string
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof TelephoneObject) {
            throw new InvalidArgumentException('The object must implement the "Money".');
        }

        return TelephoneObject::fromString($object)->__toString();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof TelephoneObject;
    }
}
