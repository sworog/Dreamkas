<?php

namespace Lighthouse\CoreBundle\Versionable;

interface VersionInterface
{
    /**
     * @return array
     */
    public function getVersionFields();

    /**
     * @param string $version
     * @return void
     */
    public function setVersion($version);

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @param $object
     * @return mixed
     */
    public function setObject(VersionableInterface $object);

    /**
     * @return mixed
     */
    public function getObject();
}
