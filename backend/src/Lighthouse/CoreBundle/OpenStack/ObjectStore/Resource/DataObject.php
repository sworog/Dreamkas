<?php

namespace Lighthouse\CoreBundle\OpenStack\ObjectStore\Resource;

use OpenCloud\ObjectStore\Resource\DataObject as BaseDataObject;

class DataObject extends BaseDataObject
{
    /**
     * @return \OpenCloud\Common\Metadata
     */
    public function retrieveMetadata()
    {
        $response = $this->getClient()
            ->head($this->getUrl())
            ->send();

        $this->populateFromResponse($response);
        return $this->metadata;
    }
}
