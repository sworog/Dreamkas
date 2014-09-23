<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\ReturnProduct;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\ReturnProduct;
use Lighthouse\CoreBundle\Validator\Constraints\ClassConstraintInterface;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class ReturnProductQuantityValidator extends ConstraintValidator
{
    /**
     * @param ReturnProduct|mixed $value The value that should be validated
     * @param ReturnProductQuantity|Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (null == $value->saleProduct) {
            return;
        }

        $otherReturnProductQuantity = 0;
        if ($value->saleProduct->returnProducts) {
            foreach ($value->saleProduct->returnProducts as $returnProduct) {
                if ($returnProduct->id != $value->id) {
                    $otherReturnProductQuantity = $returnProduct->quantity->add($otherReturnProductQuantity);
                }
            }
        }

        if (null != $value->quantity) {
            if ($value->quantity->add($otherReturnProductQuantity) > $value->saleProduct->quantity) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath("quantity")
                    ->setParameter('{{ saleProductQuantity }}', $value->saleProduct->quantity)
                    ->setParameter('{{ returnProductQuantity }}', $value->quantity)
                    ->setParameter('{{ otherReturnProductQuantity }}', $otherReturnProductQuantity)
                    ->setParameter(
                        '{{ availableReturnQuantity }}',
                        $value->saleProduct->quantity->sub($otherReturnProductQuantity)
                    )
                    ->addViolation();
            }
        }
    }
}
