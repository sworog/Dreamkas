<?php

namespace Lighthouse\ReportsBundle\Document\GrossSales\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Exclude;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;

/**
 * @property string         $id
 * @property StoreProduct   $product
 * @property Money          $runningSum
 * @property Money          $hourSum
 * @property Quantity       $hourQuantity
 * @property DateTime       $dayHour
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossSales\Product\GrossSalesProductRepository"
 * )
 * @MongoDB\Index(keys={"dayHour"="asc", "product"="asc"})
 */
class GrossSalesProductReport extends AbstractDocument
{
    /**
     * @MongoDB\Id(strategy="NONE")
     * @Exclude
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $dayHour;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $runningSum;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $hourSum;

    /**
     * @MongoDB\Field(type="quantity")
     * @var Quantity
     */
    protected $hourQuantity;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Store\StoreProduct",
     *     simple=true
     * )
     * @Exclude
     * @var StoreProduct
     */
    protected $product;
}
