<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Product;
use Lighthouse\CoreBundle\Document\Invoice;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\InvoiceProductRepository"
 * )
 */
class InvoiceProduct extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Int
     * @var int
     */
    protected $quantity;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Invoice", simple=true)
     * @var Invoice
     */
    protected $invoice;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Product", simple=true)
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
        );
    }
}
