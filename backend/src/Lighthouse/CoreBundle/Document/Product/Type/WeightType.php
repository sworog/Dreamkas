<?php

namespace Lighthouse\CoreBundle\Document\Product\Type;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints\Range\Range as AssertRange;

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
     * @Assert\Length(max="256", maxMessage="lighthouse.validation.errors.length")
     * @MongoDB\String
     * @var string
     */
    protected $nameOnScales;

    /**
     * @Assert\Length(max="256", maxMessage="lighthouse.validation.errors.length")
     * @MongoDB\String
     * @var string
     */
    protected $descriptionOnScales;

    /**
     * @Assert\Length(max="1024", maxMessage="lighthouse.validation.errors.length")
     * @MongoDB\String
     * @var string
     */
    protected $ingredients;

    /**
     * @AssertRange(lte=1000, integer=1)
     * @MongoDB\Int
     * @var int
     */
    protected $shelfLife;

    /**
     * @Assert\Length(max="1024", maxMessage="lighthouse.validation.errors.length")
     * @MongoDB\String
     * @var string
     */
    protected $nutritionFacts;

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
