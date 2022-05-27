<?php

declare(strict_types=1);

namespace Riverwaysoft\ApiTools\ApiPlatform\Filter;

use ApiPlatform\Core\Api\IdentifiersExtractorInterface;
use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

/**
 * Example usage:
 *
 * #[ApiFilter(
 *      RiverAdminEntityDropdownFilter::class,
 *      properties: ['progresses.user'],
 *      arguments: [
 *          'endpoint' => '/api/users',
 *          'labelKey' => 'username',
 *          // Set it to true if you want to render component that loads options from a remote source as the user types: https://react-select.com/async
 *          // Set it to false if you have a static list
 *          'async' => false,
 *          // Optional, false by default
 *          // Set it to true if you don't want to remove the automatically added iri to the filter value, e.g path?query=/api/user/{id}
 *          'removeIri' => false,
 *      ])
 * ]
 */
class RiverAdminEntityDropdownFilter extends SearchFilter
{
    public function __construct(
        private string $endpoint,
        private string $labelKey,
        ManagerRegistry $managerRegistry,
        ?RequestStack $requestStack,
        IriConverterInterface $iriConverter,
        PropertyAccessorInterface $propertyAccessor = null,
        LoggerInterface $logger = null,
        array $properties = null,
        IdentifiersExtractorInterface $identifiersExtractor = null,
        NameConverterInterface $nameConverter = null,
        private bool $async = false,
        private bool $removeIri = false,
    )
    {
        parent::__construct($managerRegistry, $requestStack, $iriConverter, $propertyAccessor, $logger, $properties, $identifiersExtractor, $nameConverter);
    }

    public function getDescription(string $resourceClass): array
    {
        $description = parent::getDescription($resourceClass);
        $properties = $this->getProperties();

        foreach ($properties as $property => $value) {
            if ($property && !empty($description[$property])) {
                $description[$property]['property'] = sprintf('riveradmin_entity_dropdown:%s', json_encode([
                    'endpoint' => $this->endpoint,
                    'labelKey' => $this->labelKey,
                    'async' => $this->async,
                    'removeIri' => $this->removeIri,
                ]));

            }
        }

        return $description;
    }
}
