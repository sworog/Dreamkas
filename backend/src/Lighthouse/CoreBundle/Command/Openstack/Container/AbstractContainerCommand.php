<?php

namespace Lighthouse\CoreBundle\Command\Openstack\Container;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use JMS\DiExtraBundle\Annotation as DI;
use OpenCloud\ObjectStore\Service;

/**
 * @DI\Service("lighthouse.core.command.openstack.container", abstract=true)
 */
abstract class AbstractContainerCommand extends Command
{
    /**
     * @var Service
     */
    protected $storageService;

    /**
     * @var string
     */
    protected $defaultName;

    /**
     * @var array
     */
    protected $defaultMetaData;

    /**
     * @DI\InjectParams({
     *      "storageService" = @DI\Inject("openstack.selectel.storage"),
     *      "defaultName" = @DI\Inject("%openstack.selectel.storage.container.name%"),
     *      "defaultMetaData" = @DI\Inject("%openstack.selectel.storage.container.metadata%"),
     * })
     * @param Service $storageService
     * @param string $defaultName
     * @param array $defaultMetaData
     */
    public function __construct(Service $storageService, $defaultName, array $defaultMetaData = array())
    {
        $this->storageService = $storageService;
        $this->defaultName = $defaultName;
        $this->defaultMetaData = $defaultMetaData;

        parent::__construct('openstack:container:' . $this->getOp());
    }

    protected function configure()
    {
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Container name', $this->defaultName);
    }

    /**
     * @return string
     */
    abstract protected function getOp();
}
