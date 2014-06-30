<?php

namespace Lighthouse\CoreBundle\Document\Organization;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\Department\DepartmentRepository;

/**
 * @method Organization createNew()
 * @method Organization find($id)
 * @method Organization findOneBy(array $criteria, array $sort = array(), array $hints = array())
 * @method Organization[]|Cursor findAll
 * @method Organization[]|Cursor findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
 */
class OrganizationRepository extends DepartmentRepository
{
}
