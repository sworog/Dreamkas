<?php

namespace Lighthouse\CoreBundle\Document\Log;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use DateTime;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Log\LogRepository"
 * )
 *
 * @property int        $id
 * @property DateTime  $date
 * @property string     $message
 */
class Log extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $date;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $message;

    /**
     * @MongoDB\PrePersist
     * @MongoDB\PreUpdate
     */
    public function prePersist()
    {
        if (empty($this->date)) {
            $this->date = new DateTime();
        }
    }
}
