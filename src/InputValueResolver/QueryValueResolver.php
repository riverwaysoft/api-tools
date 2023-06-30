<?php

declare(strict_types=1);

namespace Riverwaysoft\ApiTools\InputValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class QueryValueResolver implements ValueResolverInterface
{
    public function __construct(
        private DenormalizerInterface $serializer,
    ) {
    }

    /** @return iterable<mixed> */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributesOfType(Query::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        return [$this->serializer->denormalize($request->query->all(), $argument->getType(), 'json')];
    }
}
