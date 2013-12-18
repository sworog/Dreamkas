<?php

namespace Lighthouse\CoreBundle\Document\Report\GrossSales\SubCategory;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\Exclude;
use DateTime;

/**
 * @property string         $id
 * @property SubCategory    $subCategory
 * @property Money          $runningSum
 * @property Money          $hourSum
 * @property DateTime       $dayHour
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Report\GrossSales\SubCategory\GrossSalesSubCategoryRepository"
 * )
 */
class GrossSalesSubCategoryReport extends AbstractDocument
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
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory",
     *     simple=true
     * )
     * @Exclude
     * @var SubCategory
     */
    protected $subCategory;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true
     * )
     * @Exclude
     * @var Store
     */
    protected $store;
}
