<?php

namespace Lighthouse\CoreBundle\Integration\Set10\ImportSales;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Restitution\Product\RestitutionProduct;
use Lighthouse\CoreBundle\Document\Restitution\Restitution;
use Lighthouse\CoreBundle\Document\Restitution\RestitutionRepository;
use Lighthouse\CoreBundle\Document\Sale\Product\SaleProduct;
use Lighthouse\CoreBundle\Document\Sale\Sale;
use Lighthouse\CoreBundle\Document\Sale\SaleRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Exception\ValidationFailedException;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\ValidatorInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.integration.set10.import_cheques.importer")
 */
class ChequesImporter
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var ValidatorInterface|ExceptionalValidator
     */
    protected $validator;

    /**
     * @var MoneyModelTransformer
     */
    protected $moneyTransformer;

    /**
     * @var int
     */
    protected $batchSize = 1000;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @DI\InjectParams({
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "storeRepository" = @DI\Inject("lighthouse.core.document.repository.store"),
     *      "validator" = @DI\Inject("lighthouse.core.validator"),
     *      "moneyTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model")
     * })
     * @param ProductRepository $productRepository
     * @param StoreRepository $storeRepository
     * @param ValidatorInterface $validator
     * @param MoneyModelTransformer $moneyTransformer
     */
    public function __construct(
        ProductRepository $productRepository,
        StoreRepository $storeRepository,
        ValidatorInterface $validator,
        MoneyModelTransformer $moneyTransformer
    ) {
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
        $this->validator = $validator;
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * @param ImportSalesXmlParser $parser
     * @param OutputInterface $output
     * @param int $batchSize
     */
    public function import(ImportSalesXmlParser $parser, OutputInterface $output, $batchSize = null)
    {
        $this->errors = array();
        $count = 0;
        $batchSize = ($batchSize) ?: $this->batchSize;
        $dm = $this->productRepository->getDocumentManager();

        while ($purchaseElement = $parser->readNextElement()) {
            $count++;
            try {
                $cheque = $this->createCheque($purchaseElement);
                if (!$cheque) {
                    $output->write('<error>S</error>');
                } else {
                    $this->validator->validate($cheque, null, true, true);
                    $dm->persist($cheque);
                    $output->write('.');
                    if (0 == $count % $batchSize) {
                        $dm->flush();
                        $output->write('<info>F</info>');
                    }
                }
            } catch (ValidationFailedException $e) {
                $output->write('<error>V</error>');
                $this->errors[] = array(
                    'count' => $count,
                    'exception' => $e
                );
            } catch (\Exception $e) {
                $output->write('<error>E</error>');
                $this->errors[] = array(
                    'count' => $count,
                    'exception' => $e
                );
            }
        }
        $dm->flush();

        $this->outputErrors($output, $this->errors);
    }

    /**
     * @param OutputInterface $output
     * @param array $errors
     */
    protected function outputErrors(OutputInterface $output, array $errors)
    {
        if (count($errors) > 0) {
            $output->writeln('');
            $output->writeln('<error>Errors</error>');
            foreach ($errors as $error) {
                $output->writeln(
                    sprintf(
                        '<comment>Sale #%d</comment> - %s',
                        $error['count'] - 1,
                        $error['exception']->getMessage()
                    )
                );
            }
        }
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @return Sale|Restitution|null
     */
    public function createCheque(PurchaseElement $purchaseElement)
    {
        if (true === $purchaseElement->getOperationType()) {
            return $this->createSale($purchaseElement);
        } elseif (false === $purchaseElement->getOperationType()) {
            return $this->createRestitution($purchaseElement);
        } else {
            return null;
        }
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @return Sale
     */
    public function createSale(PurchaseElement $purchaseElement)
    {
        $sale = new Sale();
        $sale->createdDate = $purchaseElement->getSaleDateTime();
        $sale->store = $this->getStore($purchaseElement->getShop());
        $sale->hash = $this->createChequeHash($purchaseElement);
        foreach ($purchaseElement->getPositions() as $positionElement) {
            $sale->products->add($this->createSaleProduct($positionElement));
        }
        return $sale;
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @return Sale
     */
    public function createRestitution(PurchaseElement $purchaseElement)
    {
        $sale = new Restitution();
        $sale->createdDate = $purchaseElement->getSaleDateTime();
        $sale->store = $this->getStore($purchaseElement->getShop());
        $sale->hash = $this->createChequeHash($purchaseElement);
        foreach ($purchaseElement->getPositions() as $positionElement) {
            $sale->products->add($this->createRestitutionProduct($positionElement));
        }
        return $sale;
    }

    /**
     * @param PurchaseElement $purchaseElement
     * @return string
     */
    protected function createChequeHash(PurchaseElement $purchaseElement)
    {
        $hashStr = '';
        /* @var \SimpleXMLElement $attr */
        foreach ($purchaseElement->attributes() as $attr) {
            $hashStr.= sprintf('%s:%s;', $attr->getName(), $attr);
        }
        return md5($hashStr);
    }

    /**
     * @param PositionElement $positionElement
     * @return SaleProduct
     */
    public function createSaleProduct(PositionElement $positionElement)
    {
        $saleProduct = new SaleProduct();
        $saleProduct->quantity = $this->roundQuantity($positionElement->getCount());
        $saleProduct->sellingPrice = $this->moneyTransformer->reverseTransform($positionElement->getCostWithDiscount());
        $saleProduct->product = $this->getProduct($positionElement->getGoodsCode());
        return $saleProduct;
    }

    /**
     * @param PositionElement $positionElement
     * @return RestitutionProduct
     */
    public function createRestitutionProduct(PositionElement $positionElement)
    {
        $restitutionProduct = new RestitutionProduct();
        $restitutionProduct->quantity = $this->roundQuantity($positionElement->getCount());
        $restitutionProduct->sellingPrice = $this->
            moneyTransformer->
            reverseTransform($positionElement->getCostWithDiscount());
        $restitutionProduct->product = $this->getProduct($positionElement->getGoodsCode());
        return $restitutionProduct;
    }

    /**
     * @param string $sku
     * @throws RuntimeException
     * @return Product
     */
    public function getProduct($sku)
    {
        $product = $this->productRepository->findOneBy(array('sku' => $sku));
        if (!$product) {
            throw new RuntimeException(sprintf('Product with sku "%s" not found', $sku));
        }
        return $product;
    }

    /**
     * @param string $storeNumber
     * @return Store
     * @throws RuntimeException
     */
    public function getStore($storeNumber)
    {
        $store = $this->storeRepository->findOneBy(array('number' => $storeNumber));
        if (!$store) {
            throw new RuntimeException(sprintf('Store with number "%s" not found', $storeNumber));
        }
        return $store;
    }

    /**
     * @param string $count
     * @return float
     */
    protected function roundQuantity($count)
    {
        $quantity = (float) $count;
        if ((float) (int) $quantity === $quantity) {
            return (int) $quantity;
        } else {
            return $quantity;
        }
    }
}
