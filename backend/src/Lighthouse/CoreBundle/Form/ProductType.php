<?php


namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Product\Type;
use Lighthouse\CoreBundle\Form\Product\UnitType;
use Lighthouse\CoreBundle\Form\Product\WeightType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
            ->add(
                'retailPricePreference',
                'choice',
                array('choices' => Product::$retailPricePreferences)
            )
            ->add(
                'subCategory',
                'reference',
                array(
                    'class' => SubCategory::getClassName(),
                    'invalid_message' => 'lighthouse.validation.errors.product.subCategory.does_not_exists'
                )
            )
            ->add(
                'rounding',
                'custom_reference',
                array('reference_provider_alias' => 'rounding')
            )
            ->add(
                'type',
                'text',
                array(
                    'mapped' => false,
                    'constraints' => array(
                        new NotBlank(),
                        new Choice(
                            array(
                                'choices' => array(Type\WeightType::TYPE, Type\UnitType::TYPE)
                            )
                        )
                    ),
                )
            );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'setTypeForm'));
    }

    /**
     * @param FormEvent $event
     * @throws \Exception
     */
    public function setTypeForm(FormEvent $event)
    {
        $form = $event->getForm();
        /* @var Product $product */
        $product = $form->getData();
        $data = $event->getData();
        $type = (isset($data['type'])) ? $data['type'] : null;

        switch ($type) {
            case Type\WeightType::TYPE:
                if (!$product->typeProperties instanceof Type\WeightType) {
                    $product->typeProperties = new Type\WeightType();
                }
                $form->add('typeProperties', new WeightType());
                break;
            case Type\UnitType::TYPE:
                if (!$product->typeProperties instanceof Type\UnitType) {
                    $product->typeProperties = new Type\UnitType();
                }
                $form->add('typeProperties', new UnitType());
                break;
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Product::getClassName(),
                'csrf_protection' => false,
                'cascade_validation' => true
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
