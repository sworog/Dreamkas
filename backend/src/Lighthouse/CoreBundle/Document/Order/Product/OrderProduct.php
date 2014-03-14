<?php

namespace Lighthouse\CoreBundle\Document\Order\Product;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProduct;
use Lighthouse\CoreBundle\Document\Product\Version\ProductVersion;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\Storeable;
use Lighthouse\CoreBundle\Document\TrialBalance\Reasonable;
use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Quantity;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use JMS\Serializer\Annotation as Serializer;
use DateTime;

/**
 * @property string $id
 * @property Quantity $quantity
 * @property Product $product
 * @property StoreProduct $storeProduct
 * @property Order $order
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Order\Product\OrderProductRepository"
 * )
 */
class OrderProduct extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Количество
     * @MongoDB\Field(type="quantity")
     * @Assert\NotBlank
     * @LighthouseAssert\Chain({
     *  @LighthouseAssert\Precision(3),
     *  @LighthouseAssert\Range\Range(gt=0)
     * })
     * @var Quantity
     */
    protected $quantity;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Version\ProductVersion",
     *     simple=true,
     *     cascade={"persist"}
     * )
     *
     * @Serializer\Exclude
     * @Assert\NotBlank
     * @var ProductVersion
     */
    protected $product;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Store\StoreProduct",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @Serializer\SerializedName("product")
     * @Serializer\Accessor(getter="getStoreProductVersion")
     * @var StoreProduct
     */
    protected $storeProduct;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Order\Order",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="products"
     * )
     *
     * @var Order
     */
    protected $order;

    /**
     * @return StoreProduct
     */
    public function getStoreProductVersion()
    {
        if ($this->storeProduct) {
            $storeProduct = clone $this->storeProduct;
            $storeProduct->product = $this->product;
        } else {
            $storeProduct = null;
        }
        return $storeProduct;
    }
}
