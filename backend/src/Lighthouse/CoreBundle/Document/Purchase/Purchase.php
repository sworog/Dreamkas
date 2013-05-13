<?php

namespace Lighthouse\CoreBundle\Document\Purchase;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @MongoDB\EmbedMany(targetDocument="Lighthouse\CoreBundle\Document\PurchasePrice\PurchasePrice")
     * @var
     */
    protected $products = array();
}
