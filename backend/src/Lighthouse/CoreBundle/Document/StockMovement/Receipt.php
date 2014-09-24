<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository"
 * )
 *
 * @Unique(
 *     fields="hash",
 *     message="lighthouse.validation.errors.receipt.hash.unique",
 *     repositoryMethod="findReceiptBy"
 * )
 *
 * @property string     $hash
 */
class Receipt extends StockMovement
{
    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc", sparse=true)
     * @var string
     */
    protected $hash;

    /**
     * @return array
     */
    public static function getReceiptTypes()
    {
        return array(Sale::TYPE, Returne::TYPE);
    }
}
