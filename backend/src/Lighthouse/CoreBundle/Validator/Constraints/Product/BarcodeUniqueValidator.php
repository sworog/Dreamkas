<?php

namespace Lighthouse\CoreBundle\Validator\Constraints\Product;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Validator\Constraints\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.validator.product.barcode_unique")
 * @DI\Tag("validator.constraint_validator", attributes={"alias"="product_barcode_unique_validator"})
 */
class BarcodeUniqueValidator extends ConstraintValidator
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @DI\InjectParams({
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product")
     * })
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Product $value
     * @param Constraint|BarcodeUnique $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $barcodes = array();
        if ($value->barcode) {
            $barcodes['barcode'] = $value->barcode;
        }
        foreach ($value->barcodes as $index => $productBarcode) {
            $property = "barcodes[{$index}].barcode";
            $barcodes[$property] = $productBarcode->barcode;
        }

        $this->checkBarcodeDuplicates($barcodes, $constraint);

        if (!empty($barcodes)) {
            $products = $this->productRepository->findByBarcodes(array_values($barcodes));
            foreach ($products as $product) {
                if ($product === $value) {
                    continue;
                }
                foreach ($barcodes as $subPath => $barcode) {
                    if ($product->hasProductBarcode($barcode)) {
                        $this->context->addViolationAt(
                            $subPath,
                            $constraint->outerMessage,
                            array(
                                '{{ product.name }}' => $product->name,
                                '{{ product.sku }}' => $product->sku
                            ),
                            $barcode
                        );
                    }
                }
            }
        }
    }

    /**
     * @param array $barcodes
     * @param BarcodeUnique $constraint
     */
    protected function checkBarcodeDuplicates(array $barcodes, BarcodeUnique $constraint)
    {
        while (list($property, $barcode) = each($barcodes)) {
            $duplicateProperties = array_keys($barcodes, $barcode);
            foreach ($duplicateProperties as $duplicateProperty) {
                if ($duplicateProperty != $property) {
                    $this->context->addViolationAt(
                        $duplicateProperty,
                        $constraint->innerMessage,
                        array(),
                        $barcodes[$duplicateProperty]
                    );
                    unset($barcodes[$duplicateProperty]);
                }
            }
        }
    }
}
