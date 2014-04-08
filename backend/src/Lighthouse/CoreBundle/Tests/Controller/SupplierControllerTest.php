<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\Client\JsonRequest;
use Lighthouse\CoreBundle\Test\WebTestCase;
use SebastianBergmann\Exporter\Exporter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MongoDuplicateKeyException;
use Exception;

class SupplierControllerTest extends WebTestCase
{
    public function testPost()
    {
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postData = array(
            'name' => 'ООО "ЕвроАрт"',
            'agreement' => null,
        );
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/suppliers',
            $postData
        );
        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);
        Assert::assertJsonPathEquals($postData['name'], 'name', $postResponse);
    }

    /**
     * @dataProvider postValidationProvider
     * @param array $postData
     * @param $expectedResponseCode
     * @param array $assertions
     */
    public function testPostValidation(array $postData, $expectedResponseCode, array $assertions)
    {
        $this->factory->createSupplier('ООО "Повтор"');
        $file = $this->factory->createFile();
        $this->factory->flush();

        $postData += array(
            'name' => '',
            'agreement' => $file->id
        );
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/suppliers',
            $postData
        );
        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @return array
     */
    public function postValidationProvider()
    {
        return array(
            'name valid length 100' => array(
                array(
                    'name' => str_repeat('z', 100),
                ),
                201,
                array(
                )
            ),
            'name invalid length 101' => array(
                array(
                    'name' => str_repeat('z', 101),
                ),
                400,
                array(
                    'children.name.errors.0' => 'Не более 100 символов',
                    'children.name.errors.1' => null
                )
            ),
            'name empty' => array(
                array(
                    'name' => '',
                ),
                400,
                array(
                    'children.name.errors.0' => 'Заполните это поле',
                    'children.name.errors.1' => null
                )
            ),
            'duplicate' => array(
                array(
                    'name' => 'ООО "Повтор"'
                ),
                400,
                array(
                    'children.name.errors.0' => 'Поставщик с таким названием уже существует',
                    'children.name.errors.1' => null
                )
            ),
            'empty agreement file' => array(
                array(
                    'name' => 'ООО "Поставщик"',
                ),
                201,
                array(
                )
            ),
            'invalid agreement file' => array(
                array(
                    'name' => 'ООО "Поставщик"',
                    'agreement' => '52f34a5f02af59ed408b4587'
                ),
                400,
                array(
                    'children.agreement.errors.0' => 'Указан неверный файл договора',
                    'children.agreement.errors.1' => null
                )
            ),
        );
    }

    /**
     * @dataProvider postDuplicateNameProvider
     * @param string $firstName
     * @param string $secondName
     */
    public function testPostDuplicateName($firstName, $secondName)
    {
        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $postData = array(
            'name' => $firstName
        );
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/suppliers',
            $postData
        );
        $this->assertResponseCode(201);
        Assert::assertJsonPathEquals($postData['name'], 'name', $postResponse);

        $postData = array(
            'name' => $secondName
        );
        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/suppliers',
            $postData
        );
        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals(
            'Поставщик с таким названием уже существует',
            'children.name.errors.0',
            $postResponse
        );
        Assert::assertNotJsonHasPath('children.name.errors.1', $postResponse);
    }

    /**
     * @return array
     */
    public function postDuplicateNameProvider()
    {
        return array(
            'no spaces' => array('OOO "ЕвроАрт"', 'OOO "ЕвроАрт"'),
            'space before' => array('OOO "ЕвроАрт"', ' OOO "ЕвроАрт"'),
            'space after' => array('OOO "ЕвроАрт"', 'OOO "ЕвроАрт" '),
            'space before and after' => array('OOO "ЕвроАрт"', ' OOO "ЕвроАрт" '),
            'many spaces' => array('OOO "ЕвроАрт"', '    OOO "ЕвроАрт"  '),
        );
    }

    public function testPut()
    {
        $supplier = $this->factory->createSupplier('ООО "ЕврейАрт"');
        $this->factory->flush();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $putData = array(
            'name' => 'ООО "ЕвроАрт"'
        );
        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/suppliers/' . $supplier->id,
            $putData
        );
        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals($supplier->id, 'id', $putResponse);
        Assert::assertJsonPathEquals($putData['name'], 'name', $putResponse);
    }

    /**
     * @dataProvider postValidationProvider
     * @param array $data
     * @param int $expectedResponseCode
     * @param array $assertions
     */
    public function testPutValidation($data, $expectedResponseCode, $assertions)
    {
        $supplier = $this->factory->createSupplier('ООО "ЕврейАрт"');
        $this->factory->createSupplier('ООО "Повтор"');
        $file = $this->factory->createFile();
        $this->factory->flush();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $putData = $data + array('agreement' => $file->id);

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/suppliers/' . $supplier->id,
            $putData
        );
        $expectedResponseCode = ($expectedResponseCode == 201) ? 200 : $expectedResponseCode;
        $this->assertResponseCode($expectedResponseCode);

        $this->performJsonAssertions($putResponse, $assertions);
    }

    public function testGetAction()
    {
        $this->factory->createSupplier('1');
        $this->factory->createSupplier('2');
        $this->factory->createSupplier('3');
        $this->factory->flush();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/suppliers'
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathCount(3, '*.id', $getResponse);
        Assert::assertJsonPathEquals('1', '*.name', $getResponse, 1);
        Assert::assertJsonPathEquals('2', '*.name', $getResponse, 1);
        Assert::assertJsonPathEquals('3', '*.name', $getResponse, 1);
    }

    /**
     * @dataProvider getActionPermissionsProvider
     * @param string $role
     * @param int $expectedCode
     */
    public function testGetActionPermissions($role, $expectedCode)
    {
        $this->factory->createSupplier('1');
        $this->factory->createSupplier('2');
        $this->factory->createSupplier('3');
        $this->factory->flush();

        $accessToken = $this->factory->oauth()->authAsRole($role);

        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/suppliers'
        );

        $this->assertResponseCode($expectedCode);
    }

    /**
     * @return array
     */
    public function getActionPermissionsProvider()
    {
        return array(
            User::ROLE_COMMERCIAL_MANAGER => array(User::ROLE_COMMERCIAL_MANAGER, 200),
            User::ROLE_DEPARTMENT_MANAGER => array(User::ROLE_DEPARTMENT_MANAGER, 200),
            User::ROLE_STORE_MANAGER => array(User::ROLE_STORE_MANAGER, 200),
            User::ROLE_ADMINISTRATOR => array(User::ROLE_ADMINISTRATOR, 403),
        );
    }

    public function testGetSupplierAction()
    {
        $supplier1 = $this->factory->createSupplier('1');
        $this->factory->createSupplier('2');
        $this->factory->flush();

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);
        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/suppliers/' . $supplier1->id
        );

        $this->assertResponseCode(200);
        Assert::assertJsonPathEquals('1', 'name', $getResponse);
    }

    /**
     * @dataProvider getActionPermissionsProvider
     */
    public function testGetSupplierActionPermissions($role, $expectedResponseCode)
    {
        $supplier1 = $this->factory->createSupplier('1');
        $this->factory->createSupplier('2');
        $this->factory->flush();

        $accessToken = $this->factory->oauth()->authAsRole($role);
        $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/suppliers/' . $supplier1->id
        );

        $this->assertResponseCode($expectedResponseCode);
    }

    /**
     * @group unique
     */
    public function testUniqueUsernameInParallel()
    {
        $supplierData = array(
            'name' => 'Поставщик',
        );

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $jsonRequest = new JsonRequest('/api/1/suppliers', 'POST', $supplierData);
        $jsonRequest->setAccessToken($accessToken);

        $responses = $this->client->parallelJsonRequest($jsonRequest, 3);
        $statusCodes = array();
        $jsonResponses = array();
        foreach ($responses as $response) {
            $statusCodes[] = $response->getStatusCode();
            $jsonResponses[] = $this->client->decodeJsonResponse($response);
        }
        $exporter = new Exporter();
        $responseBody = $exporter->export($jsonResponses);
        $this->assertCount(1, array_keys($statusCodes, 201), $responseBody);
        $this->assertCount(2, array_keys($statusCodes, 400), $responseBody);
        Assert::assertJsonPathEquals('Поставщик', '*.name', $jsonResponses, 1);
        Assert::assertJsonPathEquals(
            'Поставщик с таким названием уже существует',
            '*.children.name.errors.0',
            $jsonResponses,
            2
        );
    }

    protected function doPostActionFlushFailedException(\Exception $exception)
    {
        $supplierData = array(
            'name' => 'Поставщик',
        );

        $accessToken = $this->factory->oauth()->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $supplier = new Supplier();

        $documentManagerMock = $this->getMock(
            'Doctrine\\ODM\\MongoDB\\DocumentManager',
            array(),
            array(),
            '',
            false
        );
        $documentManagerMock
            ->expects($this->once())
            ->method('persist');

        $documentManagerMock
            ->expects($this->once())
            ->method('flush')
            ->with($this->isEmpty())
            ->will($this->throwException($exception));

        $supplierRepoMock = $this->getMock(
            'Lighthouse\\CoreBundle\\Document\\Supplier\\SupplierRepository',
            array(),
            array(),
            '',
            false
        );

        $supplierRepoMock
            ->expects($this->once())
            ->method('createNew')
            ->will($this->returnValue($supplier));
        $supplierRepoMock
            ->expects($this->exactly(2))
            ->method('getDocumentManager')
            ->will($this->returnValue($documentManagerMock));

        $this->client->addTweaker(
            function (ContainerInterface $container) use ($supplierRepoMock) {
                $container->set('lighthouse.core.document.repository.supplier', $supplierRepoMock);
            }
        );

        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/suppliers',
            $supplierData
        );

        return $response;
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedException()
    {
        $exception = new Exception('Unknown exception');
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(500);
        Assert::assertJsonPathEquals('Unknown exception', 'message', $response);
    }

    /**
     * @group unique
     */
    public function testPostActionFlushFailedMongoDuplicateKeyException()
    {
        $exception = new MongoDuplicateKeyException();
        $response = $this->doPostActionFlushFailedException($exception);

        $this->assertResponseCode(400);
        Assert::assertJsonPathEquals('Validation Failed', 'message', $response);
        Assert::assertJsonPathEquals(
            'Поставщик с таким названием уже существует',
            'children.name.errors.0',
            $response
        );
    }

    /**
     * @group unique
     * @expectedException MongoDuplicateKeyException
     */
    public function testDoubleCreate()
    {
        $this->factory->createSupplier();
        $this->factory->flush();
        $this->factory->createSupplier();
        $this->factory->flush();
    }
}
