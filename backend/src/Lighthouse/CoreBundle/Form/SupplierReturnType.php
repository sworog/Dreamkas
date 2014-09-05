<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\StockMovement\SupplierReturn\SupplierReturn;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Symfony\Component\Form\FormBuilderInterface;

class SupplierReturnType extends DocumentType
{
    /**
     * @var boolean
     */
    protected $store;

    /**
     * @param bool $store
     */
    public function __construct($store = false)
    {
        $this->store = (bool) $store;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'supplier',
                'reference',
                array(
                    'class' => Supplier::getClassName(),
                    'return_null_object_on_not_found' => true,
                    'invalid_message' => 'lighthouse.validation.errors.supplier-return.supplier.does_not_exists'
                )
            )
            ->add(
                'date',
                'datetime'
            )
            ->add(
                'paid',
                'checkbox'
            )
            ->add(
                'products',
                'collection',
                array(
                    'type' => new SupplierReturnProductType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
        ;

        if ($this->store) {
            $builder->add(
                'store',
                'reference',
                array(
                    'class' => Store::getClassName(),
                    'return_null_object_on_not_found' => true,
                )
            );
        }
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return SupplierReturn::getClassName();
    }
}
