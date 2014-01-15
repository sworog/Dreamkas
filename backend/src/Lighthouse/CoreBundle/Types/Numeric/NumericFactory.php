<?php

namespace Lighthouse\CoreBundle\Types\Numeric;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.types.numeric.factory")
 */
class NumericFactory
{
    /**
     * @var int
     */
    protected $quantityPrecision;

    /**
     * @var int
     */
    protected $moneyPrecision;

    /**
     * @DI\InjectParams({
     *      "quantityPrecision" = @DI\Inject("%lighthouse.core.precision.quantity%"),
     *      "moneyPrecision" = @DI\Inject("%lighthouse.core.precision.money%"),
     * })
     * @param int $quantityPrecision
     * @param int $moneyPrecision
     */
    public function __construct($quantityPrecision, $moneyPrecision)
    {
        $this->quantityPrecision = $quantityPrecision;
        $this->moneyPrecision = $moneyPrecision;
    }

    /**
     * @param string|float|int $value
     * @return Quantity
     */
    public function createQuantity($value)
    {
        return Quantity::createFromNumeric($value, $this->quantityPrecision);
    }

    /**
     * @param int $value
     * @return Quantity
     */
    public function createQuantityFromCount($value)
    {
        return new Quantity($value, $this->quantityPrecision);
    }

    /**
     * @param string|float|int $value
     * @param bool $setRaw
     * @return Money
     */
    public function createMoney($value, $setRaw = true)
    {
        $money = Money::createFromNumeric($value, $this->moneyPrecision);
        if ($setRaw) {
            $money->setRaw($value);
        }
        return $money;
    }

    /**
     * @param int $value
     * @return Money
     */
    public function createMoneyFromCount($value)
    {
        return new Money($value, $this->moneyPrecision);
    }
}
