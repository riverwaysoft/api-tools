<?php
/**
 * Created by PhpStorm.
 * User: nastya
 * Date: 28.04.20
 * Time: 21:10
 */

namespace Riverwaysoft\ApiTools\ApiPlatform\Serializer;


use MyCLabs\Enum\Enum;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EnumNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        throw new \RuntimeException("Should not be denormalize");
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return false;
    }

    /**
     * @param mixed $object
     * @param string|null $format
     * @param array $context
     * @return mixed
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        if (!$object instanceof Enum) {
            throw new InvalidArgumentException('The object must implement the "Enum".');
        }

        return $object->getValue();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Enum;
    }
}
