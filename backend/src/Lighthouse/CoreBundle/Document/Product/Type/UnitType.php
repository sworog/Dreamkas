<?php

namespace Lighthouse\CoreBundle\Document\Product\Type;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @MongoDB\EmbeddedDocument
 */
class UnitType extends AbstractDocument implements Typeable
{
    const TYPE = 'unit';
    const UNITS = 'unit';

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getUnits()
    {
        return self::UNITS;
    }
}
