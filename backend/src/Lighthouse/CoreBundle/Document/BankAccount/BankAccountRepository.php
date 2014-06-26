<?php

namespace Lighthouse\CoreBundle\Document\BankAccount;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;

/**
 * @method BankAccount createNew()
 * @method BankAccount find($id)
 * @method BankAccount findOneBy(array $criteria, array $sort = array(), array $hints = array())
 * @method BankAccount[]|Cursor findAll
 * @method BankAccount[]|Cursor findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
 */
class BankAccountRepository extends DocumentRepository
{
}
