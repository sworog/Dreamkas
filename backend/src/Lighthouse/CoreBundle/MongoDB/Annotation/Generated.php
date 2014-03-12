<?php

namespace Lighthouse\CoreBundle\MongoDB\Annotation;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * @Annotation
 */
final class Generated extends AbstractField
{
    public $strategy = 'increment';
    public $generated = true;
}
