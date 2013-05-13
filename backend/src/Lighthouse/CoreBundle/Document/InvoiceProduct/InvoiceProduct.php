<?php

namespace Lighthouse\CoreBundle\Document\InvoiceProduct;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Invoice\Invoice;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Lighthouse\CoreBundle\Types\Money;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string $id
 * @property int    $quantity
 * @property Money  $price
 * @property Money  $totalPrice
 * @property Invoice $invoice
 * @property Product $product
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\InvoiceProduct\InvoiceProductRepository"
 * )
 */
class InvoiceProduct extends AbstractDocument implements Reasonable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Количество
     * @MongoDB\Int
     * @Assert\NotBlank
     * @LighthouseAssert\Chain({
     *   @LighthouseAssert\NotFloat,
     *   @LighthouseAssert\Range(gt=0)
     * })
     * @var int
     */
    protected $quantity;

    /**
     * Закупочная цена
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank
     * @LighthouseAssert\Money(notBlank=true)
     * @var Money
     */
    protected $price;

    /**
     * Сумма
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $totalPrice;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Invoice\Invoice",
     *     simple=true
     * )
     * @Assert\NotBlank
     * @var Invoice
     */
    protected $invoice;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Assert\NotBlank
     * @var Product
     */
    protected $product;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'totalPrice' => $this->totalPrice,
        );
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateTotalPrice()
    {
        $this->totalPrice = new Money();
        $this->totalPrice->setCountByQuantity($this->price, $this->quantity, true);
    }

    /**
     * @return string
     */
    public function getReasonId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getReasonType()
    {
        return 'InvoiceProduct';
    }
}
