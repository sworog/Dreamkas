<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Types\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Lighthouse\CoreBundle\Validator\Constraints as LighthouseAssert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

/**
 *
 * @property string $id
 * @property string $name
 * @property string $units kg,gr,l
 * @property int    $vat in %
 * @property Money  $purchasePrice
 * @property string $barcode
 * @property string $sku
 * @property string $vendorCountry
 * @property string $vendor
 * @property string $info
 * @property int    $amount
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\ProductRepository"
 * )
 * @Unique(fields="sku", message="lighthouse.validation.errors.product.sku.unique")
 */
class Product extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\String
     * @Assert\NotBlank(message="lighthouse.validation.errors.product.units.blank")
     */
    protected $units;

    /**
     * @MongoDB\Int
     * @Assert\Range(min="0")
     * @Assert\NotBlank(message="lighthouse.validation.errors.product.vat.blank")
     * @Assert\Range(min="0")
     */
    protected $vat;

    /**
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank
     * @LighthouseAssert\Money(max=1000000000, notBlank=true)
     * @var Money
     */
    protected $purchasePrice;

    /**
     * @MongoDB\String
     * @Assert\Length(max="200", maxMessage="lighthouse.validation.errors.length")
     */
    protected $barcode;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     */
    protected $sku;

    /**
     * @MongoDB\String
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     */
    protected $vendorCountry;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     */
    protected $vendor;

    /**
     * @MongoDB\String
     * @Assert\Length(max="2000", maxMessage="lighthouse.validation.errors.length")
     */
    protected $info;

    /**
     * Остаток
     * @MongoDB\Int
     * @var int
     */
    protected $amount;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'units' => $this->units,
            'vat' => $this->vat,
            'purchasePrice' => $this->purchasePrice,
            'barcode' => $this->barcode,
            'sku' => $this->sku,
            'vendorCountry' => $this->vendorCountry,
            'vendor' => $this->vendor,
            'info' => $this->info,
            'amount' => $this->amount
        );
    }
}
