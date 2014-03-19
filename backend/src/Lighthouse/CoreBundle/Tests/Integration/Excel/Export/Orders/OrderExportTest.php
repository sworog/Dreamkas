<?php

namespace Lighthouse\CoreBundle\Tests\Integration\Excel\Export\Orders;

use Lighthouse\CoreBundle\Document\Order\Order;
use Lighthouse\CoreBundle\Integration\Excel\Export\Orders\OrderGenerator;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Liuggio\ExcelBundle\Factory;

class OrderExportTest extends WebTestCase
{
    public function testExportOrderGeneration()
    {
        $this->setUpStoreDepartmentManager();
        $supplier = $this->factory->createSupplier();
        $product1 = $this->createProduct(array('name' => 'Кефир1Назв', 'sku' => 'Кефир1Арт', 'barcode' => '1111111'));
        $product2 = $this->createProduct(array('name' => 'Кефир2Назв', 'sku' => 'Кефир2Арт', 'barcode' => '2222222'));
        $product3 = $this->createProduct(array('name' => 'Кефир3Назв', 'sku' => 'Кефир3Арт', 'barcode' => '3333333'));

        $this->factory->flush();

        $postData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'product' => $product1,
                    'quantity' => 3.11,
                ),
                array(
                    'product' => $product2,
                    'quantity' => 2,
                ),
                array(
                    'product' => $product3,
                    'quantity' => 7.77,
                ),
            )
        );

        $accessToken = $this->auth($this->departmentManager);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $this->storeId . '/orders',
            $postData
        );

        /** @var OrderGenerator $orderExcelGenerator */
        $orderExcelGenerator = $this->getContainer()->get('lighthouse.core.integration.excel.export.orders.generator');

        /** @var Order $order */
        $order = $this->getContainer()->get('lighthouse.core.document.repository.order')->find($response['id']);

        $orderExcelGenerator->setOrder($order);
        $orderExcelGenerator->generate();
        $writer = $orderExcelGenerator->getWriter();
        $filename = '/tmp/' . __CLASS__ . '_' . __METHOD__ . '.xls';
        $writer->save($filename);

        /** @var Factory $phpExcel */
        $phpExcel = $this->getContainer()->get('phpexcel');
        $fileObject = $phpExcel->createPHPExcelObject($filename);

        /**
         * Title
         */
        $this->assertEquals(
            'Заказ №' . $order->number . ' от ' . $order->createdDate->format('d.m.Y'),
            $fileObject->getActiveSheet()
                ->getCell('A1')->getValue()
        );
        $this->assertEquals(
            'Поставщик "' . $order->supplier->name . '"',
            $fileObject->getActiveSheet()
                ->getCell('A2')->getValue()
        );


        /**
         * Table title
         */
        $this->assertEquals(
            'Код',
            $fileObject->getActiveSheet()
                ->getCell('A4')->getValue()
        );
        $this->assertEquals(
            'Штрихкод',
            $fileObject->getActiveSheet()
                ->getCell('B4')->getValue()
        );
        $this->assertEquals(
            'Наименование',
            $fileObject->getActiveSheet()
                ->getCell('C4')->getValue()
        );
        $this->assertEquals(
            'Кол-во',
            $fileObject->getActiveSheet()
                ->getCell('D4')->getValue()
        );


        /**
         * Products
         */
        // sku
        $this->assertEquals(
            'Кефир1Арт',
            $fileObject->getActiveSheet()
                ->getCell('A5')->getValue()
        );
        // barcode
        $this->assertEquals(
            '1111111',
            $fileObject->getActiveSheet()
                ->getCell('B5')->getValue()
        );
        // name
        $this->assertEquals(
            'Кефир1Назв',
            $fileObject->getActiveSheet()
                ->getCell('C5')->getValue()
        );
        // quantity
        $this->assertEquals(
            3.11,
            $fileObject->getActiveSheet()
                ->getCell('D5')->getValue()
        );


        // sku
        $this->assertEquals(
            'Кефир2Арт',
            $fileObject->getActiveSheet()
                ->getCell('A6')->getValue()
        );
        // barcode
        $this->assertEquals(
            '2222222',
            $fileObject->getActiveSheet()
                ->getCell('B6')->getValue()
        );
        // name
        $this->assertEquals(
            'Кефир2Назв',
            $fileObject->getActiveSheet()
                ->getCell('C6')->getValue()
        );
        // quantity
        $this->assertEquals(
            2,
            $fileObject->getActiveSheet()
                ->getCell('D6')->getValue()
        );


        // sku
        $this->assertEquals(
            'Кефир3Арт',
            $fileObject->getActiveSheet()
                ->getCell('A7')->getValue()
        );
        // barcode
        $this->assertEquals(
            '3333333',
            $fileObject->getActiveSheet()
                ->getCell('B7')->getValue()
        );
        // name
        $this->assertEquals(
            'Кефир3Назв',
            $fileObject->getActiveSheet()
                ->getCell('C7')->getValue()
        );
        // quantity
        $this->assertEquals(
            7.77,
            $fileObject->getActiveSheet()
                ->getCell('D7')->getValue()
        );
    }
}
