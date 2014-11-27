<?php

namespace Lighthouse\CoreBundle\Document\CashFlow;

use DateTime;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 *
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\CashFlow\CashFlowRepository")
 */
class CashFlow extends AbstractDocument
{
    const DIRECTION_IN = "in";
    const DIRECTION_OUT = "out";

    /**
     * @Serializer\Exclude
     * @var array
     */
    public static $directions = array(
        self::DIRECTION_IN,
        self::DIRECTION_OUT,
    );

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $date;

    /**
     * Приход/Расход
     * @MongoDB\String
     * @Assert\Choice(choices={"in", "out"})
     * @var string
     */
    protected $direction;

    /**
     * @MongoDB\Field(type="money")
     * @LighthouseAssert\Money
     * @var Money
     */
    protected $amount;

    /**
     * @MongoDB\String
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $comment;
}
