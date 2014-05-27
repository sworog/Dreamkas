<?php

namespace Lighthouse\CoreBundle\Document\Product\Type;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;

/**
 * @property Quantity $alcoholByVolume
 * @property Quantity $volume
 *
 * @MongoDB\EmbeddedDocument
 */
class AlcoholType extends AbstractDocument implements Typeable
{
    const TYPE = 'alcohol';
    const UNITS = UnitType::UNITS;

    /**
     * @MongoDB\Field(type="quantity")
     * @LighthouseAssert\Chain({
     *   @LighthouseAssert\Precision(1),
     *   @LighthouseAssert\Range\Range(gte=0,lt=100)
     * })
     *
     * @var Quantity
     */
    protected $alcoholByVolume;

    /**
     * @MongoDB\Field(type="quantity")
     * @LighthouseAssert\Chain({
     *   @LighthouseAssert\Precision(3),
     *   @LighthouseAssert\Range\Range(gt=0)
     * })
     * @var Quantity
     */
    protected $volume;

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
