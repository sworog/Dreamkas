<?php

namespace Lighthouse\CoreBundle\Test\Factory\StockIn;

use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockIn;
use Lighthouse\CoreBundle\Document\StockMovement\StockIn\StockInRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Factory\AbstractFactory;

class StockInFactory extends AbstractFactory
{
    /**
     * @param Store $store
     * @param string $date
     * @return StockInBuilder
     */
    public function createStockIn(Store $store = null, $date = null)
    {
        $builder = new StockInBuilder(
            $this->factory,
            $this->getStockInRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->createStockIn($store, $date);
    }

    /**
     * @param $id
     * @return StockIn
     */
    public function getStockInById($id)
    {
        $stockIn = $this->getStockInRepository()->find($id);
        if (null === $stockIn) {
            throw new \RuntimeException(sprintf('StockIn id#%s not found', $id));
        }
        return $stockIn;
    }

    /**
     * @return StockInRepository
     */
    public function getStockInRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.stock_movement.stockin');
    }
}
