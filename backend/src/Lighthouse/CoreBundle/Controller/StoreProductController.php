<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Store\StoreProductRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Symfony\Component\Form\AbstractType;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreProductController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.store_product")
     * @var StoreProductRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     * @throws \Lighthouse\CoreBundle\Exception\RuntimeException
     */
    protected function getDocumentFormType()
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param Store $store
     * @param Product $product
     */
    public function getStoreProductAction(Store $store, Product $product)
    {
        $storeProduct = $this->documentRepository->findOrCreateByStoreProduct($store, $product);
        if (!$storeProduct) {
            throw new NotFoundHttpException(sprintf('%s object not found.', 'StoreProduct'));
        }
        return $storeProduct;
    }
}
