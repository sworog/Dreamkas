<?php

namespace Lighthouse\CoreBundle\MongoDB\Generated;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * @Annotation
 */
final class Generated extends AbstractField
{
    /**
     * @var string
     */
    public $strategy = 'increment';

    /**
     * @var bool
     */
    public $generated = true;

    /**
     * @var int
     */
    public $startValue = 0;
}
