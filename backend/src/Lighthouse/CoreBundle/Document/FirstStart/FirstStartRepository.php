<?php

namespace Lighthouse\CoreBundle\Document\FirstStart;

use Doctrine\ODM\MongoDB\LockMode;
use Lighthouse\CoreBundle\Document\DocumentRepository;

/**
 * @method FirstStart find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 */
class FirstStartRepository extends DocumentRepository
{
    /**
     * @return FirstStart
     */
    public function findOrCreate()
    {
        $firstStart = $this->findOneBy(array());
        if (null === $firstStart) {
            $firstStart = $this->createNew();
            $this->save($firstStart);
        }
        return $firstStart;
    }
}
