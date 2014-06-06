<?php

namespace Lighthouse\CoreBundle\MongoDB\Mapping;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata as BaseClassMetadata;

class ClassMetadata extends BaseClassMetadata
{
    /**
     * @var
     */
    public $globalDb = false;

    /**
     * @return array
     */
    public function __sleep()
    {
        $serialized  = parent::__sleep();
        $serialized[] = 'globalDb';
        return $serialized;
    }
}
