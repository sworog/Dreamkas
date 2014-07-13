<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class OrganizationControllerTest extends WebTestCase
{
    /**
     * @dataProvider postActionProvider
     * @param array $postData
     * @param int $expectedCode
     * @param array $assertions
     */
    public function testPostAction(array $postData, $expectedCode, array $assertions)
    {
        $user = $this->factory()->user()->createProjectUser();

        $accessToken = $this->factory()->oauth()->auth($user);

        $postData += array(
            'name' => '',
            'phone' => '',
            'fax' => '',
            'email' => '',
            'director' => '',
            'chiefAccountant' => '',
            'address' => '',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/organizations',
            $postData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);

        if (201 === $expectedCode) {
            Assert::assertJsonHasPath('id', $postResponse);

            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/organizations/' . $postResponse['id']
            );

            $this->assertSame($postResponse, $getResponse);
        }
    }

    /**
     * @return array
     */
    public function postActionProvider()
    {
        return array(
            'only valid name' => array(
                array(
                    'name' => 'ООО "Каннибал"',
                ),
                201,
                array(
                    'name' => 'ООО "Каннибал"',
                )
            ),
            'all data filled' => array(
                array(
                    'name' => 'ИП "Борисов"',
                    'phone' => '+79023456789',
                    'fax' => '(812) 234-09-88',
                    'email' => 'boris.ov@list.ru',
                    'director' => 'Борисов Иван Петрович',
                    'chiefAccountant' => 'Борисова Надежда Ильинишна',
                    'address' => 'Борисполь, ул. Ленина д.38',
                ),
                201,
                array(
                    'name' => 'ИП "Борисов"',
                    'phone' => '+79023456789',
                    'fax' => '(812) 234-09-88',
                    'email' => 'boris.ov@list.ru',
                    'director' => 'Борисов Иван Петрович',
                    'chiefAccountant' => 'Борисова Надежда Ильинишна',
                    'address' => 'Борисполь, ул. Ленина д.38',
                ),
            ),
            'missing name' => array(
                array(
                    'name' => '',
                ),
                400,
                array(
                    'children.name.errors.0' => 'Заполните это поле',
                )
            ),
            'length validation valid' => array(
                array(
                    'name' => str_repeat('n', 300),
                    'phone' => str_repeat('p', 300),
                    'fax' => str_repeat('f', 300),
                    'email' => str_repeat('e', 300),
                    'director' => str_repeat('p', 300),
                    'chiefAccountant' => str_repeat('c', 300),
                    'address' => str_repeat('a', 300),
                ),
                201,
                array()
            ),
            'length validation invalid' => array(
                array(
                    'name' => str_repeat('n', 301),
                    'phone' => str_repeat('p', 301),
                    'fax' => str_repeat('f', 301),
                    'email' => str_repeat('e', 301),
                    'director' => str_repeat('p', 301),
                    'chiefAccountant' => str_repeat('c', 301),
                    'address' => str_repeat('a', 301),
                ),
                400,
                array(
                    'children.name.errors.0' => 'Не более 300 символов',
                    'children.phone.errors.0' => 'Не более 300 символов',
                    'children.fax.errors.0' => 'Не более 300 символов',
                    'children.email.errors.0' => 'Не более 300 символов',
                    'children.director.errors.0' => 'Не более 300 символов',
                    'children.chiefAccountant.errors.0' => 'Не более 300 символов',
                    'children.address.errors.0' => 'Не более 300 символов',
                )
            ),
        );
    }

    /**
     * @dataProvider postActionProvider
     * @param array $putData
     * @param int $expectedCode
     * @param array $assertions
     */
    public function testPutAction(array $putData, $expectedCode, array $assertions)
    {
        $user = $this->factory()->user()->createProjectUser();

        $accessToken = $this->factory()->oauth()->auth($user);

        $postData = array(
            'name' => 'Колян'
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/organizations',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);
        $id = $postResponse['id'];

        $putData += array(
            'name' => '',
            'phone' => '',
            'fax' => '',
            'email' => '',
            'director' => '',
            'chiefAccountant' => '',
            'address' => '',
        );

        $putResponse = $this->clientJsonRequest(
            $accessToken,
            'PUT',
            '/api/1/organizations/' . $id,
            $putData
        );

        $expectedCode = (201 === $expectedCode) ? 200 : $expectedCode;
        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($putResponse, $assertions);

        if (200 === $expectedCode) {
            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/organizations/' . $postResponse['id']
            );

            $this->assertSame($putResponse, $getResponse);
        }
    }

    public function testGetOrganizationsAction()
    {
        $user = $this->factory()->user()->createProjectUser();

        $accessToken = $this->factory()->oauth()->auth($user);

        $ids = array();
        for ($i = 1; $i <= 5; $i++) {
            $ids[] = $this->createOrganization('Колян ' . $i, $accessToken);
        }

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/organizations'
        );
        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(5, '*.id', $getResponse);
        foreach ($ids as $id) {
            Assert::assertJsonPathEquals($id, '*.id', $getResponse, 1);
        }

        $this->assertSame(array_values($getResponse), $getResponse);
    }

    /**
     * @param array|string $postData
     * @param object $accessToken
     * @return string
     */
    protected function createOrganization($postData, $accessToken)
    {
        if (is_string($postData)) {
            $postData = array('name' => $postData);
        }

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/organizations',
            $postData
        );

        $this->assertResponseCode(201);
        Assert::assertJsonHasPath('id', $postResponse);
        return $postResponse['id'];
    }

    /**
     * @dataProvider patchActionProvider
     * @param array $postData
     * @param int $expectedCode
     * @param array $assertions
     */
    public function testPatchAction(array $postData, $expectedCode, array $assertions = array())
    {
        $user = $this->factory()->user()->createProjectUser();
        $accessToken = $this->factory()->oauth()->auth($user);

        $organizationId = $this->createOrganization('Контора', $accessToken);

        $data = array(
            'legalDetails' => $postData
        );

        $patchResponse = $this->clientJsonRequest(
            $accessToken,
            'PATCH',
            '/api/1/organizations/' . $organizationId,
            $data
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($patchResponse, $assertions);

        if (200 == $expectedCode) {

            $getResponse = $this->clientJsonRequest(
                $accessToken,
                'GET',
                '/api/1/organizations/' . $organizationId
            );

            $this->assertResponseCode(200);

            $this->assertSame($patchResponse, $getResponse);
        }
    }

    /**
     * @return array
     */
    public function patchActionProvider()
    {
        return array(
            'legal entity' => array(
                array(
                    'fullName' => 'ООО "Рога и Копыта"',
                    'type' => LegalEntityLegalDetails::TYPE
                ),
                200,
                array(
                    'legalDetails.fullName' => 'ООО "Рога и Копыта"',
                    'legalDetails.type' => LegalEntityLegalDetails::TYPE
                )
            ),
            'entrepreneur entity' => array(
                array(
                    'fullName' => 'ИП Рогаикопытов',
                    'type' => EntrepreneurLegalDetails::TYPE
                ),
                200,
                array(
                    'legalDetails.fullName' => 'ИП Рогаикопытов',
                    'legalDetails.type' => EntrepreneurLegalDetails::TYPE
                )
            ),
            'invalid type' => array(
                array(
                    'fullName' => 'ООО "Рога и Копыта"',
                    'type' => 'ltd'
                ),
                400,
                array(
                    'children.legalDetails.children.type.errors.0' => 'Выбранное Вами значение недопустимо.'
                )
            ),
            // Legal Entity
            'legal entity all field valid' => array(
                array(
                    'type' => LegalEntityLegalDetails::TYPE,
                    'fullName' => 'ООО "ДРИНК"',
                    'legalAddress' => ' 650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                    'okpo' => '37718962',
                    'inn' => '4205244100',
                    'kpp' => '420501001',
                    'ogrn' => '1124205008487',
                ),
                200,
                array(
                    'legalDetails.type' => LegalEntityLegalDetails::TYPE,
                    'legalDetails.fullName' => 'ООО "ДРИНК"',
                    'legalDetails.legalAddress' => '650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                    'legalDetails.okpo' => '37718962',
                    'legalDetails.inn' => '4205244100',
                    'legalDetails.kpp' => '420501001',
                    'legalDetails.ogrn' => '1124205008487',
                )
            ),
            'legal entity invalid length' => array(
                array(
                    'type' => LegalEntityLegalDetails::TYPE,
                    'fullName' => str_repeat('f', 301),
                    'legalAddress' => str_repeat('l', 301),
                ),
                400,
                array(
                    'children.legalDetails.children.fullName.errors.0' => 'Не более 300 символов',
                    'children.legalDetails.children.legalAddress.errors.0' => 'Не более 300 символов',
                )
            ),
            'legal entity invalid min length' => array(
                array(
                    'type' => LegalEntityLegalDetails::TYPE,
                    'fullName' => 'ООО "Рога и Копыта"',
                    'okpo' => '1234567',
                    'inn' => '123456789',
                    'kpp' => '12345678',
                    'ogrn' => '123456789012',
                ),
                400,
                array(
                    'children.legalDetails.children.okpo.errors.0'
                    =>
                    'ОКПО юридического лица должен состоять из 8 цифр',
                    'children.legalDetails.children.inn.errors.0'
                    =>
                    'ИНН юридического лица должен состоять из 10 цифр',
                    'children.legalDetails.children.kpp.errors.0'
                    =>
                    'КПП должен состоять из 9 цифр',
                    'children.legalDetails.children.ogrn.errors.0'
                    =>
                    'ОГРН должен состоять из 13 цифр',
                )
            ),
            'legal entity invalid max length' => array(
                array(
                    'type' => LegalEntityLegalDetails::TYPE,
                    'fullName' => 'ООО "Рога и Копыта"',
                    'okpo' => '123456789',
                    'inn' => '12345678901',
                    'kpp' => '1234567890',
                    'ogrn' => '12345678901201',
                ),
                400,
                array(
                    'children.legalDetails.children.okpo.errors.0'
                    =>
                    'ОКПО юридического лица должен состоять из 8 цифр',
                    'children.legalDetails.children.inn.errors.0'
                    =>
                    'ИНН юридического лица должен состоять из 10 цифр',
                    'children.legalDetails.children.kpp.errors.0'
                    =>
                    'КПП должен состоять из 9 цифр',
                    'children.legalDetails.children.ogrn.errors.0'
                    =>
                    'ОГРН должен состоять из 13 цифр',
                )
            ),
            'legal entity invalid not digits' => array(
                array(
                    'type' => LegalEntityLegalDetails::TYPE,
                    'legalAddress' => ' 650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                    'okpo' => '3771B962OO',
                    'inn' => '4z0sz44i00',
                    'kpp' => '42050i00i',
                    'ogrn' => 'ii24205008487',
                ),
                400,
                array(
                    'children.legalDetails.children.okpo.errors.0'
                    =>
                    'ОКПО юридического лица должен состоять из 8 цифр',
                    'children.legalDetails.children.inn.errors.0'
                    =>
                    'ИНН юридического лица должен состоять из 10 цифр',
                    'children.legalDetails.children.kpp.errors.0'
                    =>
                    'КПП должен состоять из 9 цифр',
                    'children.legalDetails.children.ogrn.errors.0'
                    =>
                    'ОГРН должен состоять из 13 цифр',
                )
            ),
            // Entrepreneur
            'entrepreneur all field valid' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'legalAddress' => ' 650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                    'okpo' => '0085234036',
                    'inn' => '026823668982',
                    'ogrnip' => '304024210000096',
                    'certificateNumber' => '0123456789абвгдеёжиклмноп',
                    'certificateDate' => '2010-02-24',
                ),
                200,
                array(
                    'legalDetails.type' => EntrepreneurLegalDetails::TYPE,
                    'legalDetails.fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'legalDetails.legalAddress' => '650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                    'legalDetails.okpo' => '0085234036',
                    'legalDetails.inn' => '026823668982',
                    'legalDetails.ogrnip' => '304024210000096',
                    'legalDetails.certificateNumber' => '0123456789абвгдеёжиклмноп',
                    'legalDetails.certificateDate' => '2010-02-24T00:00:00+0300',
                )
            ),
            'entrepreneur invalid length' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => str_repeat('f', 301),
                    'legalAddress' => str_repeat('l', 301),
                    'certificateNumber' => str_repeat('c', 26)
                ),
                400,
                array(
                    'children.legalDetails.children.fullName.errors.0' => 'Не более 300 символов',
                    'children.legalDetails.children.legalAddress.errors.0' => 'Не более 300 символов',
                    'children.legalDetails.children.certificateNumber.errors.0' => 'Не более 25 символов',
                )
            ),
            'entrepreneur invalid min length' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'okpo' => '123456789',
                    'inn' => '12345678901',
                    'ogrnip' => '12345678901234',
                ),
                400,
                array(
                    'children.legalDetails.children.okpo.errors.0'
                    =>
                    'ОКПО индивидуально предпринимателя должен состоять из 10 цифр',
                    'children.legalDetails.children.inn.errors.0'
                    =>
                    'ИНН индивидуально предпринимателя должен состоять из 12 цифр',
                    'children.legalDetails.children.ogrnip.errors.0'
                    =>
                    'ОГРНИП должен состоять из 15 цифр',
                )
            ),
            'entrepreneur invalid max length' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'okpo' => '12345678901',
                    'inn' => '1234567890123',
                    'ogrnip' => '1234567890123456',
                    'certificateNumber' => '0123456789абвгдеёжиклмнопр'
                ),
                400,
                array(
                    'children.legalDetails.children.okpo.errors.0'
                    =>
                    'ОКПО индивидуально предпринимателя должен состоять из 10 цифр',
                    'children.legalDetails.children.inn.errors.0'
                    =>
                    'ИНН индивидуально предпринимателя должен состоять из 12 цифр',
                    'children.legalDetails.children.ogrnip.errors.0'
                    =>
                    'ОГРНИП должен состоять из 15 цифр',
                    'children.legalDetails.children.certificateNumber.errors.0'
                    =>
                    'Не более 25 символов',
                )
            ),
            'entrepreneur invalid not digits' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'okpo' => '12345 7890',
                    'inn' => 'i234567B90i23',
                    'ogrnip' => '12EЧ5b7890i23456',
                ),
                400,
                array(
                    'children.legalDetails.children.okpo.errors.0'
                    =>
                    'ОКПО индивидуально предпринимателя должен состоять из 10 цифр',
                    'children.legalDetails.children.inn.errors.0'
                    =>
                    'ИНН индивидуально предпринимателя должен состоять из 12 цифр',
                    'children.legalDetails.children.ogrnip.errors.0'
                    =>
                    'ОГРНИП должен состоять из 15 цифр',
                )
            ),
            'entrepreneur invalid certificate date' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'certificateNumber' => '09 735632',
                    'certificateDate' => '20 февраля 2001г.'
                ),
                400,
                array(
                    'children.legalDetails.children.certificateDate.errors.0' => 'Значение недопустимо.',
                )
            ),
            'entrepreneur invalid certificate date with time' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'certificateNumber' => '09 735632',
                    'certificateDate' => '2014-02-34T00:00:00+03:00'
                ),
                400,
                array(
                    'children.legalDetails.children.certificateDate.errors.0' => 'Значение недопустимо.',
                )
            ),
        );
    }

    public function testPatchUnsetAndEmptyFields()
    {
        $user = $this->factory()->user()->createProjectUser();
        $accessToken = $this->factory()->oauth()->auth($user);

        $postData = array(
            'name' => 'ИП "Борисов"',
            'phone' => '+79023456789',
            'fax' => '(812) 234-09-88',
            'email' => 'boris.ov@list.ru',
            'director' => 'Борисов Иван Петрович',
            'chiefAccountant' => 'Борисова Надежда Ильинишна',
            'address' => 'Борисполь, ул. Ленина д.38',
            'legalDetails' => array(
                'type' => LegalEntityLegalDetails::TYPE,
                'fullName' => 'ООО "ДРИНК"',
                'legalAddress' => '650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                'okpo' => '37718962',
                'inn' => '4205244100',
                'kpp' => '420501001',
                'ogrn' => '1124205008487',
            )
        );

        $organizationId = $this->createOrganization($postData, $accessToken);

        $patchData = array(
            'name' => 'ИП "Борисов"',
            'phone' => '',
            'fax' => null,
            'director' => 'Борисов И.П.',
            'chiefAccountant' => 'Борисова Н.И.',
            'address' => 'Борисполь, ул. Ленина д.38',
            'legalDetails' => array(
                'type' => LegalEntityLegalDetails::TYPE,
                'fullName' => 'ООО "ДРИНК"',
                'legalAddress' => '650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                'okpo' => '',
                'inn' => null,
                'ogrn' => '1124205008488',
            )
        );

        $patchResponse = $this->clientJsonRequest(
            $accessToken,
            'PATCH',
            '/api/1/organizations/' . $organizationId,
            $patchData
        );

        $this->assertResponseCode(200);

        $expectedData = array(
            'id' => $organizationId,
            'name' => 'ИП "Борисов"',
            'email' => 'boris.ov@list.ru',
            'director' => 'Борисов И.П.',
            'chiefAccountant' => 'Борисова Н.И.',
            'address' => 'Борисполь, ул. Ленина д.38',
            'legalDetails' => array(
                'type' => LegalEntityLegalDetails::TYPE,
                'fullName' => 'ООО "ДРИНК"',
                'legalAddress' => '650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                'kpp' => '420501001',
                'ogrn' => '1124205008488',
            ),
            'bankAccounts' => array()
        );

        $this->assertEquals($expectedData, $patchResponse);

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/organizations/' . $organizationId
        );

        $this->assertResponseCode(200);

        $this->assertSame($patchResponse, $getResponse);
    }
}
