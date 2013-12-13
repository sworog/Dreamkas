<?php

namespace Lighthouse\CoreBundle\Tests\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Job\JobManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Rounding\Nearest10;
use Lighthouse\CoreBundle\Rounding\Nearest100;
use Lighthouse\CoreBundle\Rounding\Nearest50;
use Lighthouse\CoreBundle\Rounding\Nearest99;
use Lighthouse\CoreBundle\Test\Assert;
use Lighthouse\CoreBundle\Test\WebTestCase;

class JobControllerTest extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearJobs();
    }

    public function testRecalcProductProductPriceOnRetailsChange()
    {
        $commercialAccessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse);

        $storeId1 = $this->factory->getStore('1');
        $storeId2 = $this->factory->getStore('2');
        $storeId3 = $this->factory->getStore('3');

        $productData = array(
            'sku' => 'Печенье Юбилейное',
            'purchasePrice' => 20,
            'retailMarkupMin' => 10,
            'retailMarkupMax' => 30,
            'retailPricePreference' => 'retailMarkup',
        );

        $productId = $this->createProduct($productData);

        $storeProductData1 = array(
            'retailPrice' => 22,
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId1, $productId, $storeProductData1);

        $storeProductData2 = array(
            'retailPrice' => 26,
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId2, $productId, $storeProductData2);

        $storeProductData3 = array(
            'retailPrice' => 23,
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId3, $productId, $storeProductData3);

        $updateProductData = array(
            'retailPriceMin' => 23,
            'retailPriceMax' => 24,
            'retailPricePreference' => 'retailPrice',
        ) + $productData;

        $this->updateProduct($productId, $updateProductData);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('recalc_product_price', '*.type', $getResponse);
        Assert::assertJsonPathEquals('pending', '*.status', $getResponse);

        /* @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $dm->clear();

        /* @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('lighthouse.core.job.manager');

        $jobManager->startWatchingTubes();
        $job = $jobManager->reserveJob(0);
        $jobManager->stopWatchingTubes();

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('recalc_product_price', '*.type', $getResponse);
        Assert::assertJsonPathEquals('processing', '*.status', $getResponse);

        $jobManager->startWatchingTubes();
        $jobManager->processJob($job);
        $jobManager->stopWatchingTubes();

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('recalc_product_price', '*.type', $getResponse);
        Assert::assertJsonPathEquals('success', '*.status', $getResponse);

        $this->assertStoreProduct(
            $storeId1,
            $productId,
            array(
                'retailPrice' => '23.00',
            )
        );

        $this->assertStoreProduct(
            $storeId2,
            $productId,
            array(
                'retailPrice' => '24.00',
            )
        );

        $this->assertStoreProduct(
            $storeId3,
            $productId,
            array(
                'retailPrice' => '23.00',
            )
        );
    }

    /**
     * @param string $rounding
     * @param string $retailPrice1
     * @param string $retailPrice2
     * @param string $retailPrice3
     *
     * @dataProvider recalcProductProductPriceOnMarkupChangeProvider
     */
    public function testRecalcProductProductPriceOnMarkupChange($rounding, $retailPrice1, $retailPrice2, $retailPrice3)
    {
        $commercialAccessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse);

        $storeId1 = $this->factory->getStore('1');
        $storeId2 = $this->factory->getStore('2');
        $storeId3 = $this->factory->getStore('3');

        $productData = array(
            'sku' => 'Печенье Юбилейное',
            'purchasePrice' => '20.00',
            'retailPriceMin' => '21.08',
            'retailPriceMax' => '27.74',
            'retailPricePreference' => 'retailPrice',
        );

        $productId = $this->createProduct($productData);

        $storeProductData1 = array(
            'retailPrice' => '22.13',
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId1, $productId, $storeProductData1);

        $storeProductData2 = array(
            'retailPrice' => '26.07',
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId2, $productId, $storeProductData2);

        $storeProductData3 = array(
            'retailPrice' => '23.46',
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId3, $productId, $storeProductData3);

        $updateProductData = array(
            'rounding' => $rounding
        ) + $productData;

        $this->updateProduct($productId, $updateProductData);

        $this->assertProduct($productId, array('rounding.name' => $rounding));

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('recalc_product_price', '*.type', $getResponse);
        Assert::assertJsonPathEquals('pending', '*.status', $getResponse);

        /* @var DocumentManager $dm */
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');
        $dm->clear();

        /* @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('lighthouse.core.job.manager');

        $jobManager->startWatchingTubes();
        $job = $jobManager->reserveJob(0);
        $jobManager->stopWatchingTubes();

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('recalc_product_price', '*.type', $getResponse);
        Assert::assertJsonPathEquals('processing', '*.status', $getResponse);

        $jobManager->startWatchingTubes();
        $jobManager->processJob($job);
        $jobManager->stopWatchingTubes();

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(1, '*.id', $getResponse);
        Assert::assertJsonPathEquals('recalc_product_price', '*.type', $getResponse);
        Assert::assertJsonPathEquals('success', '*.status', $getResponse);

        $this->assertStoreProduct(
            $storeId1,
            $productId,
            array(
                'roundedRetailPrice' => $retailPrice1,
            )
        );

        $this->assertStoreProduct(
            $storeId2,
            $productId,
            array(
                'roundedRetailPrice' => $retailPrice2,
            )
        );

        $this->assertStoreProduct(
            $storeId3,
            $productId,
            array(
                'roundedRetailPrice' => $retailPrice3,
            )
        );
    }

    /**
     * @return array
     */
    public function recalcProductProductPriceOnMarkupChangeProvider()
    {
        return array(
            //'nearest1'  => array(Nearest1::NAME,  '22.13', '26.07', '23.46'),
            'nearest10'  => array(Nearest10::NAME,  '22.10', '26.10', '23.50'),
            'nearest50'  => array(Nearest50::NAME,  '22.00', '26.00', '23.50'),
            'nearest100' => array(Nearest100::NAME, '22.00', '26.00', '23.00'),
            'nearest99'  => array(Nearest99::NAME,  '21.99', '25.99', '22.99'),
        );
    }

    /**
     * @param string $storeId
     * @param string $productId
     * @param array  $assertions
     * @param string $message
     */
    protected function assertStoreProduct($storeId, $productId, array $assertions, $message = '')
    {
        $storeManager = $this->getStoreManager($storeId);

        $accessToken = $this->auth($storeManager, 'password');

        $getResponse = $this->clientJsonRequest(
            $accessToken,
            'GET',
            '/api/1/stores/' . $storeId . '/products/' . $productId
        );

        $this->assertResponseCode(200);
        $this->performJsonAssertions($getResponse, $assertions);
    }

    public function testNoJobCreatedOnProductUpdateWithoutRetailsAndRounding()
    {
        $commercialAccessToken = $this->authAsRole(User::ROLE_COMMERCIAL_MANAGER);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse);

        $storeId = $this->factory->getStore('1');

        $productData = array(
            'sku' => 'Печенье Юбилейное',
            'purchasePrice' => '20.00',
            'retailPriceMin' => '21.08',
            'retailPriceMax' => '27.74',
            'retailPricePreference' => 'retailPrice',
        );

        $productId = $this->createProduct($productData);

        $storeProductData = array(
            'retailPrice' => '22.13',
            'retailPricePreference' => 'retailPrice',
        );

        $this->updateStoreProduct($storeId, $productId, $storeProductData);

        $updateProductData = array(
            'sku' => 'Печенье Юбелейное 200гр'
        ) + $productData;

        $this->updateProduct($productId, $updateProductData);

        $getResponse = $this->clientJsonRequest(
            $commercialAccessToken,
            'GET',
            '/api/1/jobs'
        );

        $this->assertResponseCode(200);

        Assert::assertJsonPathCount(0, '*.id', $getResponse);
    }
}
