<?php

namespace Lighthouse\CoreBundle\Document\Product;

use Lighthouse\CoreBundle\Versionable\VersionInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Versionable\VersionRepository"
 * )
 */
class ProductVersion extends Product implements VersionInterface
{
    /**
     * @MongoDB\String
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $sku;

    /**
     * @MongoDB\Id(strategy="NONE")
     * @var string
     */
    protected $version;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Product\Product",
     *     simple=true,
     *     cascade={"persist"}
     * )
     * @var Product
     */
    protected $object;

    /**
     * @param string $version
     * @return void
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param Product $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return Product
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return array
     */
    public function getVersionFields()
    {
        return array(
            "id",
            "name",
            "units",
            "vat",
            "purchasePrice",
            "lastPurchasePrice",
            "barcode",
            "sku",
            "vendorCountry",
            "info",
            "amount",
            "subCategory"
        );
    }
}
