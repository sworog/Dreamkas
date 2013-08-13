<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints\Range\Range;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare as AssertMarkupCompare;
use Lighthouse\CoreBundle\Validator\Constraints\NotBlankFields as AssertNotBlankFields;

/**
 * @property string $id
 * @property string $name
 * @property float  $retailMarkupMin
 * @property float  $retailMarkupMax
 * @property AbstractRounding $rounding
 *
 * @MongoDB\MappedSuperclass
 * @AssertMarkupCompare(
 *      minField="retailMarkupMin",
 *      maxField="retailMarkupMax",
 *      message="lighthouse.validation.errors.markup.compare"
 * )
 * @AssertNotBlankFields(
 *      {"retailMarkupMin","retailMarkupMax"}
 * )
 */
abstract class AbstractNode extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Наименование
     *
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\Float
     * @Range(gte=0)
     * @var float
     */
    protected $retailMarkupMin;

    /**
     * @MongoDB\Float
     * @Range(gte=0)
     * @var float
     */
    protected $retailMarkupMax;

    /**
     * @Assert\NotBlank
     * @var AbstractRounding
     */
    protected $rounding;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $roundingId;

    /**
     * @return AbstractNode
     */
    abstract public function getParent();

    /**
     * @return AbstractNode[]
     */
    abstract public function getChildren();
}
