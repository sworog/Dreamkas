<?php

namespace Lighthouse\CoreBundle\Form\StockMovement\Returne;

use Lighthouse\CoreBundle\Document\StockMovement\Returne\Returne;
use Lighthouse\CoreBundle\Document\StockMovement\Sale\Sale;
use Lighthouse\CoreBundle\Form\DocumentType;
use Lighthouse\CoreBundle\Form\StockMovement\Returne\ReturnProductType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReturnType extends DocumentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'datetime')
            ->add(
                'sale',
                'reference',
                array(
                    'class' => Sale::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.return.sale.does_not_exists'
                )
            )
            ->add(
                'products',
                'collection',
                array(
                    'type' => new ReturnProductType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                )
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                'cascade_validation' => true
            )
        );
    }

    /**
     * @return string
     */
    protected function getDataClass()
    {
        return Returne::getClassName();
    }
}
