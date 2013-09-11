<?php

namespace Lighthouse\CoreBundle\Integration\Set10;

use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Document\Job\Integration\Set10\ExportProductsJob;
use Lighthouse\CoreBundle\Document\Product\ProductRepository;
use Lighthouse\CoreBundle\Document\Job\Job;
use Lighthouse\CoreBundle\Job\Worker\WorkerInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.job.integration.set10.export_products")
 * @DI\Tag("job.worker")
 */
class ExportProductsWorker implements WorkerInterface
{
    /**
     * @var Set10ProductConverter
     */
    protected $converter;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    protected $set10Url = '';
    protected $set10Login = '';
    protected $set10Password = '';

    /**
     * @DI\InjectParams({
     *      "converter" = @DI\Inject("lighthouse.core.service.convert.set10.product"),
     *      "productRepository" = @DI\Inject("lighthouse.core.document.repository.product"),
     *      "configRepository" = @DI\Inject("lighthouse.core.document.repository.config"),
     * })
     * @param Set10ProductConverter $converter
     * @param ProductRepository $productRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        Set10ProductConverter $converter,
        ProductRepository $productRepository,
        ConfigRepository $configRepository
    ) {
        $this->converter = $converter;
        $this->productRepository = $productRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @param Job $job
     * @return boolean
     */
    public function supports(Job $job)
    {
        if ($job instanceof ExportProductsJob) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param \Lighthouse\CoreBundle\Document\Job\Job $job
     * @return mixed result of work
     */
    public function work(Job $job)
    {
        if (!$this->validateConfig()) {
            $job->setFailStatus("failed set 10 integration config");

            return;
        }

        $url = $this->getUrl();
        if (!is_dir($url)
            || !is_dir($url . "/source")
            || !is_writable($url . "/source")
        ) {
            $job->setFailStatus("set 10 doesn't answer");

            return;
        }

        $remoteXmlFile = fopen($url . "/source/catalog-goods_" . time() . ".xml", "w");
        fwrite($remoteXmlFile, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<goods-catalog>");

        $products = $this->productRepository->findAll();
        foreach ($products as $product) {
            $xmlProducts = $this->converter->makeXmlByProduct($product);
            array_map(
                function ($xml) use ($remoteXmlFile) {
                    fwrite($remoteXmlFile, $xml);
                },
                $xmlProducts
            );
        }

        fwrite($remoteXmlFile, "</goods-catalog>");
        fclose($remoteXmlFile);
    }

    public function getUrl()
    {
        $parsedUrl = parse_url($this->set10Url);
        if ($this->set10Login != '') {
            $authUrl = '';
            if (array_key_exists('scheme', $parsedUrl)) {
                $authUrl.= $parsedUrl['scheme'] . "://";
            }
            $authUrl.= $this->set10Login;
            if ($this->set10Password != '') {
                $authUrl.= ":" . $this->set10Password;
            }
            $authUrl.= "@";
            $authUrl.= $parsedUrl['host'];
            if (array_key_exists('path', $parsedUrl)) {
                $authUrl.= $parsedUrl['path'];
            }
            return $authUrl;
        } else {
            return $this->set10Url;
        }
    }

    public function validateConfig()
    {
        $url = $this->configRepository->findOneBy(array('name' => Set10::URL_CONFIG_NAME));
        if (!$url || $url->value == '') {
            return false;
        } else {
            $this->set10Url = $url->value;
        }

        $login = $this->configRepository->findOneBy(array('name' => Set10::LOGIN_CONFIG_NAME));
        if ($login) {
            $this->set10Login = $login->value;
        }

        $password = $this->configRepository->findOneBy(array('name' => Set10::PASSWORD_CONFIG_NAME));
        if ($password) {
            $this->set10Password = $password->value;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'set10_export_products';
    }

}
