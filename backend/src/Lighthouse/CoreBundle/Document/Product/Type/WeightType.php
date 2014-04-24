<?php

namespace Lighthouse\CoreBundle\Document\Product\Type;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property string $nameOnScales
 * @property string $descriptionOnScales
 * @property string $ingredients
 * @property int $shelfLife
 *
 * @MongoDB\EmbeddedDocument
 */
class WeightType extends AbstractDocument implements Typeable
{
    const TYPE = 'weight';
    const UNITS = 'kg';

    /**
     * @MongoDB\String
     * @var string
     */
    protected $nameOnScales;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $descriptionOnScales;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $ingredients;

    /**
     * @MongoDB\Int
     * @var int
     */
    protected $shelfLife;

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
