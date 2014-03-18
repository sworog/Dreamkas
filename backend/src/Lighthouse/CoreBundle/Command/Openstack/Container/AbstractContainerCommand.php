<?php

namespace Lighthouse\CoreBundle\Command\Openstack\Container;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use JMS\DiExtraBundle\Annotation as DI;
use OpenCloud\ObjectStore\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @DI\Service("lighthouse.core.command.openstack.container", abstract=true)
 */
abstract class AbstractContainerCommand extends ContainerAwareCommand
{
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
     *      "defaultName" = @DI\Inject("%openstack.selectel.storage.container.name%"),
     *      "defaultMetaData" = @DI\Inject("%openstack.selectel.storage.container.metadata%"),
     * })
     * @param string $defaultName
     * @param array $defaultMetaData
     */
    public function __construct($defaultName, array $defaultMetaData = array())
    {
        $this->defaultName = $defaultName;
        $this->defaultMetaData = $defaultMetaData;

        parent::__construct('openstack:container:' . $this->getOp());
    }

    protected function configure()
    {
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Container name', $this->defaultName);
    }

    /**
     * @return Service
     */
    protected function getStorageService()
    {
        return $this->getContainer()->get('openstack.selectel.storage');
    }

    /**
     * @return string
     */
    abstract protected function getOp();
}
