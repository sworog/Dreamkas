<?php

namespace Lighthouse\CoreBundle\Tests\Meta;

use Lighthouse\CoreBundle\Meta\MetaCollection;
use Lighthouse\CoreBundle\Meta\MetaDocument;
use Lighthouse\CoreBundle\Meta\MetaGeneratorInterface;
use Lighthouse\CoreBundle\Test\WebTestCase;

class MetaContainerTest extends WebTestCase
{
    /**
     * @param MetaGeneratorInterface|MetaGeneratorInterface[] $metaGenerators
     * @return MetaCollection|MetaDocument[]
     */
    public function getProductCollectionWithMetaGenerators($metaGenerators)
    {
        $productRepository = $this->getContainer()->get('lighthouse.core.document.repository.product');
        $cursor = $productRepository->findAll();
        $collection = new MetaCollection($cursor);
        
        if (is_array($metaGenerators)) {
            foreach ($metaGenerators as $generator) {
                $collection->addMetaGenerator($generator);
            }
        } else {
            $collection->addMetaGenerator($metaGenerators);
        }

        return $collection;
    }

    public function testCollectionWithMetaData()
    {
        $this->clearMongoDb();

        $productsData = $this->getProductsData();

        /* @var $mockMetaGenerator MetaGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject */
        $mockMetaGenerator = $this->getMock(
            '\\Lighthouse\\CoreBundle\\Meta\\MetaGeneratorInterface'
        );

        $productsMeta = array(
            1 => array(
                'test_meta1' => 'test_meta_value1',
            ),
            2 => array(
                'test_meta2' => 'test_meta_value2',
            ),
            3 => array(
                'test_meta3' => 'test_meta_value3',
            ),
            4 => array(
                'test_meta4' => 'test_meta_value4',
            ),
            5 => array(
                'test_meta5' => 'test_meta_value5',
            )
        );

        $mockMetaGenerator
            ->expects($this->any())
            ->method('getMetaForElement')
            ->will(
                $this->onConsecutiveCalls(
                    $productsMeta[1],
                    $productsMeta[2],
                    $productsMeta[3],
                    $productsMeta[4],
                    $productsMeta[5],
                    $productsMeta[1],
                    $productsMeta[2],
                    $productsMeta[3],
                    $productsMeta[1],
                    $productsMeta[3],
                    $productsMeta[2],
                    $productsMeta[1]
                )
            );

        $container = $this->getProductCollectionWithMetaGenerators($mockMetaGenerator);

        /** @var MetaDocument $document */
        $i = 1;
        foreach ($container as $document) {
            $this->assertEquals($productsMeta[$i], $document->getMeta());
            foreach ($productsData[$i] as $property => $value) {
                if (!is_object($document->$property)) {
                    $this->assertEquals($value, $document->$property);
                }
            }

            $i++;
        }

        $document = $container->current();
        $this->assertEquals($productsMeta[1], $document->getMeta());
        $document = $container->next();
        $this->assertEquals($productsMeta[2], $document->getMeta());
        $document = $container->next();
        $this->assertEquals($productsMeta[3], $document->getMeta());
        $document = $container->first();
        $this->assertEquals($productsMeta[1], $document->getMeta());
        $document = $container->last();
        $this->assertEquals($productsMeta[3], $document->getMeta());
        $document = $container->remove(1);
        $this->assertEquals($productsMeta[2], $document->getMeta());
        $document = $container->get(0);
        $this->assertEquals($productsMeta[1], $document->getMeta());
    }
    
    public function testCollectionWithMultiMetaGenerators()
    {
        $this->clearMongoDb();

        $this->createProductsByNames(array('1', '2', '3'));

        $mockMetaGeneratorOne = $this->getMock(
            '\\Lighthouse\\CoreBundle\\Meta\\MetaGeneratorInterface'
        );

        $mockMetaGeneratorTwo = $this->getMock(
            '\\Lighthouse\\CoreBundle\\Meta\\MetaGeneratorInterface'
        );

        $returnMetaGeneratorOne = array(
            'test_meta1' => 'Test meta Value 1',
        );
        
        $returnMetaGeneratorTwo = array(
            'test_meta2' => 'Test meta Value 2',
        );
        
        $expectedMeta = array(
            'test_meta1' => 'Test meta Value 1',
            'test_meta2' => 'Test meta Value 2',
        );
        
        $mockMetaGeneratorOne
            ->expects($this->any())
            ->method('getMetaForElement')
            ->will($this->returnValue($returnMetaGeneratorOne));
        $mockMetaGeneratorTwo
            ->expects($this->any())
            ->method('getMetaForElement')
            ->will($this->returnValue($returnMetaGeneratorTwo));
        
        $collection = $this->getProductCollectionWithMetaGenerators(array(
                $mockMetaGeneratorOne,
                $mockMetaGeneratorTwo
            ));

        /** @var MetaDocument $document */
        foreach ($collection as $document) {
            $this->assertEquals($expectedMeta, $document->getMeta());
        }

        $document = $collection->current();
        $this->assertEquals($expectedMeta, $document->getMeta());
        $document = $collection->next();
        $this->assertEquals($expectedMeta, $document->getMeta());
        $document = $collection->next();
        $this->assertEquals($expectedMeta, $document->getMeta());
        $document = $collection->first();
        $this->assertEquals($expectedMeta, $document->getMeta());
        $document = $collection->last();
        $this->assertEquals($expectedMeta, $document->getMeta());
        $document = $collection->remove(1);
        $this->assertEquals($expectedMeta, $document->getMeta());
        $document = $collection->get(0);
        $this->assertEquals($expectedMeta, $document->getMeta());
    }

    public function getProductsData()
    {
        $groupData = array(
            'name' => 'Группа',
            'id' => $this->createGroup('Группа'),
        );

        $categoryData = array(
            'name' => 'Категория',
            'id' => $this->createCategory($groupData['id'], 'Категория'),
        );

        $subCategoryData = array(
            'name' => 'Подкатегория',
            'id' => $this->createSubCategory($categoryData['id'], 'Подкатегория'),
        );

        $productsData = array(
            1 => array(
                'name' => 'Продукт 1',
                'barcode' => '7770000000001',
                'vat' => '10',
                'vendor' => 'Вимм-Билль-Данн',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '44.11',
                'retailMarkupMin' => '40',
                'retailMarkupMax' => '60',
                'retailPricePreference' => 'retailMarkup',
                'subCategory' => $subCategoryData['id'],
            ),
            2 => array(
                'name' => 'Продукт 2 без диапозонов',
                'barcode' => '7770000000002',
                'vat' => '10',
                'vendor' => 'Петмол',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '55',
                'subCategory' => $subCategoryData['id'],
            ),
            3 => array(
                'name' => 'Продукт 3',
                'barcode' => '7770000000003',
                'vat' => '10',
                'vendor' => 'Куромать',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '66.23',
                'retailPriceMin' => '67.33',
                'retailPriceMax' => '117.54',
                'retailPricePreference' => 'retailPrice',
                'subCategory' => $subCategoryData['id'],
            ),
            4 => array(
                'name' => 'Продукт 4 без цены',
                'barcode' => '7770000000004',
                'vat' => '10',
                'vendor' => 'Гадило',
                'vendorCountry' => 'Россия',
                'purchasePrice' => '',
                'retailPriceMin' => '',
                'retailPriceMax' => '',
                'retailPricePreference' => 'retailPrice',
                'subCategory' => $subCategoryData['id'],
            ),
            5 => array(
                'name' => 'Продукт 5',
                'barcode' => '7770000000005',
                'vat' => '10',
                'vendor' => 'Пончик',
                'vendorCountry' => 'Израиль',
                'purchasePrice' => '88.3',
                'retailMarkupMin' => '40',
                'retailMarkupMax' => '60',
                'retailPricePreference' => 'retailMarkup',
                'subCategory' => $subCategoryData['id'],
            ),
        );

        for ($key = 1; $key < count($productsData) + 1; $key++) {
            $productsData[$key]['id'] = $this->createProduct($productsData[$key], $productsData[$key]['subCategory']);
        }

        return $productsData;
    }
}
