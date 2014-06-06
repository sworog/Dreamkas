<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Karzer\Karzer;

class LighthouseKernel extends Kernel
{
    /**
     * @var string
     */
    protected $cacheDir;

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new Ekino\Bundle\NewRelicBundle\EkinoNewRelicBundle(),
            new Leezy\PheanstalkBundle\LeezyPheanstalkBundle(),
            new Clamidity\ProfilerBundle\ClamidityProfilerBundle(),
            new Lighthouse\CoreBundle\LighthouseCoreBundle(),
            new Lighthouse\ReportsBundle\LighthouseReportsBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new Ornicar\ApcBundle\OrnicarApcBundle(),
            new Hackzilla\Bundle\PasswordGeneratorBundle\HackzillaPasswordGeneratorBundle(),
        );


        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
        }

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        if (null === $this->cacheDir) {
            $this->setCacheDir(parent::getCacheDir() . Karzer::getThreadName());
        }
        return $this->cacheDir;
    }

    /**
     * @param $cacheDir
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return array
     */
    protected function getKernelParameters()
    {
        $parameters = parent::getKernelParameters();
        $parameters['karzer.thread'] = Karzer::getThreadName();
        return $parameters;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array($this->environment, $this->debug, Karzer::getThreadNumber()));
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        list($environment, $debug, $karzerThreadNumber) = unserialize($data);

        Karzer::setThreadNumber($karzerThreadNumber);
        $this->__construct($environment, $debug);
    }

    /**
     * @return LighthouseKernel
     */
    public function boot()
    {
        parent::boot();
        return $this;
    }
}
