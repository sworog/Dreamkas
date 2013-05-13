<?php

namespace Lighthouse\CoreBundle\Document\Purchase;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\PurchaseProduct\PurchaseProduct;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Purchase\PurchaseRepository"
 * )
 */
class Purchase extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\EmbedMany(targetDocument="Lighthouse\CoreBundle\Document\PurchaseProduct\PurchaseProduct")
     * @var PurchaseProduct[]
     */
    protected $products = array();
}
