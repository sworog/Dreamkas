<?php

namespace Lighthouse\CoreBundle\MongoDB\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class GlobalDb extends Annotation
{
    /**
     * @var bool
     */
    public $value = true;
}
