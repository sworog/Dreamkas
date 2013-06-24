<?php

namespace Lighthouse\CoreBundle\Document\Auth;

use FOS\OAuthServerBundle\Document\Client as BaseClient;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 */
class Client extends BaseClient
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;
}
