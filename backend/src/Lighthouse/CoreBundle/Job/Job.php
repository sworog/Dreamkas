<?php

namespace Lighthouse\CoreBundle\Job;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Pheanstalk_Job as PheanstalkJob;
use DateTime;

/**
 * @property string $id
 * @property string $jobId
 * @property string $status
 * @property string $title
 * @property string $finalMessage
 * @property DateTime $dateCreated
 * @property DateTime $dateStarted
 * @property DateTime $dateFinished
 * @property float $duration
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Job\JobRepository"
 * )
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="type")
 * @MongoDB\DiscriminatorMap({
 *      "recalcProductPrice"="Lighthouse\CoreBundle\Document\Product\RecalcProductPrice\RecalcProductPriceJob",
 * })
 */
abstract class Job extends AbstractDocument
{
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';

    /**
     * @Serializer\Exclude
     * @var array
     */
    static public $statuses = array(
        self::STATUS_NEW,
        self::STATUS_PENDING,
        self::STATUS_PROCESSING,
        self::STATUS_SUCCESS,
        self::STATUS_FAIL
    );

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $jobId;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $status;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $title;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $finalMessage;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $dateStarted;

    /**
     * @MongoDB\Date
     * @var DateTime
     */
    protected $dateFinished;

    /**
     * Time job took to process (dateFinished - dateStarted)
     *
     * @MongoDB\Float
     * @var float
     */
    protected $duration;

    /**
     * @Serializer\Exclude
     * @var PheanstalkJob
     */
    protected $tubeJob;

    /**
     *
     */
    public function __construct()
    {
        $this->setNewStatus();
    }

    /**
     *
     */
    public function setNewStatus()
    {
        $this->status = self::STATUS_NEW;
        $this->dateCreated = new DateTime();
    }

    /**
     * @param string $jobId job id in tube
     */
    public function setPendingStatus($jobId)
    {
        $this->jobId = $jobId;
        $this->status = self::STATUS_PENDING;
    }

    /**
     *
     */
    public function setProcessingStatus()
    {
        $this->status = self::STATUS_PROCESSING;
        $this->dateStarted = new Datetime();
    }

    /**
     * @param string $message
     */
    public function setSuccessStatus($message = null)
    {
        $this->setDoneStatus(self::STATUS_SUCCESS, $message);
    }

    /**
     * @param string $message
     */
    public function setFailStatus($message = null)
    {
        $this->setDoneStatus(self::STATUS_FAIL, $message);
    }

    /**
     * @param string $status
     * @param string $message
     */
    protected function setDoneStatus($status, $message = null)
    {
        $this->status = $status;
        $this->dateFinished = new DateTime();
        $this->duration = $this->dateFinished->getTimestamp() - $this->dateStarted->getTimestamp();
        if ($message) {
            $this->finalMessage = $message;
        }
    }

    /**
     * @Serializer\VirtualProperty
     * @return string
     */
    abstract public function getType();

    /**
     * @param PheanstalkJob $tubeJob
     */
    public function setTubeJob(PheanstalkJob $tubeJob)
    {
        $this->tubeJob = $tubeJob;
    }

    /**
     * @return PheanstalkJob
     */
    public function getTubeJob()
    {
        return $this->tubeJob;
    }
}
