<?php

namespace Lighthouse\CoreBundle\Tests\OpenStack;

use Lighthouse\CoreBundle\Test\TestCase;
use OpenCloud\Identity\Resource\Token;
use OpenCloud\OpenStack;

class UploadTest extends TestCase
{
    public function testUpload()
    {
        $user = '18487';
        $key = '4hSmzgdljt';
        $client = new OpenStack(
            'https://auth.selcdn.ru',
            array(
                'username' => $user,
                'password' => $key,
            )
        );
        $request = $client->get(
            'https://auth.selcdn.ru',
            array(
                'X-Auth-User' => $user,
                'X-Auth-Key' => $key
            )
        );
        $response = $request->send();

        $expire = (string) $response->getHeader('X-Expire-Auth-Token');
        $storageUrl = (string) $response->getHeader('X-Storage-Url');
        $token = (string) $response->getHeader('X-Auth-Token');

        $client->setToken($token);
        $client->getTokenObject()->setExpires($expire);

        $endPoint = new \stdClass();
        $endPoint->tenantId = $user;
        $endPoint->publicURL = $storageUrl;
        $endPoint->versionId = 1;
        $endPoint->region = 'spb';

        $catalog = new \stdClass();
        $catalog->name = "selectel";
        $catalog->type = "object-store";
        $catalog->endpoints = array(
            $endPoint
        );
        $client->setCatalog(array($catalog));
        $storeService = $client->objectStoreService('selectel', 'spb');
        $containers = $storeService->listContainers();
    }
}
