<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Products;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Console\DotHelper;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Category\CategoryRepository;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\Group\GroupRepository;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryRepository;
use Lighthouse\CoreBundle\Document\Product\Product;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Product\Type\Typeable;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType;
use Lighthouse\CoreBundle\Document\Product\Type\WeightType;
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\Types\Numeric\Money;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * @DI\Service("lighthouse.core.integration.set10.import.products.importer")
 */
class Set10ProductImporter
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var ValidatorInterface|ExceptionalValidator
     */
    protected $validator;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var SubCategoryRepository
     */
    protected $subCategoryRepository;

    /**
     * @var MoneyModelTransformer
     */
    protected $moneyModelTransformer;

    /**
     * @var array
     */
    protected $productSkus = array();

    /**
     * @var Group[]
     */
    protected $groups;

    /**
     * @var Category[]
     */
    protected $categories;

    /**
     * @var SubCategory[]
     */
    protected $subCategories;

    /**
     * @var int
     */
    protected $batchSize = 1;

    /**
     * @var int
     */
    protected $lineWidth = 50;

    /**
     * @DI\InjectParams({
     *      "dm" = @DI\Inject("doctrine_mongodb.odm.document_manager"),
     *      "validator" = @DI\Inject("lighthouse.core.validator"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "groupRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.group"),
     *      "categoryRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.category"),
     *      "subCategoryRepository" = @DI\Inject("lighthouse.core.document.repository.classifier.subcategory"),
     *      "moneyModelTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model")
     * })
     * @param ObjectManager $dm
     * @param ValidatorInterface $validator
     * @param ProductRepository $productRepository
     * @param GroupRepository $groupRepository
     * @param CategoryRepository $categoryRepository
     * @param SubCategoryRepository $subCategoryRepository
     * @param MoneyModelTransformer $moneyModelTransformer
     */
    public function __construct(
        ObjectManager $dm,
        ValidatorInterface $validator,
        ProductRepository $productRepository,
        GroupRepository $groupRepository,
        CategoryRepository $categoryRepository,
        SubCategoryRepository $subCategoryRepository,
        MoneyModelTransformer $moneyModelTransformer
    ) {
        $this->dm = $dm;
        $this->validator = $validator;
        $this->productRepository = $productRepository;
        $this->groupRepository = $groupRepository;
        $this->categoryRepository = $categoryRepository;
        $this->subCategoryRepository = $subCategoryRepository;
        $this->moneyModelTransformer = $moneyModelTransformer;
    }

    /**
     * @param Set10ProductImportXmlParser $parser
     * @param OutputInterface $output
     * @param int $batchSize
     * @param boolean $update
     * @param DotHelper $dotHelper
     * @param Stopwatch $stopwatch
     */
    public function import(
        Set10ProductImportXmlParser $parser,
        OutputInterface $output,
        $batchSize = null,
        $update = false,
        DotHelper $dotHelper = null,
        Stopwatch $stopwatch = null
    ) {
        $dotHelper = ($dotHelper) ?: new DotHelper($output);
        $stopwatch = ($stopwatch) ?: new Stopwatch();

        $errors = array();
        $count = 0;

        $allEvent = $stopwatch->start('all');

        $batchSize = ($batchSize) ?: $this->batchSize;
        $currentBatch = $count;

        /* @var GoodElement $goodElement */
        while ($goodElement = $parser->readNextElement()) {

            $persistEvent = $stopwatch->start('allPersist');
            $batchPersistEvent = $stopwatch->start('persist_' . $currentBatch);

            try {
                $product = $this->getProduct($goodElement, $update);
                if ($product) {
                    $dot = ($product->id) ? 'U' : '.';
                    $this->dm->persist($product);
                    $dotHelper->write($dot);
                } else {
                    $dotHelper->writeComment('S');
                }
            } catch (ValidationFailedException $e) {
                $errors[] = array(
                    'message' => $e->getMessage(),
                    'sku' => $goodElement->getMarkingOfTheGood(),
                    'name' => $goodElement->getGoodName(),
                );
                $dotHelper->writeError('E');
            }

            $batchPersistEvent->stop();
            $persistEvent->stop();

            if (0 == ++$count % $batchSize) {
                $currentBatch = $count;
                $dotHelper->end(false);
                $flushEvent = $stopwatch->start('flush');
                $currentFlushEvent = $stopwatch->start('flush_' . $currentBatch);
                $output->write('<info>Flushing</info>');

                $this->dm->flush();
                $this->dm->clear(Product::getClassName());

                $flushEvent->stop();
                $currentFlushEvent->stop();

                $output->writeln(
                    sprintf(
                        ' - Persist: %.01f prod/s. Flush+Clear: %d ms, %.01f prod/s',
                        $this->countSpeed($batchPersistEvent->getPeriods(), $batchPersistEvent->getDuration() / 1000),
                        $currentFlushEvent->getDuration(),
                        $this->countSpeed($batchPersistEvent->getPeriods(), $currentFlushEvent->getDuration() / 1000)
                    )
                );
            }
        }

        if (0 != $count % $batchSize) {
            $dotHelper->end(false);
            $flushEvent = $stopwatch->start('flush');
            $currentFlushEvent = $stopwatch->start('flush_' . $count);
            $output->write('<info>Flushing</info>');

            $this->dm->flush();
            $this->dm->clear(Product::getClassName());

            $flushEvent->stop();
            $currentFlushEvent->stop();

            $output->writeln(
                sprintf(
                    ' - Persist: %.01f prod/s. Flush+Clear: %d ms, %.01f prod/s',
                    $this->countSpeed($batchPersistEvent->getPeriods(), $batchPersistEvent->getDuration() / 1000),
                    $currentFlushEvent->getDuration(),
                    $this->countSpeed($batchPersistEvent->getPeriods(), $currentFlushEvent->getDuration() / 1000)
                )
            );
        }

        $allEvent->stop();

        $output->writeln('');
        $output->writeln(
            sprintf(
                '<info>Total persist</info> - %d products in %d seconds, %.01f prod/s',
                count($persistEvent->getPeriods()),
                $persistEvent->getDuration() / 1000,
                $this->countSpeed(count($persistEvent->getPeriods()), $persistEvent->getDuration() / 1000)
            )
        );
        $output->writeln(
            sprintf(
                '<info>Total flush</info> - %d flushes in %d seconds, average %d ms, %.01f prod/s',
                count($flushEvent->getPeriods()),
                $flushEvent->getDuration() / 1000,
                $flushEvent->getDuration() / count($flushEvent->getPeriods()),
                $this->countSpeed($persistEvent->getPeriods(), $flushEvent->getDuration() / 1000)
            )
        );

        $output->writeln(
            sprintf(
                '<info>Total</info> - took %d sec, speed - %.01f prod/s, memory - %d mb',
                $allEvent->getDuration() / 1000,
                $this->countSpeed($persistEvent->getPeriods(), $allEvent->getDuration() / 1000),
                $allEvent->getMemory() / 1048576
            )
        );

        $this->outputErrors($errors, $output);
    }

    /**
     * @param array|int|float $count
     * @param int|float $duration
     * @return float
     */
    protected function countSpeed($count, $duration)
    {
        if (is_array($count) || $count instanceof \Countable) {
            $count = count($count);
        }
        if ($duration > 0) {
            return $count / $duration;
        } else {
            return 0;
        }
    }

    /**
     * @param array $errors
     * @param OutputInterface $output
     */
    protected function outputErrors(array $errors, OutputInterface $output)
    {
        if (count($errors) > 0) {
            $output->writeln('<error>Errors</error>');
            foreach ($errors as $error) {
                $output->writeln(
                    sprintf(
                        '<comment>%s / %s</comment> - %s',
                        $error['sku'],
                        $error['name'],
                        $error['message']
                    )
                );
            }
        }
    }

    /**
     * @param GoodElement $good
     * @param bool $update
     * @return bool|Product
     * @throws ValidationFailedException
     */
    public function getProduct(GoodElement $good, $update = false)
    {
        $sku = $good->getMarkingOfTheGood();
        if (isset($this->productSkus[$sku])) {
            return false;
        }
        $product = ($update) ? $this->productRepository->findOneBySku($sku) : null;
        $product = $this->createProduct($good, $product);
        $this->validator->validate($product, null, true, true);
        $this->productSkus[$sku] = true;
        return $product;
    }

    /**
     * @param GoodElement $good
     * @param Product $product
     * @return Product
     */
    public function createProduct(GoodElement $good, Product $product = null)
    {
        $product = ($product) ?: new Product();
        $product->name = $good->getGoodName();
        $product->sku  = $good->getMarkingOfTheGood();
        $product->vat  = $good->getVat();
        $product->barcode = $good->getBarcode();
        $product->vendor = $good->getManufacturerName();
        $product->purchasePrice = $this->getPurchasePrice($good);
        $product->retailPricePreference = $product::RETAIL_PRICE_PREFERENCE_MARKUP;
        $product->retailMarkupMin = 15;
        $product->retailMarkupMax = 20;
        $product->typeProperties = $this->createType($good);

        $product->subCategory = $this->getCatalog($good);

        return $product;
    }

    /**
     * @param GoodElement $good
     * @return Typeable
     */
    public function createType(GoodElement $good)
    {
        switch ($good->getProductType()) {
            case GoodElement::PRODUCT_WEIGHT_ENTITY:
                return $this->createWeightType($good);
            case GoodElement::PRODUCT_PIECE_ENTITY:
            default:
                return $this->createUnitType($good);
        }
    }

    /**
     * @param GoodElement $good
     * @return UnitType
     */
    public function createUnitType(GoodElement $good)
    {
        return new UnitType();
    }

    /**
     * @param GoodElement $good
     * @return WeightType
     */
    public function createWeightType(GoodElement $good)
    {
        $type = new WeightType();
        $type->nameOnScales = $good->getPluginProperty(GoodElement::PLUGIN_PROPERTY_NAME_ON_SCALE_SCREEN);
        $type->descriptionOnScales = $good->getPluginProperty(GoodElement::PLUGIN_PROPERTY_DESCRIPTION_ON_SCALE_SCREEN);
        $type->ingredients = $good->getPluginProperty(GoodElement::PLUGIN_PROPERTY_COMPOSITION);
        $type->nutritionFacts = $good->getPluginProperty(GoodElement::PLUGIN_PROPERTY_FOOD_VALUE);
        $type->shelfLife = $good->getPluginProperty(GoodElement::PLUGIN_PROPERTY_GOOD_FOR_HOURS);
        return $type;
    }

    /**
     * @param GoodElement $good
     * @return Money
     */
    public function getPurchasePrice(GoodElement $good)
    {
        $salePrice = $good->getPrice();
        $salePriceMoney = $this->moneyModelTransformer->reverseTransform($salePrice);
        $purchasePrice = $salePriceMoney->mul(0.80);
        return $purchasePrice;
    }

    /**
     * @param GoodElement $good
     * @return SubCategory
     */
    public function getCatalog(GoodElement $good)
    {
        $groups = $this->normalizeGroups($good);

        $group = $this->getGroup($groups[0]['id'], $groups[0]['name']);
        $category = $this->getCategory($groups[1]['id'], $groups[1]['name'], $group);
        $subCategory = $this->getSubCategory($groups[2]['id'], $groups[2]['name'], $category);

        return $subCategory;
    }

    /**
     * @param string $id
     * @param string $name
     * @return Group
     */
    public function getGroup($id, $name)
    {
        if (!isset($this->groups[$id])) {
            $group = $this->groupRepository->findOneByName($name);
            if (!$group) {
                $group = new Group();
                $group->name = $name;
            }

            $this->groups[$id] = $group;
        }
        return $this->groups[$id];
    }

    /**
     * @param string $id
     * @param string $name
     * @param Group $group
     * @return Category
     */
    public function getCategory($id, $name, Group $group)
    {
        if (!isset($this->categories[$id])) {
            $category = null;
            if ($group->id) {
                $category = $this->categoryRepository->findOneByName($name, $group->id);
            }
            if (!$category) {
                $category = new Category();
                $category->name = $name;
                $category->group = $group;
            }

            $this->categories[$id] = $category;
        }
        return $this->categories[$id];
    }

    /**
     * @param string $id
     * @param string $name
     * @param Category $category
     * @return SubCategory
     */
    public function getSubCategory($id, $name, Category $category)
    {
        if (!isset($this->subCategories[$id])) {
            $subCategory = null;
            if ($category->id) {
                $subCategory = $this->subCategoryRepository->findOneByName($name, $category->id);
            }
            if (!$subCategory) {
                $subCategory = new SubCategory();
                $subCategory->name = $name;
                $subCategory->category = $category;
            }

            $this->subCategories[$id] = $subCategory;
        }
        return $this->subCategories[$id];
    }

    /**
     * @param GoodElement $good
     * @return array
     */
    protected function normalizeGroups(GoodElement $good)
    {
        $groups = $good->getGroups();
        $groupsCount = count($groups);
        if ($groupsCount == 0) {
            $groups = array(0 => array('id' => 'Unknown', 'name' => 'Unknown'));
        }
        if ($groupsCount > 3) {
            $groups = array_slice($groups, 0, 3);
        } elseif ($groupsCount < 3) {
            $lastGroup = end($groups);
            for ($i = $groupsCount; $i < 3; $i++) {
                $groups[] = $lastGroup;
            }
        }
        return $groups;
    }
}
