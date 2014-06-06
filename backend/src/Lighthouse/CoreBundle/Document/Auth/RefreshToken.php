<?php

namespace Lighthouse\CoreBundle\Document\Auth;

use FOS\OAuthServerBundle\Document\RefreshToken as BaseRefreshToken;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\MongoDB\Mapping\Annotations\GlobalDb;

/**
 * @MongoDB\Document
 * @GlobalDb
 */
class RefreshToken extends BaseRefreshToken
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

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\User\User",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var User
     */
    protected $user;
}
