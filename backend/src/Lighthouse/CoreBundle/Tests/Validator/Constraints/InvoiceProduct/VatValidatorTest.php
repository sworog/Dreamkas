<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\InvoiceProduct;

use Lighthouse\CoreBundle\Document\StockMovement\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\StockMovement\Invoice\InvoiceProduct;
use Lighthouse\CoreBundle\Tests\Validator\Constraints\ConstraintTestCase;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;
use Lighthouse\CoreBundle\Validator\Constraints\InvoiceProduct\Vat;
use Symfony\Component\Validator\Constraint;

class VatValidatorTest extends ConstraintTestCase
{
    public function testConstraintTargets()
    {
        $constraint = new Vat();
        $this->assertEquals(Constraint::CLASS_CONSTRAINT, $constraint->getTargets());
    }

    /**
     * @param boolean $includesVat
     * @param string $price
     * @param string $priceWithoutVAT
     * @param int $expectedViolationsCount
     * @param array $expectedMessages
     *
     * @dataProvider validateProvider
     */
    public function testValidate(
        $includesVat,
        $price,
        $priceWithoutVAT,
        $expectedViolationsCount,
        array $expectedMessages = array()
    ) {
        $constraint = new Vat();

        $invoice = new Invoice();
        $invoice->includesVAT = $includesVat;

        $invoiceProduct = new InvoiceProduct();
        $invoiceProduct->parent = $invoice;
        $invoiceProduct->quantity = $this->getNumericFactory()->createQuantity(12);
        $invoiceProduct->price = $this->getNumericFactory()->createMoney($price);
        $invoiceProduct->priceWithoutVAT = $this->getNumericFactory()->createMoney($priceWithoutVAT);

        $violationList = $this->getValidator()->validate($invoiceProduct, $constraint, null);

        $this->assertCount($expectedViolationsCount, $violationList);
        foreach ($expectedMessages as $offset => $expectedMessage) {
            $violationMessage = $violationList->get($offset)->getMessageTemplate();
            $this->assertEquals($expectedMessage, $violationMessage);
        }
    }

    /**
     * @return array
     */
    public function validateProvider()
    {
        return array(
            'includes vat, both prices present' => array(
                true,
                12.11,
                12.11,
                0
            ),
            'not includes vat, both prices present' => array(
                false,
                12.11,
                12.11,
                0
            ),
            'not includes vat, only price without vat present' => array(
                false,
                null,
                12.11,
                0
            ),
            'includes vat, only price with vat present' => array(
                true,
                12.11,
                null,
                0
            ),
            'includes vat, price with vat missing' => array(
                true,
                null,
                12.11,
                1,
                array('lighthouse.validation.errors.money.not_blank')
            ),
            'not includes vat, price without vat missing' => array(
                false,
                12.11,
                null,
                1,
                array('lighthouse.validation.errors.money.not_blank')
            ),
            'includes vat, price with vat has 3 digits' => array(
                true,
                12.111,
                null,
                1,
                array('lighthouse.validation.errors.money.precision')
            ),
            'not includes vat, price without vat has 3 digits' => array(
                false,
                null,
                12.111,
                1,
                array('lighthouse.validation.errors.money.precision')
            ),
            'not includes vat, price without vat has 3 digits but last digit is zero' => array(
                false,
                null,
                '12.110',
                0
            ),
            'includes vat, price with vat is less than 0' => array(
                true,
                -12.11,
                null,
                1,
                array('lighthouse.validation.errors.money.negative')
            ),
            'not includes vat, price without vat is less than 0' => array(
                false,
                null,
                -12.11,
                1,
                array('lighthouse.validation.errors.money.negative')
            ),
        );
    }

    /**
     * @return NumericFactory
     */
    protected function getNumericFactory()
    {
        return $this->getContainer()->get('lighthouse.core.types.numeric.factory');
    }
}
