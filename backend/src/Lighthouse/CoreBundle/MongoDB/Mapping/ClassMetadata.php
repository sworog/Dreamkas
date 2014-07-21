<?php

namespace Lighthouse\CoreBundle\MongoDB\Mapping;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata as BaseClassMetadata;

class ClassMetadata extends BaseClassMetadata
{
    /**
     * @var bool
     */
    public $globalDb = false;

    /**
     * @var bool
     */
    public $softDeleteable = false;

    /**
     * @return array
     */
    public function __sleep()
    {
        $serialized  = parent::__sleep();
        if ($this->globalDb) {
            $serialized[] = 'globalDb';
        }
        if ($this->softDeleteable) {
            $serialized[] = 'softDeleteable';
        }
        return $serialized;
    }
}
