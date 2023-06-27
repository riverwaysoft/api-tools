<?php

declare(strict_types=1);

namespace Riverwaysoft\ApiTools\InputValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class InputValueResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    /** @return iterable<mixed> */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributesOfType(Input::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        return [$this->serializer->deserialize($request->getContent(), $argument->getType(), 'json')];
    }
}
