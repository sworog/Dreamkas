<?php

namespace Lighthouse\CoreBundle\Integration\Set10\ImportSales;

use Lighthouse\CoreBundle\Document\Config\ConfigRepository;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\Integration\Set10\Import\Set10Import;
use Lighthouse\CoreBundle\Util\Url;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.integration.set10.import_sales.remote_directory")
 */
class RemoteDirectory
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * @var string
     */
    protected $dirUrl;

    /**
     * @DI\InjectParams({
     *      "configRepository" = @DI\Inject("lighthouse.core.document.repository.config")
     * })
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @throws \Lighthouse\CoreBundle\Exception\RuntimeException
     * @return \SplFileInfo[]
     */
    public function read()
    {
        $files = array();
        $dirUrl = $this->getDirUrl();
        try {
            $directory = new \DirectoryIterator($dirUrl);
            if (!$directory->isDir()) {
                throw new RuntimeException(sprintf('Directory "%s" is not a directory', $dirUrl));
            } elseif (!$directory->isReadable()) {
                throw new RuntimeException(sprintf('Directory "%s" is not readable', $dirUrl));
            } elseif (!$directory->isWritable()) {
                throw new RuntimeException(sprintf('Directory "%s" is not writable', $dirUrl));
            }
        } catch (\RuntimeException $e) {
            throw new RuntimeException(sprintf('Failed to read directory "%s"', $dirUrl));
        }
        /* @var \DirectoryIterator $file */
        foreach ($directory as $file) {
            if ($file->isFile() && 'xml' == $file->getExtension()) {
                $files[] = new \SplFileInfo($file->getPathname());
            }
        }
        return $files;
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function deleteFile($filePath)
    {
        if ($filePath instanceof \SplFileInfo) {
            $filePath = $filePath->getPathname();
        }
        return unlink($filePath);
    }

    /**
     * @return string
     */
    public function getDirUrl()
    {
        if (null === $this->dirUrl) {
            $dirUrl = new Url($this->configRepository->findValueByName(Set10Import::URL_CONFIG_NAME));
            $dirUrl->setPart(Url::USER, $this->configRepository->findValueByName(Set10Import::LOGIN_CONFIG_NAME));
            $dirUrl->setPart(Url::PASS, $this->configRepository->findValueByName(Set10Import::PASSWORD_CONFIG_NAME));
            $this->dirUrl = $dirUrl->toString();
        }
        return $this->dirUrl;
    }
}
