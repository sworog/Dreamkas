<?php

namespace Lighthouse\CoreBundle\Document\Auth;

use FOS\OAuthServerBundle\Document\RefreshToken as BaseRefreshToken;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 */
class RefreshToken extends BaseRefreshToken
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