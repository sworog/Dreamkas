<?php

namespace Lighthouse\CoreBundle\Document\StockMovement;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

class StockMovementRepository extends DocumentRepository
{
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
        $criteria = array();
        if (isset($filter->types)) {
            $criteria['type'] = array('$in' => $filter->types);
        }
        if (isset($filter->dateFrom)) {
            $criteria['date']['$gte'] = $filter->dateFrom;
        }
        if (isset($filter->dateTo)) {
            $criteria['date']['$lte'] = $filter->dateTo;
        }
        return $this->findBy($criteria, array('date' => self::SORT_DESC));
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
