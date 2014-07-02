<?php

namespace Lighthouse\CoreBundle\Document\BankAccount;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Organization\Organizationable;
use MongoId;

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
     * @param Organizationable $organization
     * @return Cursor|BankAccount[]
     */
    public function findByOrganization(Organizationable $organization)
    {
        $criteria = array(
            'organization.$id' => new MongoId($organization->getOrganizationId()),
            'organization.$ref' => $organization->getOrganizationType(),
        );
        return $this->findBy($criteria);
    }
}
