<?php

namespace Lighthouse\CoreBundle\Document\Classifier\Category;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Classifier\AbstractNode;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Classifier\Category\CategoryRepository"
 * )
 * @Unique(fields={"name", "group"}, message="lighthouse.validation.errors.category.name.unique")
 */
class Category extends AbstractNode
{
    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Classifier\Group\Group",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var \Lighthouse\CoreBundle\Document\Classifier\Group\Group
     */
    protected $group;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory",
     *      simple=true,
     *      cascade="persist",
     *      mappedBy="category"
     * )
     * @var SubCategory[]
     */
    protected $subCategories;

    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
    }

    /**
     * @return Group
     */
    public function getParent()
    {
        return $this->group;
    }

    /**
     * @return AbstractNode[]|SubCategory
     */
    public function getChildren()
    {
        return $this->subCategories;
    }

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function updateMarkup()
    {
        parent::updateMarkup();
    }
}
