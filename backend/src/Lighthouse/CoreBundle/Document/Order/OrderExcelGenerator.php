<?php

namespace Lighthouse\CoreBundle\Document\Order;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\Translator;
use Liuggio\ExcelBundle\Factory;
use PHPExcel;
use PHPExcel_Writer_Abstract;

/**
 * @DI\Service("lighthouse.core.integration.excel.export.orders.generator")
 */
class OrderExcelGenerator
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
    public $phpExcelFactory;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var PHPExcel
     */
    protected $phpExcel;

    /**
     * @var PHPExcel_Writer_Abstract
     */
    protected $writer;

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        $this->phpExcel = $this->phpExcelFactory->createPHPExcelObject();
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

            $this->writer = $this->phpExcelFactory->createWriter($this->phpExcel, 'Excel2007');
        }
    }

    /**
     *
     */
    protected function generateHead()
    {
        $this->phpExcel->setActiveSheetIndex(0)
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
                        '{{ number }}' => $this->order->store->name,
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
        $this->phpExcel->setActiveSheetIndex(0)
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

        $this->phpExcel->getActiveSheet()
            ->getColumnDimension('A')
            ->setWidth(15);
        $this->phpExcel->getActiveSheet()
            ->getColumnDimension('B')
            ->setAutoSize(true);
        // Формат для штрихкодов, что бы выглядили как обычные числа
        $this->phpExcel->getActiveSheet()
            ->getStyle('B')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        $this->phpExcel->getActiveSheet()
            ->getColumnDimension('C')
            ->setWidth(25);
        // Формат ячеек текстовый для названия
        $this->phpExcel->getActiveSheet()
            ->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        // Перенос строк для названия
        $this->phpExcel->getActiveSheet()
            ->getStyle('C')->getAlignment()->setWrapText(true);
        $this->phpExcel->getActiveSheet()
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
            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $stringNumber, $orderProduct->product->sku)
                ->setCellValue('B' . $stringNumber, $orderProduct->product->barcode)
                ->setCellValue('C' . $stringNumber, $orderProduct->product->name)
                ->setCellValue('D' . $stringNumber, $orderProduct->quantity->toString());
            $this->phpExcel->getActiveSheet()
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
