<?php

namespace Lighthouse\CoreBundle\Tests\Validator\Constraints\StoreProduct;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Test\ContainerAwareTestCase;
use Lighthouse\CoreBundle\Validator\Constraints\StoreProduct\RetailPrice;
use Lighthouse\CoreBundle\Validator\Constraints\StoreProduct\RetailPriceValidator;
use Symfony\Component\Validator\ExecutionContextInterface;

class RetailPriceValidatorTest extends ContainerAwareTestCase
{
    /**
     * @var ExecutionContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var RetailPriceValidator
     */
    protected $validator;

    public function setUp()
    {
        $this->context = $this->getMock('Symfony\\Component\\Validator\\ExecutionContext', array(), array(), '', false);
        $this->validator = new RetailPriceValidator();
        $this->validator->initialize($this->context);
    }

    public function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    public function testValidateNoProductRetailsEmptyRetailPrice()
    {
        $product = new Product();
        $storeProduct = new StoreProduct();
        $storeProduct->product = $product;
        $storeProduct->retailPricePreference = Product::RETAIL_PRICE_PREFERENCE_PRICE;

        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->context
            ->expects($this->never())
            ->method('addViolationAt');

        $constraint = new RetailPrice();
        $this->validator->validate($storeProduct, $constraint);
    }

    public function testValidateNoProductRetailsEmptyRetailMarkup()
    {
        $product = new Product();
        $storeProduct = new StoreProduct();
        $storeProduct->product = $product;
        $storeProduct->retailPricePreference = Product::RETAIL_PRICE_PREFERENCE_MARKUP;

        $this->context
            ->expects($this->never())
            ->method('addViolation');

        $this->context
            ->expects($this->never())
            ->method('addViolationAt');

        $constraint = new RetailPrice();
        $this->validator->validate($storeProduct, $constraint);
    }
}
