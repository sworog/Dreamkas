<?php

namespace Lighthouse\CoreBundle\Document\Log;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class LogRepository extends DocumentRepository
{
    /**
     * @param string $message
     * @param \DateTime $date
     */
    public function createLog($message, \DateTime $date = null)
    {
        $log = $this->createNew();
        $log->message = $message;
        if ($date !== null) {
            $log->date = $date;
        }

        $this->save($log);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Cursor
     */
    public function findAll()
    {
        return $this->findBy(array(), array('date' => -1));
    }
}
