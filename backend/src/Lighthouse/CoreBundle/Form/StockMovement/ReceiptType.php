<?php

namespace Lighthouse\CoreBundle\Form\StockMovement;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Form\DocumentType;
use Symfony\Component\Form\FormBuilderInterface;

class ReceiptType extends DocumentType
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'datetime')
            ->add(
                'products',
                'collection',
                array(
                    'type' => $this->isSale() ? new SaleProductType() : new ReturnProductType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
        ;
    }

    /**
     * @return bool
     */
    protected function isSale()
    {
        return Sale::TYPE === $this->type;
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return $this->isSale() ? Sale::getClassName() : Returne::getClassName();
    }
}
