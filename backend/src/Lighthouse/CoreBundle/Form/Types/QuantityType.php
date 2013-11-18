<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\FloatViewTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\DataTransformer\QuantityTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Extension\Core\Type\MoneyType as BaseMoneyType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\Service("form.type.quantity")
 * @DI\Tag("form.type", attributes={"alias"="quantity"})
 */
class QuantityType extends BaseMoneyType
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
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                'invalid_message' => 'lighthouse.validation.errors.quantity.negative'
            )
        );
    }
}
