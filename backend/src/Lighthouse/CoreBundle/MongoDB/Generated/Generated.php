<?php

namespace Lighthouse\CoreBundle\MongoDB\Generated;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * @Annotation
 */
final class Generated extends AbstractField
{
    public $strategy = 'increment';
    public $generated = true;
    public $startValue = 0;
}
