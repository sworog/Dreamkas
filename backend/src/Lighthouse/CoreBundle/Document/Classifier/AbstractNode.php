<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\NumbersCompare as AssertMarkupCompare;
use Lighthouse\CoreBundle\Validator\Constraints\NotBlankFields as AssertNotBlankFields;
use Lighthouse\CoreBundle\Validator\Constraints\Range\Range as AssertRange;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @property string $id
 * @property string $name
 * @property float  $retailMarkupMin
 * @property float  $retailMarkupMax
 * @property AbstractRounding $rounding
 * @property DateTime $deletedAt
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
     * @Serialize\Groups({"Default", "Catalog"})
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Наименование
     *
     * @Serialize\Groups({"Default", "Catalog"})
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;

    /**
     * @Serialize\Groups({"Default", "Catalog"})
     * @MongoDB\Float
     * @AssertRange(gte=0)
     * @var float
     */
    protected $retailMarkupMin;

    /**
     * @Serialize\Groups({"Default", "Catalog"})
     * @MongoDB\Float
     * @AssertRange(gte=0)
     * @var float
     */
    protected $retailMarkupMax;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * @Serialize\Groups({"Default", "Catalog"})
     * @var AbstractRounding
     */
    protected $rounding;

    /**
     * @param AbstractRounding $rounding
     */
    public function setRounding(AbstractRounding $rounding = null)
    {
        $this->rounding = $rounding;

        if (null !== $rounding) {
            $this->roundingId = $rounding->getName();
        } else {
            $this->roundingId = null;
        }
    }

    /**
     * @Serialize\Exclude
     * @MongoDB\String
     * @var string
     */
    protected $roundingId;

    /**
     * @return AbstractNode
     */
    abstract public function getParent();

    /**
     * @return string
     */
    abstract public function getChildClass();
}
