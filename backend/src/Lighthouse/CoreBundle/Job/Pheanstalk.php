<?php

namespace Lighthouse\CoreBundle\Job;

use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;
use Pheanstalk_PheanstalkInterface as PheanstalkInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @DI\Service("lighthouse.core.job.pheanstalk")
 */
class Pheanstalk extends PheanstalkProxy
{
    /**
     * @var string
     */
    protected $tubePrefix = '';

    /**
     * @DI\InjectParams({
     *      "dispatcher" = @DI\Inject("event_dispatcher"),
     *      "tubePrefix" = @DI\Inject("%lighthouse.core.job.tube.prefix%")
     * })
     * @param EventDispatcherInterface $dispatcher
     * @param string $tubePrefix
     */
    public function __construct(EventDispatcherInterface $dispatcher, $tubePrefix)
    {
        $this->setDispatcher($dispatcher);
        $this->setTubePrefix($tubePrefix);
    }

    /**
     * @param string $tubePrefix
     */
    public function setTubePrefix($tubePrefix)
    {
        $this->tubePrefix = $tubePrefix;
    }

    /**
     * @param string $tube
     * @return string
     */
    protected function getTubeName($tube)
    {
        return $this->tubePrefix . $tube;
    }

    /**
     * @param string $tube
     * @return mixed
     */
    public function ignore($tube)
    {
        $tube = $this->getTubeName($tube);
        return parent::ignore($tube);
    }

    /**
     * @param string $tube
     * @param int $delay
     * @return mixed
     */
    public function pauseTube($tube, $delay)
    {
        $tube = $this->getTubeName($tube);
        return parent::pauseTube($tube, $delay);
    }

    /**
     * @param string $tube
     * @param string $data
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     * @return int
     */
    public function putInTube(
        $tube,
        $data,
        $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        $delay = PheanstalkInterface::DEFAULT_DELAY,
        $ttr = PheanstalkInterface::DEFAULT_TTR
    ) {
        $tube = $this->getTubeName($tube);
        return parent::putInTube($tube, $data, $priority, $delay, $ttr);
    }

    /**
     * @param string $tube
     * @param null $timeout
     * @return object
     */
    public function reserveFromTube($tube, $timeout = null)
    {
        $tube = $this->getTubeName($tube);
        return parent::reserveFromTube($tube, $timeout);
    }

    /**
     * @param string $tube
     * @return object
     */
    public function statsTube($tube)
    {
        $tube = $this->getTubeName($tube);
        return parent::statsTube($tube);
    }

    /**
     * @param string $tube
     * @return mixed
     */
    public function useTube($tube)
    {
        $tube = $this->getTubeName($tube);
        return parent::useTube($tube);
    }

    /**
     * @param string $tube
     * @return mixed
     */
    public function watch($tube)
    {
        $tube = $this->getTubeName($tube);
        return parent::watch($tube);
    }

    /**
     * @param string $tube
     * @return mixed
     */
    public function watchOnly($tube)
    {
        $tube = $this->getTubeName($tube);
        return parent::watchOnly($tube);
    }

    /**
     * @param null $tube
     * @return object
     */
    public function peekReady($tube = null)
    {
        $tube = (null !== $tube) ? $this->getTubeName($tube) : null;
        return parent::peekReady($tube);
    }

    /**
     * @param null $tube
     * @return object
     */
    public function peekDelayed($tube = null)
    {
        $tube = (null !== $tube) ? $this->getTubeName($tube) : null;
        return parent::peekDelayed($tube);
    }

    /**
     * @param null $tube
     * @return object
     */
    public function peekBuried($tube = null)
    {
        $tube = (null !== $tube) ? $this->getTubeName($tube) : null;
        return parent::peekBuried($tube);
    }
}
