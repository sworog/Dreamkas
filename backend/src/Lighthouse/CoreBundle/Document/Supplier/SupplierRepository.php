<?php

namespace Lighthouse\CoreBundle\Document\Supplier;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

/**
 * @method Supplier createNew()
 * @method Supplier find($id)
 * @method Supplier findOneBy(array $criteria, array $sort = array(), array $hints = array())
 * @method Supplier[]|Cursor findAll
 * @method Supplier[]|Cursor findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
 */
class SupplierRepository extends DocumentRepository
{
    /**
     * @param string $id
     * @return NullSupplier
     */
    public function getNullObject($id)
    {
        $nullSupplier = new NullSupplier();
        $nullSupplier->id = $id;
        return $nullSupplier;
    }

    /**
     * @param string $name
     * @return Supplier|null
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }

    /**
     * @return Cursor|Supplier[]
     */
    public function findAllActive()
    {
        return $this->findBy(
            array('deletedAt' => null),
            array('id' => self::SORT_ASC)
        );
    }
}
