<?php

namespace Lighthouse\CoreBundle\Document\Auth;

use FOS\OAuthServerBundle\Document\Client as BaseClient;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use OAuth2\OAuth2;
use Lighthouse\CoreBundle\MongoDB\Mapping\Annotations\GlobalDb;

/**
 * @MongoDB\Document
 * @GlobalDb
 */
class Client extends BaseClient
{
    /**
     * @MongoDB\Id(
     *      strategy="custom",
     *      options={
     *          "class" = "Lighthouse\CoreBundle\MongoDB\Id\SelectiveGenerator"
     *      }
     * )
     * @var string
     */
    protected $id;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->allowedGrantTypes = array(
            OAuth2::GRANT_TYPE_USER_CREDENTIALS,
            OAuth2::GRANT_TYPE_AUTH_CODE,
            OAuth2::GRANT_TYPE_REFRESH_TOKEN,
        );
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
