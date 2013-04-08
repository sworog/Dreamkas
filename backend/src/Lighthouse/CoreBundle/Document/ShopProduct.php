<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\ShopProductRepository",
 *     indexes={
 *         @MongoDB\Index(keys={"product"="desc"}, options={"unique"=true})
 *     }
 * )
 */
class ShopProduct extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Int
     * @var integer
     */
    protected $amount;

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
            'amount' => $this->amount,
        );
    }
}