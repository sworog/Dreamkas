<?php

namespace Lighthouse\CoreBundle\Document\Auth;

use FOS\OAuthServerBundle\Document\AuthCode as BaseAuthCode;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Auth\Client",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Client
     */
    protected $client;
}
