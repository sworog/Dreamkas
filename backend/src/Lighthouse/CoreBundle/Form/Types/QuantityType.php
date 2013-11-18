<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\QuantityTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\Service("form.type.quantity")
 * @DI\Tag("form.type", attributes={"alias"="quantity"})
 */
class QuantityType extends AbstractType
{
    /**
     * @var QuantityTransformer
     */
    protected $viewTransformer;

    /**
     * @DI\InjectParams({
     *      "viewTransformer" = @DI\Inject("lighthouse.core.data_transformer.quantity"),
     * })
     *
     * @param QuantityTransformer $viewTransformer
     */
    public function __construct(QuantityTransformer $viewTransformer)
    {
        $this->viewTransformer = $viewTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->viewTransformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'compound' => false,
                'invalid_message' => 'lighthouse.validation.errors.quantity.negative'
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'quantity';
    }
}
