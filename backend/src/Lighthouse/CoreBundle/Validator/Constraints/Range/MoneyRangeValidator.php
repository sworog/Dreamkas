<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Range;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Types\Money;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\Comparison;
use Lighthouse\CoreBundle\Validator\Constraints\Compare\MoneyComparison;
use Symfony\Component\Validator\Constraint;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.validator.money_range")
 * @DI\Tag("validator.constraint_validator", attributes={"alias"="money_range_validator"})
 */
class MoneyRangeValidator extends RangeValidator
{
    /**
     * @var MoneyModelTransformer
     */
    protected $transformer;

    /**
     * @DI\InjectParams({
     *      "moneyTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model")
     * })
     * @param MoneyModelTransformer $moneyTransformer
     */
    public function __construct(MoneyModelTransformer $moneyTransformer)
    {
        parent::__construct();
        $this->transformer = $moneyTransformer;
    }

    /**
     * @param Money $value
     * @param Range $constraint
     * @return Comparison|MoneyComparison
     */
    protected function createComparison($value, Range $constraint)
    {
        return new MoneyComparison($value, $this->comparator);
    }

    /**
     * @param Comparison|MoneyComparison $comparison
     * @param MoneyRange|Range|Constraint $constraint
     * @param string $operator
     * @return string
     */
    protected function formatValueMessage(Comparison $comparison, Range $constraint, $operator)
    {
        return $this->transformer->transform($comparison->getMoneyValue(), $constraint->precision);
    }

    /**
     * @param Money $limit
     * @param Range|MoneyRange $constraint
     * @param $operator
     * @param Comparison $comparison
     * @return string
     */
    protected function formatLimitMessage($limit, Range $constraint, $operator, Comparison $comparison)
    {
        $limitValue = $this->transformer->transform($limit, $constraint->precision);
        return parent::formatLimitMessage($limitValue, $constraint, $operator, $comparison);
    }
}
