<?php

namespace Lighthouse\CoreBundle\Integration\Excel\Export\Orders;

use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Order\Order;
use Liuggio\ExcelBundle\Factory;
use PHPExcel;
use Symfony\Component\Translation\Translator;

/**
 * @DI\Service("lighthouse.core.integration.excel.export.orders.generator")
 */
class OrderGenerator
{
    /**
     * @DI\Inject("translator")
     *
     * @var Translator
     */
    public $translator;

    /**
     * @DI\Inject("phpexcel")
     *
     * @var Factory
     */
    public $phpExcel;

    /**
     * @var Order
     */
    protected $order = null;

    /**
     * @var PHPExcel
     */
    protected $phpExcelObject = null;

    /**
     * @var \PHPExcel_Writer_Abstract
     */
    protected $writer = null;

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        $this->phpExcelObject = $this->phpExcel->createPHPExcelObject();
    }

    /**
     *
     */
    public function generate()
    {
        if (null != $this->order) {
            $this->generateHead();
            $this->generateBodyTitle();
            $this->generateBody();

            $this->writer = $this->phpExcel->createWriter($this->phpExcelObject, 'Excel2007');
        }
    }

    /**
     *
     */
    protected function generateHead()
    {
        $this->phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue(
                'A1',
                $this->translator->trans(
                    'lighthouse.order.export.generator.title.order',
                    array(
                        '{{ number }}' => $this->order->number,
                        '{{ date }}' => $this->order->createdDate->format('d.m.Y')
                    ),
                    'order'
                )
            )
            ->setCellValue(
                'A2',
                $this->translator->trans(
                    'lighthouse.order.export.generator.title.contacts',
                    array(
                        '{{ number }}' => $this->order->store->number,
                        '{{ address }}' => $this->order->store->address,
                        '{{ contacts }}' => $this->order->store->contacts,
                    ),
                    'order'
                )
            )
            ->setCellValue(
                'A3',
                $this->translator->trans(
                    'lighthouse.order.export.generator.title.supplier',
                    array('{{ supplier }}' => $this->order->supplier->name),
                    'order'
                )
            );
    }

    /**
     *
     */
    protected function generateBodyTitle()
    {
        $this->phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue(
                'A5',
                $this->translator->trans('lighthouse.order.export.generator.products.title.sku', array(), 'order')
            )
            ->setCellValue(
                'B5',
                $this->translator->trans('lighthouse.order.export.generator.products.title.barcode', array(), 'order')
            )
            ->setCellValue(
                'C5',
                $this->translator->trans('lighthouse.order.export.generator.products.title.name', array(), 'order')
            )
            ->setCellValue(
                'D5',
                $this->translator->trans('lighthouse.order.export.generator.products.title.quantity', array(), 'order')
            );

        $this->phpExcelObject->getActiveSheet()
            ->getColumnDimension('A')
            ->setWidth(15);
        $this->phpExcelObject->getActiveSheet()
            ->getColumnDimension('B')
            ->setAutoSize(true);
        // Формат для штрихкодов, что бы выглядили как обычные числа
        $this->phpExcelObject->getActiveSheet()
            ->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        $this->phpExcelObject->getActiveSheet()
            ->getColumnDimension('C')
            ->setWidth(25);
        // Формат ячеек текстовый для названия
        $this->phpExcelObject->getActiveSheet()
            ->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        // Перенос строк для названия
        $this->phpExcelObject->getActiveSheet()
            ->getStyle('C')->getAlignment()->setWrapText(true);
        $this->phpExcelObject->getActiveSheet()
            ->getColumnDimension('D')
            ->setAutoSize(true);
    }

    /**
     *
     */
    protected function generateBody()
    {
        $stringNumber = 6;
        foreach ($this->order->products as $orderProduct) {
            $this->phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $stringNumber, $orderProduct->product->sku)
                ->setCellValue('B' . $stringNumber, $orderProduct->product->barcode)
                ->setCellValue('C' . $stringNumber, $orderProduct->product->name)
                ->setCellValue('D' . $stringNumber, $orderProduct->quantity->toString());
            $this->phpExcelObject->getActiveSheet()
                ->getStyle('A' . $stringNumber . ':D' . $stringNumber)
                ->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $stringNumber++;
        }
    }

    /**
     * @return \PHPExcel_Writer_Abstract
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return 'order' . $this->order->number . '.xlsx';
    }
}
