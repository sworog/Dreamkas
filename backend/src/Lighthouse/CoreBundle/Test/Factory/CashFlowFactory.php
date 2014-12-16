<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use DateTime;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlow;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlowRepository;

class CashFlowFactory extends AbstractFactory
{
    const DEFAULT_DIRECTION = 'in';
    const DEFAULT_AMOUNT = 1111;

    /**
     * @param string $id
     * @return CashFlow
     * @throws \RuntimeException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function getCashFlowById($id)
    {
        $cashFlow = $this->getCashFlowRepository()->find($id);
        if (null === $cashFlow) {
            throw new \RuntimeException(sprintf('CashFlow id#%s not found', $id));
        }
        return $cashFlow;
    }

    /**
     * @param string $direction
     * @param int $amount
     * @param string|null $date
     * @param string|null $comment
     * @param bool $validate
     * @internal param string $name
     * @internal param bool $validate
     * @return CashFlow
     */
    public function createCashFlow(
        $direction = self::DEFAULT_DIRECTION,
        $amount = self::DEFAULT_AMOUNT,
        $date = null,
        $comment = null,
        $validate = true
    ) {
        $cashFlow = new CashFlow();
        $cashFlow->direction = $direction;
        $cashFlow->amount = $this->getNumericFactory()->createMoney($amount);
        $cashFlow->date = new DateTime($date);
        $cashFlow->comment = $comment;

        $this->doSave($cashFlow, $validate);

        return $cashFlow;
    }

    /**
     * @param string $id
     */
    public function deleteCashFlowById($id)
    {
        $cashFlow = $this->getCashFlowById($id);
        $this->doDelete($cashFlow);
    }

    /**
     * @param CashFlow $cashFlow
     */
    public function deleteCashFlow(CashFlow $cashFlow)
    {
        $this->deleteCashFlowById($cashFlow->id);
    }

    /**
     * @return CashFlowRepository
     */
    protected function getCashFlowRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.cash_flow');
    }
}
