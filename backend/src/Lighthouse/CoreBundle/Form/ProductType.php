<?php


namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Type\UnitType as Unit;
use Lighthouse\CoreBundle\Document\Product\Type\WeightType as Weight;
use Lighthouse\CoreBundle\Form\Product\UnitType;
use Lighthouse\CoreBundle\Form\Product\WeightType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('vat', 'text')
            ->add('purchasePrice', 'money')
            ->add('barcode', 'text')
            ->add('vendorCountry', 'text')
            ->add('vendor', 'text')
            ->add('info', 'text')
            ->add('retailPriceMin', 'money')
            ->add('retailPriceMax', 'money')
            ->add('retailMarkupMin', 'markup')
            ->add('retailMarkupMax', 'markup')
            ->add('retailPricePreference', 'choice', array('choices' => Product::$retailPricePreferences))
            ->add(
                'subCategory',
                'reference',
                array(
                    'class' => SubCategory::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.product.subCategory.does_not_exists'
                )
            )
            ->add('rounding', 'custom_reference', array('reference_provider_alias' => 'rounding'))
            ->add('type', 'text', array('mapped' => false));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'setTypeForm'));
    }

    /**
     * @param FormEvent $event
     * @throws \Exception
     */
    public function setTypeForm(FormEvent $event)
    {
        $form = $event->getForm();
        $type = $form->get('type')->getData();
        switch ($type) {
            case Unit::TYPE:
                $form->add('typeProperties', new UnitType());
                break;
            case Weight::TYPE:
                $form->add('typeProperties', new WeightType());
                break;
            default:
                $form->get('type')->addError(new FormError('invalid type'));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Product::getClassName(),
                'csrf_protection' => false
            )
        );
    }

    public function getName()
    {
        return '';
    }
}
