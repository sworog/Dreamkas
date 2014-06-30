<?php

namespace Lighthouse\CoreBundle\Document\BankAccount;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Organization\Organization;

/**
 * @method BankAccount createNew()
 * @method BankAccount find($id)
 * @method BankAccount findOneBy(array $criteria, array $sort = array(), array $hints = array())
 * @method BankAccount[]|Cursor findAll
 * @method BankAccount[]|Cursor findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
 */
class BankAccountRepository extends DocumentRepository
{
    /**
     * @param Organization $organization
     * @return Cursor|BankAccount[]
     */
    public function findByOrganization(Organization $organization)
    {
        return $this->findBy(array(
            'organization' => $organization->id
        ));
    }
}
