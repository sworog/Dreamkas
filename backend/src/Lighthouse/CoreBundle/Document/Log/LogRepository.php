<?php

namespace Lighthouse\CoreBundle\Document\Log;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use DateTime;

/**
 * @method Log createNew()
 * @method Log find($id)
 * @method Log findOneBy(array $criteria, array $sort = array(), array $hints = array())
 * @method Log[]|Cursor findBy(array $criteria, array $sort = null, $limit = null, $skip = null)
 */
class LogRepository extends DocumentRepository
{
    /**
     * @param string $message
     * @param DateTime $date
     * @return Log
     */
    public function createLog($message, DateTime $date = null)
    {
        $log = $this->createNew();
        $log->message = $message;
        if ($date !== null) {
            $log->date = $date;
        }

        $this->save($log);
        return $log;
    }

    /**
     * @return Cursor|Log[]
     */
    public function findAll()
    {
        return $this->findBy(array(), array('date' => self::SORT_DESC));
    }
}
