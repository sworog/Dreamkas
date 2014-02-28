<?php

namespace Lighthouse\CoreBundle\OpenStack;

use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions\CredentialError;
use OpenCloud\Common\Service\Catalog;
use OpenCloud\Common\Service\CatalogItem;
use OpenCloud\Common\Service\Endpoint;
use OpenCloud\Identity\Resource\Token;
use OpenCloud\ObjectStore\Service as ObjectStoreService;
use OpenCloud\OpenStack;

/**
 * @method Token getTokenObject
 * @method Catalog getCatalog
 */
class SelectelStorage extends OpenStack
{
    const DEFAULT_REGION = 'SPB';
    const DEFAULT_NAME = 'Selectel';

    /**
     * @return array
     * @throws CredentialError
     */
    public function getCredentials()
    {
        $secret = $this->getSecret();
        if (isset($secret['username'], $secret['password'])) {
            return array(
                'X-Auth-User' => $secret['username'],
                'X-Auth-Key' => $secret['password']
            );
        } else {
            throw new CredentialError(
                Lang::translate('Unrecognized credential secret')
            );
        }
    }

    public function authenticate()
    {
        $response = $this->get(
            $this->getAuthUrl(),
            $this->getCredentials()
        )->send();

        $expires = (string) $response->getHeader('X-Expire-Auth-Token');
        $storageUrl = (string) $response->getHeader('X-Storage-Url');
        $token = (string) $response->getHeader('X-Auth-Token');

        $catalog  = $this->createCatalog($storageUrl);

        $this->importCredentials(
            array(
                'token'      => $token,
                'expiration' => $expires,
                'catalog'    => $catalog
            )
        );
    }

    /**
     * @param string $storageUrl
     * @return Catalog
     */
    protected function createCatalog($storageUrl)
    {
        $catalogItems = array(
            $this->createStorageCatalogItem($storageUrl)
        );
        return Catalog::factory($catalogItems);
    }

    /**
     * @param string $storageUrl
     * @return CatalogItem
     */
    protected function createStorageCatalogItem($storageUrl)
    {
        $catalogItem = new CatalogItem();
        $catalogItem->setName(self::DEFAULT_NAME);
        $catalogItem->setType(ObjectStoreService::DEFAULT_TYPE);
        $catalogItem->setEndpoints(array(
            $this->createStorageEndPoint($storageUrl)
        ));

        return $catalogItem;
    }

    /**
     * @param string $publicUrl
     * @return Endpoint
     */
    protected function createStorageEndPoint($publicUrl)
    {
        $endPoint = new \stdClass();
        $endPoint->publicURL = $publicUrl;
        $endPoint->region = self::DEFAULT_REGION;

        return $endPoint;
    }

    /**
     * @param string $name
     * @param string $region
     * @param string $urlType
     * @return ObjectStoreService
     */
    public function objectStoreService($name = null, $region = null, $urlType = null)
    {
        $name = ($name) ?: self::DEFAULT_NAME;
        $region = ($region) ?: self::DEFAULT_REGION;
        return parent::objectStoreService($name, $region, $urlType);
    }
}
