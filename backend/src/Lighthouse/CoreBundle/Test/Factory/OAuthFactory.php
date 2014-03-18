<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\User\User;
use OAuth2\OAuth2;
use Lighthouse\CoreBundle\Document\Auth\Client as AuthClient;
use Symfony\Component\HttpFoundation\Request;
use stdClass;

class OAuthFactory extends AbstractFactory
{
    const CLIENT_DEFAULT_SECRET = 'secret';
    const USER_DEFAULT_PASSWORD = 'password';

    /**
     * @var AuthClient
     */
    protected $authClient;

    /**
     * @var array
     */
    protected $accessTokens = array();

    /**
     * @return AuthClient
     */
    public function getAuthClient()
    {
        if (null === $this->authClient) {
            $this->authClient = $this->createAuthClient();
        }
        return $this->authClient;
    }

    /**
     * @param string $secret
     * @return AuthClient
     */
    public function createAuthClient($secret = self::CLIENT_DEFAULT_SECRET)
    {
        $client = new AuthClient;
        $client->setSecret($secret);

        $this->getDocumentManager()->persist($client);
        $this->getDocumentManager()->flush();

        return $client;
    }

    /**
     * @return OAuth2
     */
    protected function getOAuth2Server()
    {
        return $this->container->get('fos_oauth_server.server');
    }

    /**
     * @param User $oauthUser
     * @param string $password
     * @param AuthClient $oauthClient
     * @return \stdClass access token
     */
    public function doAuth(
        User $oauthUser,
        $password = UserFactory::USER_DEFAULT_PASSWORD,
        AuthClient $oauthClient = null
    ) {
        $oauthClient = ($oauthClient) ?: $this->getAuthClient();

        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('grant_type', OAuth2::GRANT_TYPE_USER_CREDENTIALS);
        $request->request->set('username', $oauthUser->username);
        $request->request->set('password', $password);
        $request->request->set('client_id', $oauthClient->getPublicId());
        $request->request->set('client_secret', $oauthClient->getSecret());

        $response = $this->getOAuth2Server()->grantAccessToken($request);

        $content = $response->getContent();
        $token = json_decode($content);

        return $token;
    }

    /**
     * @param User $oauthUser
     * @param string $password
     * @param AuthClient $oauthClient
     * @return \stdClass
     */
    public function auth(
        User $oauthUser,
        $password = UserFactory::USER_DEFAULT_PASSWORD,
        AuthClient $oauthClient = null
    ) {
        if (UserFactory::USER_DEFAULT_PASSWORD === $password && null === $oauthClient) {
            if (!isset($this->accessTokens[$oauthUser->id])) {
                $this->accessTokens[$oauthUser->id] = $this->doAuth($oauthUser, $password, $oauthClient);
            }
            return $this->accessTokens[$oauthUser->id];
        } else {
            return $this->doAuth($oauthUser, $password, $oauthClient);
        }
    }

    /**
     * @param string $role
     * @return \stdClass
     */
    public function authAsRole($role)
    {
        $user = $this->factory->user()->getRoleUser($role);
        return $this->auth($user);
    }

    /**
     * @param string $storeId
     * @return stdClass
     */
    public function authAsStoreManager($storeId = null)
    {
        $storeId = $this->factory->store()->getStoreById($storeId);
        $storeManager = $this->factory->store()->getStoreManager($storeId);
        return $this->auth($storeManager);
    }

    /**
     * @param string $storeId
     * @return stdClass
     */
    public function authAsDepartmentManager($storeId = null)
    {
        $storeId = $this->factory->store()->getStoreById($storeId);
        $departmentManager = $this->factory->store()->getDepartmentManager($storeId);
        return $this->auth($departmentManager);
    }
}
