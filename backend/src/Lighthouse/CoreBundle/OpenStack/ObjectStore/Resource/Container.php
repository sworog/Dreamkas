<?php

namespace Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource;

use OpenCloud\ObjectStore\Resource\Container as BaseContainer;

class Container extends BaseContainer
{
    /**
     * @param array $metadata
     * @param bool $stockPrefix
     * @return mixed
     */
    public function saveMetadata(array $metadata = null, $stockPrefix = true)
    {
        $metadata = (null === $metadata) ? $this->metadata->toArray() : $metadata;
        return parent::saveMetadata($metadata, $stockPrefix);
    }

    /**
     * @param null $info
     * @return DataObject
     */
    public function dataObject($info = null)
    {
        return new DataObject($this, $info);
    }
}
