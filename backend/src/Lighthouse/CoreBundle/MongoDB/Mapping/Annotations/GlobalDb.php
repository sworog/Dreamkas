<?php

namespace Lighthouse\CoreBundle\MongoDB\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class GlobalDb extends Annotation
{
    public $value = true;
}
