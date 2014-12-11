<?php

namespace Lighthouse\CoreBundle\Tests\Document\Order;

use Lighthouse\CoreBundle\Document\Order\OrderExcelGenerator;
use Lighthouse\CoreBundle\Document\Order\OrderRepository;
use Lighthouse\CoreBundle\Test\WebTestCase;
use Liuggio\ExcelBundle\Factory;

class OrderExportTest extends WebTestCase
{
    public function testExportOrderGeneration()
    {
        $store = $this->factory()->store()->getStore();
        $supplier = $this->factory()->supplier()->getSupplier();
        $product1 = $this->factory()->catalog()->createProduct(array(
            'name' => 'Длинное название с многим количеством слов что бы проверить перенос строк',
            'barcode' => '1234567891234' // 13-и знаковый штрихкод
        ));
        $product2 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир2Назв', 'barcode' => '2222222'));
        $product3 = $this->factory()->catalog()->createProduct(array('name' => 'Кефир3Назв', 'barcode' => '3333333'));

        $this->factory()->flush();

        $postData = array(
            'supplier' => $supplier->id,
            'products' => array(
                array(
                    'product' => $product1->id,
                    'quantity' => 3.11,
                ),
                array(
                    'product' => $product2->id,
                    'quantity' => 2,
                ),
                array(
                    'product' => $product3->id,
                    'quantity' => 7.77,
                ),
            )
        );

        $accessToken = $this->factory()->oauth()->authAsDepartmentManager($store->id);
        $response = $this->clientJsonRequest(
            $accessToken,
            'POST',
            "/api/1/stores/{$store->id}/orders",
            $postData
        );

        $order = $this->getOrderRepository()->find($response['id']);

        $this->getOrderExcelGenerator()->setOrder($order);
        $this->getOrderExcelGenerator()->generate();

        $filename = '/tmp/' . uniqid('order-') . '.xlsx';
        $this->getOrderExcelGenerator()->getWriter()->save($filename);

        $fileObject = $this->getPhpExcelFactory()->createPHPExcelObject($filename);

        /**
         * Title
         */
        $this->assertEquals(
            'Заказ №' . $order->number . ' от ' . $order->createdDate->format('d.m.Y'),
            $fileObject->getActiveSheet()->getCell('A1')->getValue()
        );
        $this->assertEquals(
            'Магазин №' . $order->store->name. '. ' . $order->store->address . '. ' . $order->store->contacts,
            $fileObject->getActiveSheet()->getCell('A2')->getValue()
        );
        $this->assertEquals(
            'Поставщик "' . $order->supplier->name . '"',
            $fileObject->getActiveSheet()->getCell('A3')->getValue()
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
            '10001',    // sku
            '1234567891234',      // barcode
            'Длинное название с многим количеством слов что бы проверить перенос строк',   // name
            3.11            // quantity
        ));

        $this->assertExcelRow($fileObject, 7, array(
            '10002',
            '2222222',
            'Кефир2Назв',
            2
        ));

        $this->assertExcelRow($fileObject, 8, array(
            '10003',
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
                $object->getActiveSheet()->getCell($startCell . $row)->getValue(),
                sprintf('Cell %s%s not equals', $startCell, $row)
            );

            $startCell++;
        }
    }

    /**
     * @return OrderExcelGenerator
     */
    protected function getOrderExcelGenerator()
    {
        return $this->getContainer()->get('lighthouse.core.integration.excel.export.orders.generator');
    }

    /**
     * @return OrderRepository
     */
    protected function getOrderRepository()
    {
        return $this->getContainer()->get('lighthouse.core.document.repository.order');
    }

    /**
     * @return Factory
     */
    protected function getPhpExcelFactory()
    {
        return $this->getContainer()->get('phpexcel');
    }
}
