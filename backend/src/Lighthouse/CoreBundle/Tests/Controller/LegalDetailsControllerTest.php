<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class LegalDetailsControllerTest extends WebTestCase
{
    /**
     * @param string $name
     * @param object $accessToken
     * @return string
     */
    protected function createOrganization($name, $accessToken)
    {
        $postData = array(
            'name' => $name
        );

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
     * @dataProvider postActionProvider
     * @param array $postData
     * @param int $expectedCode
     * @param array $assertions
     */
    public function testPostAction(array $postData, $expectedCode, array $assertions = array())
    {
        $user = $this->factory()->user()->createProjectUser();
        $accessToken = $this->factory()->oauth()->auth($user);

        $organizationId = $this->createOrganization('Контора', $accessToken);

        $postData = $postData + array(
            'type' => LegalEntityLegalDetails::TYPE,
            'fullName' => '',
            'legalAddress' => '',
            'okpo' => '',
        );

        $postResponse = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/organizations/' . $organizationId . '/legalDetails',
            $postData
        );

        $this->assertResponseCode($expectedCode);

        $this->performJsonAssertions($postResponse, $assertions);
    }

    /**
     * @return array
     */
    public function postActionProvider()
    {
        return array(
            'legal entity' => array(
                array(
                    'fullName' => 'ООО "Рога и Копыта"',
                    'type' => LegalEntityLegalDetails::TYPE
                ),
                201,
                array(
                    'fullName' => 'ООО "Рога и Копыта"',
                    'type' => LegalEntityLegalDetails::TYPE
                )
            ),
            'entrepreneur entity' => array(
                array(
                    'fullName' => 'ИП Рогаикопытов',
                    'type' => EntrepreneurLegalDetails::TYPE
                ),
                201,
                array(
                    'fullName' => 'ИП Рогаикопытов',
                    'type' => EntrepreneurLegalDetails::TYPE
                )
            ),
            'invalid type' => array(
                array(
                    'fullName' => 'ООО "Рога и Копыта"',
                    'type' => 'ltd'
                ),
                400,
                array(
                    'children.type.errors.0' => 'Выбранное Вами значение недопустимо.'
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
                201,
                array(
                    'type' => LegalEntityLegalDetails::TYPE,
                    'fullName' => 'ООО "ДРИНК"',
                    'legalAddress' => '650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                    'okpo' => '37718962',
                    'inn' => '4205244100',
                    'kpp' => '420501001',
                    'ogrn' => '1124205008487',
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
                    'children.fullName.errors.0' => 'Не более 300 символов',
                    'children.legalAddress.errors.0' => 'Не более 300 символов',
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
                    'children.okpo.errors.0' => 'ОКПО юридического лица должен состоять из 8 цифр',
                    'children.inn.errors.0' => 'ИНН юридического лица должен состоять из 10 цифр',
                    'children.kpp.errors.0' => 'КПП должен состоять из 9 цифр',
                    'children.ogrn.errors.0' => 'ОГРН должен состоять из 13 цифр',
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
                    'children.okpo.errors.0' => 'ОКПО юридического лица должен состоять из 8 цифр',
                    'children.inn.errors.0' => 'ИНН юридического лица должен состоять из 10 цифр',
                    'children.kpp.errors.0' => 'КПП должен состоять из 9 цифр',
                    'children.ogrn.errors.0' => 'ОГРН должен состоять из 13 цифр',
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
                    'children.okpo.errors.0' => 'ОКПО юридического лица должен состоять из 8 цифр',
                    'children.inn.errors.0' => 'ИНН юридического лица должен состоять из 10 цифр',
                    'children.kpp.errors.0' => 'КПП должен состоять из 9 цифр',
                    'children.ogrn.errors.0' => 'ОГРН должен состоять из 13 цифр',
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
                    'certificateDate' => '24 февраля 2010г.',
                ),
                201,
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'legalAddress' => '650055, КЕМЕРОВСКАЯ обл, КЕМЕРОВО г, СИБИРЯКОВ-ГВАРДЕЙЦЕВ ул, 1',
                    'okpo' => '0085234036',
                    'inn' => '026823668982',
                    'ogrnip' => '304024210000096',
                    'certificateNumber' => '0123456789абвгдеёжиклмноп',
                    'certificateDate' => '24 февраля 2010г.',
                )
            ),
            'entrepreneur invalid length' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => str_repeat('f', 301),
                    'legalAddress' => str_repeat('l', 301),
                    'certificateDate' => str_repeat('0', 101)
                ),
                400,
                array(
                    'children.fullName.errors.0' => 'Не более 300 символов',
                    'children.legalAddress.errors.0' => 'Не более 300 символов',
                    'children.certificateDate.errors.0' => 'Не более 100 символов',
                )
            ),
            'entrepreneur invalid min length' => array(
                array(
                    'type' => EntrepreneurLegalDetails::TYPE,
                    'fullName' => 'ШАТАЛИН ВЛАДИМИР ПЕТРОВИЧ',
                    'okpo' => '123456789',
                    'inn' => '12345678901',
                    'ogrnip' => '12345678901234',
                    'certificateNumber' => '0123456789абвгдеёжиклмно'
                ),
                400,
                array(
                    'children.okpo.errors.0' => 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр',
                    'children.inn.errors.0' => 'ИНН индивидуально предпринимателя должен состоять из 12 цифр',
                    'children.ogrnip.errors.0' => 'ОГРНИП должен состоять из 15 цифр',
                    'children.certificateNumber.errors.0' => 'Номер свидетельства должен состоять из 25 символов',
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
                    'children.okpo.errors.0' => 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр',
                    'children.inn.errors.0' => 'ИНН индивидуально предпринимателя должен состоять из 12 цифр',
                    'children.ogrnip.errors.0' => 'ОГРНИП должен состоять из 15 цифр',
                    'children.certificateNumber.errors.0' => 'Номер свидетельства должен состоять из 25 символов',
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
                    'children.okpo.errors.0' => 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр',
                    'children.inn.errors.0' => 'ИНН индивидуально предпринимателя должен состоять из 12 цифр',
                    'children.ogrnip.errors.0' => 'ОГРНИП должен состоять из 15 цифр',
                )
            ),
        );
    }
}
