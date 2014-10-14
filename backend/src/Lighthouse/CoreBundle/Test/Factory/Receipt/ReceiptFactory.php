<?php

namespace Lighthouse\CoreBundle\Test\Factory\Receipt;

use Lighthouse\CoreBundle\Document\StockMovement\Receipt;
use Lighthouse\CoreBundle\Document\StockMovement\ReceiptRepository;
use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Factory\AbstractFactory;

class ReceiptFactory extends AbstractFactory
{
    /**
     * @param Store $store
     * @param string $date
     * @param Sale $sale
     * @return ReceiptBuilder
     */
    public function createReturn(Store $store = null, $date = null, Sale $sale = null)
    {
        $builder = new ReceiptBuilder(
            $this->factory,
            $this->getReceiptRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->createReturn($store, $date, $sale);
    }

    /**
     * @param Store $store
     * @param string $date
     * @return ReceiptBuilder
     */
    public function createSale(Store $store = null, $date = null)
    {
        $builder = new ReceiptBuilder(
            $this->factory,
            $this->getReceiptRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->createSale($store, $date);
    }

    /**
     * @param string $id
     * @return Receipt|Sale|Returne
     */
    public function getReceiptById($id)
    {
        $receipt = $this->getReceiptRepository()->find($id);
        if (null === $receipt) {
            throw new \RuntimeException(sprintf('Receipt id#%s not found', $id));
        }
        return $receipt;
    }

    /**
     * @param Receipt $receipt
     */
    public function deleteReceipt(Receipt $receipt)
    {
        $this->getDocumentManager()->remove($receipt);
        $this->getDocumentManager()->flush();
    }

    /**
     * @return ReceiptRepository
     */
    public function getReceiptRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.receipt');
    }
}
