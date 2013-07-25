<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints\Range;
use Lighthouse\CoreBundle\Validator\Constraints\NumbersCompare as AssertMarkupCompare;

/**
 * @MongoDB\MappedSuperclass
 * @AssertMarkupCompare(
 *      minField="retailMarkupMin",
 *      maxField="retailMarkupMax",
 *      message="lighthouse.validation.errors.markup.compare"
 * )
 */
class AbstractNode extends AbstractDocument
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
     * @Range(gt=-100)
     * @var float
     */
    protected $retailMarkupMin;

    /**
     * @MongoDB\Float
     * @Range(gt=-100)
     * @var float
     */
    protected $retailMarkupMax;
}
