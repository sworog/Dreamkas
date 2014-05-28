<?php

namespace Lighthouse\CoreBundle\MongoDB\Mapping;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory as BaseClassMetadataFactory;

class ClassMetadataFactory extends BaseClassMetadataFactory
{
    /**
     * @param string $className
     * @return ClassMetadata
     */
    protected function newClassMetadataInstance($className)
    {
        return new ClassMetadata($className);
    }
}
