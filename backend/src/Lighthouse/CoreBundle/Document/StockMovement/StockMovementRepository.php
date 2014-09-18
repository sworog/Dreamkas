<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Types\Numeric\NumericFactory;

class StockMovementRepository extends DocumentRepository
{
    /**
     * @var NumericFactory
     */
    protected $numericFactory;

    /**
     * @param NumericFactory $numericFactory
     */
    public function setNumericFactory(NumericFactory $numericFactory)
    {
        $this->numericFactory = $numericFactory;
    }

    /**
     * @return StockMovement
     */
    public function createNew()
    {
        /* @var StockMovement $stockMovement */
        $stockMovement = parent::createNew();
        $stockMovement->sumTotal = $this->numericFactory->createMoney();

        return $stockMovement;
    }

    /**
     * @return Cursor|StockMovement[]
     */
    public function findAll()
    {
        return $this->findBy(array(), array('date' => self::SORT_DESC));
    }

    /**
     * @param StockMovementFilter $filter
     * @return Cursor|StockMovement[]
     */
    public function findByFilter(StockMovementFilter $filter)
    {
        $qb = $this->createQueryBuilder();

        if (isset($filter->types)) {
            $qb->field('type')->in($filter->types);
        } else {
            $qb->field('type')->notIn(Receipt::getReceiptTypes());
        }
        if (isset($filter->dateFrom)) {
            $qb->field('date')->gte($filter->dateFrom);
        }
        if (isset($filter->dateTo)) {
            $qb->field('date')->lte($filter->dateTo);
        }
        $qb->sort('date', self::SORT_DESC);

        return $qb->getQuery()->execute();
    }

    /**
     * @param StockMovement $stockMovement
     */
    public function resetProducts(StockMovement $stockMovement)
    {
        foreach ($stockMovement->products as $key => $invoiceProduct) {
            unset($stockMovement->products[$key]);
            $this->getDocumentManager()->remove($invoiceProduct);
        }
        $stockMovement->products = new ArrayCollection();
    }
}
