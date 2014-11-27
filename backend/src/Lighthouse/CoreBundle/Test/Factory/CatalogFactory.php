<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Classifier\CatalogManager;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Category\CategoryRepository;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\Group\GroupRepository;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryRepository;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Lighthouse\CoreBundle\Rounding\Nearest1;
use Lighthouse\CoreBundle\Rounding\RoundingManager;

class CatalogFactory extends AbstractFactory
{
    const DEFAULT_PRODUCT_NAME = 'product';
    const DEFAULT_GROUP_NAME = CatalogManager::DEFAULT_NAME;
    const DEFAULT_CATEGORY_NAME = CatalogManager::DEFAULT_NAME;
    const DEFAULT_SUBCATEGORY_NAME = CatalogManager::DEFAULT_NAME;
    const DEFAULT_ROUNDING_NAME = Nearest1::NAME;

    /**
     * @var array
     */
    protected $productNames = array();

    /**
     * @var array name => id
     */
    protected $groupNames = array();

    /**
     * @var array name => id
     */
    protected $categoryNames = array();

    /**
     * @var array name => id
     */
    protected $subCategoryNames = array();

    /**
     * @param string $name
     * @param SubCategory $subCategory
     * @param array $extraData
     * @return Product
     */
    public function createProductByName(
        $name = self::DEFAULT_PRODUCT_NAME,
        SubCategory $subCategory = null,
        array $extraData = array()
    ) {
        $data = $extraData + array(
            'name' => $name,
            'vat' => 18,
        );
        return $this->createProduct($data, $subCategory);
    }

    /**
     * @param array $data
     * @param SubCategory $subCategory
     * @return Product
     */
    public function createProduct(array $data, SubCategory $subCategory = null)
    {
        $product = new Product();

        $this->populate($product, $this->prepareData($data));

        $product->subCategory = $subCategory ?: $this->getSubCategory();

        $this->doSave($product);

        $this->productNames[$product->name] = $product->id;

        return $product;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prepareData(array $data)
    {
        foreach ($data as $key => &$value) {
            switch ($key) {
                case 'purchasePrice':
                case 'sellingPrice':
                case 'retailPriceMin':
                case 'retailPriceMax':
                    $value = $this->getNumericFactory()->createMoney($value);
                    break;
            }
        }

        return $data;
    }

    /**
     * @param string $name
     * @param SubCategory $subCategory
     * @return Product
     */
    public function getProductByName($name = self::DEFAULT_PRODUCT_NAME, SubCategory $subCategory = null)
    {
        if (!isset($this->productNames[$name])) {
            $this->createProductByName($name, $subCategory);
        }
        return $this->getProductById($this->productNames[$name]);
    }

    /**
     * @param string $name
     * @param SubCategory $subCategory
     * @return Product
     */
    public function getProduct($name = self::DEFAULT_PRODUCT_NAME, SubCategory $subCategory = null)
    {
        return $this->getProductByName($name, $subCategory);
    }

    /**
     * @param array $names
     * @param SubCategory $subCategory
     * @return Product[]
     */
    public function getProductByNames(array $names, SubCategory $subCategory = null)
    {
        $products = array();
        foreach ($names as $name) {
            $products[$name] = $this->getProductByName($name, $subCategory);
        }
        return $products;
    }

    /**
     * @param string $id
     * @return Product
     */
    public function getProductById($id)
    {
        $product = $this->getProductRepository()->find($id);
        if (null === $product) {
            throw new \RuntimeException(sprintf('Product id#%s not found', $id));
        }
        return $product;
    }

    /**
     * @param string $name
     * @param string $rounding
     * @param float $retailMarkupMin
     * @param float $retailMarkupMax
     * @return Group
     */
    public function createGroup(
        $name = self::DEFAULT_GROUP_NAME,
        $rounding = null,
        $retailMarkupMin = null,
        $retailMarkupMax = null
    ) {
        $group = new Group();
        $group->name = $name;
        $group->retailMarkupMin = $retailMarkupMin;
        $group->retailMarkupMax = $retailMarkupMax;
        $group->rounding = $this->getRounding($rounding);

        $this->doSave($group);

        $this->groupNames[$group->name] = $group->id;

        return $group;
    }

    /**
     * @param string $name
     * @return Group
     */
    public function getGroup($name = self::DEFAULT_GROUP_NAME)
    {
        if (!isset($this->groupNames[$name])) {
            $this->createGroup($name);
        }
        return $this->getGroupById($this->groupNames[$name]);
    }

    /**
     * @param string $id
     * @return Group
     * @throws \RuntimeException
     */
    public function getGroupById($id)
    {
        $group = $this->getGroupRepository()->find($id);
        if (null === $group) {
            throw new \RuntimeException(sprintf('Group id#%s not found', $id));
        }
        return $group;
    }

    /**
     * @param string $groupId
     * @param string $name
     * @param string $rounding
     * @param float $retailMarkupMin
     * @param float $retailMarkupMax
     * @return Category
     */
    public function createCategory(
        $groupId,
        $name = self::DEFAULT_CATEGORY_NAME,
        $rounding = null,
        $retailMarkupMin = null,
        $retailMarkupMax = null
    ) {
        $group = ($groupId) ? $this->getGroupById($groupId) : $this->getGroup();

        $category = new Category();
        $category->group = $group;
        $category->name = $name;
        $category->rounding = $this->getRounding($rounding);
        $category->retailMarkupMin = $retailMarkupMin;
        $category->retailMarkupMax = $retailMarkupMax;

        $this->getDocumentManager()->persist($category);
        $this->getDocumentManager()->flush();

        $this->categoryNames[$category->name] = $category->id;

        return $category;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function getCategory($name = self::DEFAULT_CATEGORY_NAME)
    {
        if (!isset($this->categoryNames[$name])) {
            $this->createCategory(null, $name);
        }
        return $this->getCategoryById($this->categoryNames[$name]);
    }

    /**
     * @param string $id
     * @return Category
     * @throws \RuntimeException
     */
    public function getCategoryById($id)
    {
        $category = $this->getCategoryRepository()->find($id);
        if (null === $category) {
            throw new \RuntimeException(sprintf('Category id#%s not found', $id));
        }
        return $category;
    }


    /**
     * @param string $categoryId
     * @param string $name
     * @param string $rounding
     * @param float $retailMarkupMin
     * @param float $retailMarkupMax
     * @return SubCategory
     */
    public function createSubCategory(
        $categoryId = null,
        $name = self::DEFAULT_SUBCATEGORY_NAME,
        $rounding = null,
        $retailMarkupMin = null,
        $retailMarkupMax = null
    ) {
        $category = ($categoryId) ? $this->getCategoryById($categoryId) : $this->getCategory();

        $subCategory = new SubCategory();
        $subCategory->category = $category;
        $subCategory->name = $name;
        $subCategory->rounding = $this->getRounding($rounding);
        $subCategory->retailMarkupMin = $retailMarkupMin;
        $subCategory->retailMarkupMax = $retailMarkupMax;

        $this->getDocumentManager()->persist($subCategory);
        $this->getDocumentManager()->flush();

        $this->subCategoryNames[$subCategory->name] = $subCategory->id;

        return $subCategory;
    }

    /**
     * @param string $name
     * @return SubCategory
     */
    public function getSubCategory($name = self::DEFAULT_SUBCATEGORY_NAME)
    {
        if (!isset($this->subCategoryNames[$name])) {
            $this->createSubCategory(null, $name);
        }
        return $this->getSubCategoryById($this->subCategoryNames[$name]);
    }

    /**
     * @param string $id
     * @return SubCategory
     * @throws \RuntimeException
     */
    public function getSubCategoryById($id)
    {
        $subCategory = $this->getSubCategoryRepository()->find($id);
        if (null === $subCategory) {
            throw new \RuntimeException(sprintf('SubCategory id#%s not found', $id));
        }
        return $subCategory;
    }

    /**
     * @param array $names
     * @return SubCategory[]
     */
    public function getSubCategories(array $names)
    {
        $subCategories = array();
        foreach ($names as $name) {
            $subCategories[$name] = $this->getSubCategory($name);
        }
        return $subCategories;
    }

    /**
     * @param string $name
     * @return AbstractRounding
     */
    public function getRounding($name = null)
    {
        $name = ($name) ?: self::DEFAULT_ROUNDING_NAME;
        return $this->getRoundingManager()->findByName($name);
    }

    /**
     * @return RoundingManager
     */
    protected function getRoundingManager()
    {
        return $this->container->get('lighthouse.core.rounding.manager');
    }

    /**
     * @return GroupRepository
     */
    protected function getGroupRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.classifier.group');
    }

    /**
     * @return CategoryRepository
     */
    protected function getCategoryRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.classifier.category');
    }

    /**
     * @return SubCategoryRepository
     */
    protected function getSubCategoryRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.classifier.subcategory');
    }

    /**
     * @return ProductRepository
     */
    protected function getProductRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.product');
    }
}
