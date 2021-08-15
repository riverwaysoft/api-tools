<?php

declare(strict_types=1);

namespace Riverwaysoft\ApiTools\ApiPlatform\Search;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

class InputSearchFilter extends SearchFilter
{
    public function getDescription(string $resourceClass): array
    {
        $description = parent::getDescription($resourceClass);

        $properties = $this->getProperties();
        $property = array_key_first($properties);
        if ($property && !empty($description[$property])) {
            $description[$property]['property'] = sprintf('riveradmin_input:%s', $property);
        }

        return $description;
    }
}
