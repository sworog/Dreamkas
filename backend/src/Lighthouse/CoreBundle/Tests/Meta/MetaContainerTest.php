<?php

namespace Lighthouse\CoreBundle\Tests\Meta;

use Lighthouse\CoreBundle\Document\Meta\MetaGeneratorInterface;
use Lighthouse\CoreBundle\Document\Product\ProductCollection;
use Lighthouse\CoreBundle\Test\WebTestCase;

class MetaContainerTest extends WebTestCase
{
    /**
     * @param MetaGeneratorInterface|MetaGeneratorInterface[] $metaGenerators
     * @return ProductCollection
     */
    public function getProductCollectionWithMetaGenerators($metaGenerators)
    {
        $productRepository = $this->getContainer()->get('lighthouse.core.document.repository.product');
        $cursor = $productRepository->findAll();
        $collection = new ProductCollection($cursor);
        
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

        $products = $this->createProductsBySku(array('1', '2', '3'));

        $mockMetaGenerator = $this->getMock(
            '\Lighthouse\CoreBundle\Document\Meta\MetaGeneratorInterface'
        );

        $productsMeta = array(
            array(
                'test_meta' => 'test_meta_value',
            ),
            array(
                'test_meta2' => 'test_meta_value2',
            )
            ,array(
                'test_meta3' => 'test_meta_value3',
            )
        );

        $mockMetaGenerator
            ->expects($this->any())
            ->method('getMetaForElement')
            ->will(
                $this->onConsecutiveCalls(
                    $productsMeta[0],
                    $productsMeta[1],
                    $productsMeta[2],
                    $productsMeta[0],
                    $productsMeta[1],
                    $productsMeta[2],
                    $productsMeta[0],
                    $productsMeta[2],
                    $productsMeta[1],
                    $productsMeta[0]
                )
            );

        $container = $this->getProductCollectionWithMetaGenerators($mockMetaGenerator);

        $i = 0;
        foreach ($container as $document) {
            $this->assertEquals($productsMeta[$i++], $document->meta);
        }

        $document = $container->current();
        $this->assertEquals($productsMeta[0], $document->meta);
        $document = $container->next();
        $this->assertEquals($productsMeta[1], $document->meta);
        $document = $container->next();
        $this->assertEquals($productsMeta[2], $document->meta);
        $document = $container->first();
        $this->assertEquals($productsMeta[0], $document->meta);
        $document = $container->last();
        $this->assertEquals($productsMeta[2], $document->meta);
        $document = $container->remove(1);
        $this->assertEquals($productsMeta[1], $document->meta);
        $document = $container->get(0);
        $this->assertEquals($productsMeta[0], $document->meta);
    }
    
    public function testCollectionWithMultiMetaGenerators()
    {
        $this->clearMongoDb();

        $products = $this->createProductsBySku(array('1', '2', '3'));

        $mockMetaGeneratorOne = $this->getMock(
            '\Lighthouse\CoreBundle\Document\Meta\MetaGeneratorInterface'
        );

        $mockMetaGeneratorTwo = $this->getMock(
            '\Lighthouse\CoreBundle\Document\Meta\MetaGeneratorInterface'
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

        foreach ($collection as $document) {
            $this->assertEquals($expectedMeta, $document->meta);
        }

        $document = $collection->current();
        $this->assertEquals($expectedMeta, $document->meta);
        $document = $collection->next();
        $this->assertEquals($expectedMeta, $document->meta);
        $document = $collection->next();
        $this->assertEquals($expectedMeta, $document->meta);
        $document = $collection->first();
        $this->assertEquals($expectedMeta, $document->meta);
        $document = $collection->last();
        $this->assertEquals($expectedMeta, $document->meta);
        $document = $collection->remove(1);
        $this->assertEquals($expectedMeta, $document->meta);
        $document = $collection->get(0);
        $this->assertEquals($expectedMeta, $document->meta);
    }
}
