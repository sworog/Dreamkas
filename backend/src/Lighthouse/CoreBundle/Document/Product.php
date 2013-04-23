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
 * @LighthouseAssert\Callback(
 *     method="updateRetails",
 *     constraints={
 *         "retailPrice"  = @LighthouseAssert\Money,
 *         "retailMarkup" = {
 *              @LighthouseAssert\Precision,
 *              @LighthouseAssert\Range(
 *                  gt="-100",
 *                  gtMessage="lighthouse.validation.errors.product.retailMarkup.range"
 *              )
 *         }
 *     }
 * )
 */
class Product extends AbstractDocument
{
    const RETAIL_PRICE_PREFERENCE_PRICE = 'retailPrice';
    const RETAIL_PRICE_PREFERENCE_MARKUP = 'retailMarkup';

    /**
     * @var array
     */
    public static $retailPricePreferences = array(
        self::RETAIL_PRICE_PREFERENCE_PRICE => 'lighthouse.document.product.retailPrice',
        self::RETAIL_PRICE_PREFERENCE_MARKUP => 'lighthouse.document.product.retailMarkup',
    );

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
     * @Assert\NotBlank(message="lighthouse.validation.errors.product.vat.blank")
     * @Assert\Range(min="0")
     */
    protected $vat;

    /**
     * @MongoDB\Field(type="money")
     * @Assert\NotBlank
     * @LighthouseAssert\Money(notBlank=true)
     * @var Money
     */
    protected $purchasePrice;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $lastPurchasePrice;

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
     * @MongoDB\Increment
     * @var int
     */
    protected $amount;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $retailPrice;

    /**
     * @MongoDB\Float
     * @var float
     */
    protected $retailMarkup;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $retailPricePreference = self::RETAIL_PRICE_PREFERENCE_PRICE;

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
            'lastPurchasePrice' => $this->lastPurchasePrice,
            'barcode' => $this->barcode,
            'sku' => $this->sku,
            'vendorCountry' => $this->vendorCountry,
            'vendor' => $this->vendor,
            'info' => $this->info,
            'amount' => $this->amount,
            'retailPrice' => $this->retailPrice,
            'retailMarkup' => $this->retailMarkup,
            'retailPricePreference' => $this->retailPricePreference,
        );
    }

    public function updateRetails()
    {
        switch ($this->retailPricePreference) {
            case self::RETAIL_PRICE_PREFERENCE_PRICE:
                if (null !== $this->retailPrice && !$this->retailPrice->isEmpty()) {
                    $markup = (($this->retailPrice->getCount() / $this->purchasePrice->getCount()) * 100) - 100;
                    $this->retailMarkup = round($markup, 2);
                }
                break;
            case self::RETAIL_PRICE_PREFERENCE_MARKUP:
                if (null !== $this->retailMarkup) {
                    $percent = 1 + ($this->retailMarkup / 100);
                    $this->retailPrice = new Money();
                    $this->retailPrice->setCountByQuantity($this->purchasePrice, $percent, true);
                }
                break;
        }
    }
}
