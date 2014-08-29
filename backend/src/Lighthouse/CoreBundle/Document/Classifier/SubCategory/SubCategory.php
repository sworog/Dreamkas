<?php

namespace Lighthouse\CoreBundle\Document\Classifier\SubCategory;

use DateTime;
use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\SoftDeleteableDocument;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use JMS\Serializer\Annotation as Serialize;

/**
 * @property Category $category
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryRepository"
 * )
 * @MongoDB\UniqueIndex(keys={"name"="asc", "category"="asc"})
 * @Unique(fields={"name", "category"}, message="lighthouse.validation.errors.subCategory.name.unique")
 * @SoftDeleteable
 */
class SubCategory extends AbstractNode implements SoftDeleteableDocument
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\Category\Category",
     *     simple=true,
     *     cascade="persist",
     *     inversedBy="subCategories"
     * )
     * @Serialize\Groups({"Default"})
     * @var Category
     */
    protected $category;

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getChildClass()
    {
        return Product::getClassName();
    }

    /**
     * @return DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @return string
     */
    public function getSoftDeleteableName()
    {
        return 'name';
    }
}
