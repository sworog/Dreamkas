<?php

namespace Lighthouse\CoreBundle\Test\Factory\WriteOff;

use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOff;
use Lighthouse\CoreBundle\Document\StockMovement\WriteOff\WriteOffRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Test\Factory\AbstractFactory;

class WriteOffFactory extends AbstractFactory
{
    /**
     * @param Store $store
     * @param string $date
     * @param string $number
     * @return WriteOffBuilder
     */
    public function createWriteOff(Store $store = null, $date = null, $number = null)
    {
        $builder = new WriteOffBuilder(
            $this->factory,
            $this->getWriteOffRepository(),
            $this->factory->getValidator(),
            $this->factory->getNumericFactory()
        );
        return $builder->createWriteOff($store, $date, $number);
    }

    /**
     * @param $id
     * @return WriteOff
     */
    public function getWriteOffById($id)
    {
        $writeOff = $this->getWriteOffRepository()->find($id);
        if (null === $writeOff) {
            throw new \RuntimeException(sprintf('WriteOff id#%s not found', $id));
        }
        return $writeOff;
    }

    /**
     * @return WriteOffRepository
     */
    public function getWriteOffRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.stock_movement.writeoff');
    }
}
