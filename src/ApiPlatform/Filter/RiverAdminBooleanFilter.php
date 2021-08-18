<?php

namespace Riverwaysoft\ApiTools\ApiPlatform\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

class RiverAdminBooleanFilter extends SearchFilter
{
    public function getDescription(string $resourceClass): array
    {
        $description = parent::getDescription($resourceClass);
        $properties = $this->getProperties();
        $property = array_key_first($properties);

        if ($property && !empty($description[$property])) {
            $description[$property]['property'] = sprintf('riveradmin_bool[%s]', $property);
        }

        return $description;
    }
}