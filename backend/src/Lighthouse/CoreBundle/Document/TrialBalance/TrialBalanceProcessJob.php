<?php

namespace Lighthouse\CoreBundle\Document\TrialBalance;

use Lighthouse\CoreBundle\Document\Job\JobSilent;

/**
 * @property string $reasonId
 * @property string $reasonClassName
 * @property string $reasonType
 * @property int    $processType
 */
class TrialBalanceProcessJob extends JobSilent
{
    const TYPE = 'trial_balance_process';

    const PROCESS_TYPE_CREATE = 1;
    const PROCESS_TYPE_UPDATE = 2;
    const PROCESS_TYPE_REMOVE = 3;

    /**
     * @var string
     */
    protected $reasonId;

    /**
     * @var string
     */
    protected $reasonClassName;

    /**
     * @var string
     */
    protected $reasonType;

    /**
     * @var int
     */
    protected $processType = self::PROCESS_TYPE_CREATE;

    public function setReason(Reasonable $reason)
    {
        $this->reasonId = $reason->getReasonId();
        $this->reasonClassName = $reason->getClassName();
        $this->reasonType = $reason->getReasonType();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return TrialBalanceProcessJob::TYPE;
    }

    /**
     * @return array
     */
    public function getTubeData()
    {
        $data = parent::getTubeData();

        $data['processType']        = $this->processType;
        $data['reasonId']           = $this->reasonId;
        $data['reasonClassName']    = $this->reasonClassName;
        $data['reasonType']         = $this->reasonType;

        return $data;
    }

    /**
     * @param array $tubeData
     */
    public function setDataFromTube(array $tubeData)
    {
        parent::setDataFromTube($tubeData);

        $this->processType      = $tubeData['processType'];
        $this->reasonId         = $tubeData['reasonId'];
        $this->reasonClassName  = $tubeData['reasonClassName'];
        $this->reasonType       = $tubeData['reasonType'];
    }
}
