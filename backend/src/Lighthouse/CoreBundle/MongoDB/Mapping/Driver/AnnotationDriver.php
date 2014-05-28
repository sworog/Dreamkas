<?php

namespace Lighthouse\CoreBundle\MongoDB\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver as BaseAnnotationDriver;
use Lighthouse\CoreBundle\MongoDB\Mapping\Annotations\GlobalDb;

class AnnotationDriver extends BaseAnnotationDriver
{
    /**
     * @param string $className
     * @param ClassMetadata|\Lighthouse\CoreBundle\MongoDB\Mapping\ClassMetadata $class
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function loadMetadataForClass($className, ClassMetadata $class)
    {
        parent::loadMetadataForClass($className, $class);

        $classReflection = $class->getReflectionClass();

        $classAnnotations = $this->reader->getClassAnnotations($classReflection);

        foreach ($classAnnotations as $annotation) {
            if ($annotation instanceof GlobalDb) {
                $class->globalDb = $annotation->value;
            }
        }
    }
}
