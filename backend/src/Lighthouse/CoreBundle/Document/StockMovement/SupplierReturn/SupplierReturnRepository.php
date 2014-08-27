<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\LockMode;
use Lighthouse\CoreBundle\Document\StockMovement\StockMovementRepository;

/**
 * @method SupplierReturn find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method SupplierReturn[]|Collection findAll()
 * @method SupplierReturn createNew()
 */
class SupplierReturnRepository extends StockMovementRepository
{
}
