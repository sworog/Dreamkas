<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Types\Money as MoneyType;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
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
     * @param Constraint|MoneyRange $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof MoneyType && $value->isNull()) {
            return;
        }

        return parent::validate($value, $constraint);
    }

    /**
     * @param Money $value
     * @param Constraint $constraint
     * @return int|string
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    protected function normalizeValue($value, Range $constraint)
    {
        if (!$value instanceof MoneyType) {
            throw new UnexpectedTypeException($value, 'Money');
        }
        return $value->getCount();
    }

    /**
     * @param Money $value
     * @param Constraint|MoneyRange $constraint
     * @return string|void
     */
    protected function formatValueMessage($value, Range $constraint)
    {
        return $this->transformer->transform($value, $constraint->digits);
    }
}
