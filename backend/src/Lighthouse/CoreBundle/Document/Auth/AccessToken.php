<?php

namespace Lighthouse\CoreBundle\Document\Auth;

use FOS\OAuthServerBundle\Document\AccessToken as BaseAccessToken;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @@MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Auth\Client",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Client
     */
    protected $client;
}
