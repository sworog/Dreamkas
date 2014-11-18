<?php

namespace Lighthouse\JobBundle\Document\Job;

use Doctrine\ODM\MongoDB\LockMode;
use Lighthouse\CoreBundle\Document\DocumentRepository;

/**
 * @method Job find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 */
class JobRepository extends DocumentRepository
{
    /**
     * @return Job[]|\Doctrine\ODM\MongoDB\Cursor
     */
    public function findAll()
    {
        return $this->findBy(array(), array('dateCreated' => self::SORT_DESC));
    }
}
