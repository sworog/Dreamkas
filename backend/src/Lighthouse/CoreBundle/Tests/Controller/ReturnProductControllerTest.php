<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Lighthouse\CoreBundle\Integration\Set10\ImportCheques\ChequesImporter;
use Lighthouse\CoreBundle\Integration\Set10\ImportCheques\ImportChequesXmlParser;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\TestOutput;
use Lighthouse\CoreBundle\Test\WebTestCase;

class ReturnProductControllerTest extends WebTestCase
{
    /**
     * @param string $xmlFile
     * @return ChequesImporter
     */
    protected function importCheques($xmlFile)
    {
        $importer = $this->getContainer()->get('lighthouse.core.integration.set10.import_cheques.importer');
        $xmlFilePath = $this->getFixtureFilePath($xmlFile);
        $xmlParser = new ImportChequesXmlParser($xmlFilePath);
        $output = new TestOutput();
        $importer->import($xmlParser, $output);
        return $importer;
    }

    public function testGetProductRestitutionProducts()
    {
        $storeId = $this->createStore('197');
        $departmentManager = $this->factory->getDepartmentManager($storeId);

        $products = $this->createProductsBySku(array('1', '2', '3', '4'));

        $importer = $this->importCheques('Integration/Set10/ImportCheques/purchases-with-restitution.xml');
        $this->assertCount(0, $importer->getErrors(), 'Failed asserting no import errors');

        $accessToken = $this->factory->auth($departmentManager);

        // Check product '1' returns
        $getResponse1 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $products['1'] . '/returnProducts'
        );

        $this->assertResponseCode(200);


        Assert::assertJsonPathCount(1, '*.id', $getResponse1);
        Assert::assertNotJsonPathEquals($products['2'], '*.product.id', $getResponse1);
        Assert::assertNotJsonPathEquals($products['3'], '*.product.id', $getResponse1);
        Assert::assertNotJsonPathEquals($products['4'], '*.product.id', $getResponse1);
        Assert::assertJsonPathEquals($products['1'], '0.product.id', $getResponse1);
        Assert::assertJsonPathEquals('1', '0.product.sku', $getResponse1);
        Assert::assertJsonPathEquals($storeId, '0.return.store.id', $getResponse1);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.return.createdDate', $getResponse1);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.createdDate', $getResponse1);
        Assert::assertJsonPathEquals('513.00', '0.sellingPrice', $getResponse1);
        Assert::assertJsonPathEquals('1', '0.quantity', $getResponse1);
        Assert::assertJsonPathEquals('513.00', '0.totalSellingPrice', $getResponse1);

        Assert::assertNotJsonHasPath('*.store', $getResponse1);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse1);

        // Check product '2' returns
        $getResponse2 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $products['2'] . '/returnProducts'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse2);

        // Check product '3' returns
        $getResponse3 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $products['3'] . '/returnProducts'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse3);
        Assert::assertNotJsonPathEquals($products['1'], '*.product.id', $getResponse3);
        Assert::assertNotJsonPathEquals($products['2'], '*.product.id', $getResponse3);
        Assert::assertNotJsonPathEquals($products['4'], '*.product.id', $getResponse3);
        Assert::assertJsonPathEquals($products['3'], '0.product.id', $getResponse3);
        Assert::assertJsonPathEquals($storeId, '0.return.store.id', $getResponse3);
        // check it same return as product '1'
        Assert::assertJsonPathEquals($getResponse1[0]['return']['id'], '0.return.id', $getResponse3);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.return.createdDate', $getResponse3);
        Assert::assertJsonPathEquals('2012-05-12T19:31:44+0400', '0.createdDate', $getResponse3);
        Assert::assertJsonPathEquals('180.00', '0.sellingPrice', $getResponse3);
        Assert::assertJsonPathEquals('25', '0.quantity', $getResponse3);
        Assert::assertJsonPathEquals('4500.00', '0.totalSellingPrice', $getResponse3);

        Assert::assertNotJsonHasPath('*.store', $getResponse3);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse3);

        // Check product '4' returns
        $getResponse4 = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $products['4'] . '/returnProducts'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(2, '*.id', $getResponse4);
        Assert::assertNotJsonPathEquals($products['1'], '*.product.id', $getResponse4);
        Assert::assertNotJsonPathEquals($products['2'], '*.product.id', $getResponse4);
        Assert::assertNotJsonPathEquals($products['3'], '*.product.id', $getResponse4);
        Assert::assertJsonPathEquals($products['4'], '*.product.id', $getResponse4, 2);

        Assert::assertJsonPathEquals($storeId, '0.return.store.id', $getResponse4);
        Assert::assertJsonPathEquals('2012-05-12T19:47:33+0400', '0.return.createdDate', $getResponse4);
        Assert::assertJsonPathEquals('2012-05-12T19:46:32+0400', '1.return.createdDate', $getResponse4);

        Assert::assertJsonPathEquals('38.00', '0.sellingPrice', $getResponse4);
        Assert::assertJsonPathEquals('1', '0.quantity', $getResponse4);
        Assert::assertJsonPathEquals('38.00', '0.totalSellingPrice', $getResponse4);

        Assert::assertJsonPathEquals('36.00', '1.sellingPrice', $getResponse4);
        Assert::assertJsonPathEquals('1', '1.quantity', $getResponse4);
        Assert::assertJsonPathEquals('36.00', '1.totalSellingPrice', $getResponse4);

        Assert::assertNotJsonHasPath('*.store', $getResponse4);
        Assert::assertNotJsonHasPath('*.originalProduct', $getResponse4);
    }
}
