<?php

namespace Lighthouse\JobBundle\Document\Job;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class JobRepository extends DocumentRepository
{
    /**
     * @return array|\Doctrine\ODM\MongoDB\Cursor
     */
    public function findAll()
    {
        return $this->findBy(array(), array('dateCreated' => self::SORT_DESC));
    }
}
