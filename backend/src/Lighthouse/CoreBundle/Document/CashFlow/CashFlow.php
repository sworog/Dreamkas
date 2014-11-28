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
 * @property string $id
 * @property DateTime $date
 * @property string $direction
 * @property Money $amount
 * @property string $comment
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\CashFlow\CashFlowRepository")
 */
class CashFlow extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @Assert\NotBlank
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
     * @LighthouseAssert\Money(
     *      messagePrecision="lighthouse.validation.errors.amount.precision",
     *      messageNegative="lighthouse.validation.errors.amount.negative",
     *      messageMax="lighthouse.validation.errors.amount.max",
     *      messageNotBlank="lighthouse.validation.errors.amount.not_blank"
     * )
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