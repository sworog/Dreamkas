<?php

namespace Lighthouse\ReportsBundle\Document\GrossMarginSales\Product;

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
 * @property Money          $grossSales
 * @property Money          $grossMargin
 * @property Money          $costOfGoods
 * @property Quantity       $quantity
 * @property DateTime       $day
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\ReportsBundle\Document\GrossMarginSales\Product\GrossMarginSalesProductRepository"
 * )
 * @MongoDB\Index(keys={"day"="asc", "product"="asc"})
 */
class GrossMarginSalesProductReport extends AbstractDocument
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
    protected $day;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $grossSales;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $grossMargin;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $costOfGoods;

    /**
     * @MongoDB\Field(type="quantity")
     * @var Quantity
     */
    protected $quantity;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Store\StoreProduct",
     *     simple=true
     * )
     *
     * @var StoreProduct
     */
    protected $product;
}
