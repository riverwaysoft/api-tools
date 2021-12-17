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
 *      arguments: ['endpoint' => '/api/users', 'labelKey' => 'username', 'async' => false])
 * ]
 *
 * Use 'async' => true if you want to render component that loads options from a remote source as the user types: https://react-select.com/async
 */
class RiverAdminEntityDropdownFilter extends SearchFilter
{
    public function __construct(private string $endpoint, private string $labelKey, private bool $async = false, ManagerRegistry $managerRegistry, ?RequestStack $requestStack, IriConverterInterface $iriConverter, PropertyAccessorInterface $propertyAccessor = null, LoggerInterface $logger = null, array $properties = null, IdentifiersExtractorInterface $identifiersExtractor = null, NameConverterInterface $nameConverter = null)
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
                    'async' => $this->async
                ]));
            }
        }

        return $description;
    }
}
