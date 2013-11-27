<?php

namespace Lighthouse\CoreBundle\Integration\Set10\Import\Products;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
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
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Symfony\Component\Console\Output\OutputInterface;
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
     */
    public function import(
        Set10ProductImportXmlParser $parser,
        OutputInterface $output,
        $batchSize = null,
        $update = false
    ) {
        $errors = array();
        $memStart = memory_get_usage();
        $count = 0;
        $batchSize = ($batchSize) ?: $this->batchSize;
        $verbose = $output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL;
        $totalStartTime = microtime(true);
        $startItemTime = microtime(true);
        $flushStartTime = microtime(true);
        $flushCount = 0;
        $lineCount = 0;
        while ($goodElement = $parser->readNextElement()) {
            $count++;
            $flushCount++;
            $lineCount++;
            try {
                $product = $this->getProduct($goodElement, $update);
                if ($product) {
                    $dot = ($product->id) ? 'U' : '.';
                    $this->dm->persist($product);
                    if ($verbose) {
                        $output->writeln(sprintf('<info>Persist product "%s"</info>', $product->name));
                    } else {
                        $output->write($dot);
                    }
                } else {
                    $output->write('S');
                }
            } catch (ValidationFailedException $e) {
                $errors[] = array(
                    'exception' => $e,
                    'product' => $goodElement,
                );
                if ($verbose) {
                    $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
                } else {
                    $output->write('<error>E</error>');
                }
            }

            $stopItemTime = microtime(true);
            $itemTime = $stopItemTime - $startItemTime;
            if ($verbose) {
                $output->writeln(sprintf('<comment>Item time: %.02f ms</comment>', $itemTime * 1000));
            }
            $startItemTime = microtime(true);

            if ($this->lineWidth == $lineCount) {
                $output->writeln(sprintf('   %s', $count));
                $lineCount = 0;
            }

            if (0 == $count % $batchSize) {
                if (0 != $lineCount) {
                    $output->writeln('');
                }
                $output->write('<info>Flushing</info>');
                $this->dm->flush();
                $this->dm->clear();
                $flushTime = microtime(true) - $flushStartTime;
                if ($verbose) {
                    $output->writeln('<info>Flushing</info>');
                } else {
                    $output->writeln(sprintf(' - %.02f prod/s', $flushCount / $flushTime));
                }
                $flushStartTime = microtime(true);
                $flushCount = 0;
                $lineCount = 0;
            }
        }
        $this->dm->flush();
        $this->dm->clear();

        $totalTime = microtime(true) - $totalStartTime;
        $output->writeln('');
        $output->writeln(
            sprintf(
                '<info>Total</info> - %d products in %d seconds, %.02f prod/s',
                $count,
                $totalTime,
                $count / $totalTime
            )
        );
        $memStop = memory_get_usage();
        $output->writeln(
            sprintf(
                '<info>Memory usage</info> - start %dMB, end %dMB, diff %dMB, peak %dMB',
                $memStart / 1048576,
                $memStop / 1048576,
                ($memStop - $memStart)  / 1048576,
                memory_get_peak_usage() / 1048576
            )
        );
        if (count($errors) > 0) {
            $output->writeln('<error>Errors</error>');
            foreach ($errors as $error) {
                $output->writeln(
                    sprintf(
                        '<comment>%s / %s</comment> - %s',
                        $error['product']->getSku(),
                        $error['product']->getGoodName(),
                        $error['exception']->getMessage()
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
        $sku = $good->getSku();
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
        $product->sku  = $good->getSku();
        $product->vat  = $good->getVat();
        $product->barcode = $good->getBarcode();
        $product->vendor = $good->getVendor();
        $product->units = $good->getUnits() ?: Product::UNITS_UNIT;
        $product->purchasePrice = $this->getPurchasePrice($good);
        $product->retailPricePreference = $product::RETAIL_PRICE_PREFERENCE_MARKUP;
        $product->retailMarkupMin = 15;
        $product->retailMarkupMax = 20;

        $product->subCategory = $this->getCatalog($good);

        return $product;
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
            return $groups;
        } elseif ($groupsCount > 3) {
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
