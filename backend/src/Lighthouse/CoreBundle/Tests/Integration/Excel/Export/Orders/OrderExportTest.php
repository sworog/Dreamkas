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
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory->createSupplier();
        $product1 = $this->createProduct(array(
            'name' => 'Длинное название с многим количеством слов что бы проверить перенос строк',
            'sku' => 'Кефир1Арт',
            'barcode' => '1234567891234' // 13-и знаковый штрихкод
        ));
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

        $accessToken = $this->factory->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            '/api/1/stores/' . $store->id . '/orders',
            $postData
        );

        /** @var OrderGenerator $orderExcelGenerator */
        $orderExcelGenerator = $this->getContainer()->get('lighthouse.core.integration.excel.export.orders.generator');

        /** @var Order $order */
        $order = $this->getContainer()->get('lighthouse.core.document.repository.order')->find($response['id']);

        $orderExcelGenerator->setOrder($order);
        $orderExcelGenerator->generate();
        $writer = $orderExcelGenerator->getWriter();
        $filename = '/tmp/' . __CLASS__ . '_' . __METHOD__ . '.xlsx';
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
            'Магазин №' . $order->store->number. '. ' . $order->store->address . '. ' . $order->store->contacts,
            $fileObject->getActiveSheet()
                ->getCell('A2')->getValue()
        );
        $this->assertEquals(
            'Поставщик "' . $order->supplier->name . '"',
            $fileObject->getActiveSheet()
                ->getCell('A3')->getValue()
        );


        /**
         * Table title
         */
        $this->assertExcelRow($fileObject, 5, array(
            'Код',
            'Штрихкод',
            'Наименование',
            'Кол-во'
        ));


        /**
         * Products
         */
        $this->assertExcelRow($fileObject, 6, array(
            'Кефир1Арт',    // sku
            '1234567891234',      // barcode
            'Длинное название с многим количеством слов что бы проверить перенос строк',   // name
            3.11            // quantity
        ));

        $this->assertExcelRow($fileObject, 7, array(
            'Кефир2Арт',
            '2222222',
            'Кефир2Назв',
            2
        ));

        $this->assertExcelRow($fileObject, 8, array(
            'Кефир3Арт',
            '3333333',
            'Кефир3Назв',
            7.77
        ));
    }

    /**
     * @param \PHPExcel $object
     * @param int $row
     * @param array $cells
     * @param string $startCell
     */
    protected function assertExcelRow(\PHPExcel $object, $row, array $cells, $startCell = 'A')
    {
        foreach ($cells as $cell) {
            $this->assertEquals(
                $cell,
                $object->getActiveSheet()
                    ->getCell($startCell . $row)->getValue(),
                "Cell " . $startCell . $row . " not equals"
            );

            $startCell++;
        }
    }
}
