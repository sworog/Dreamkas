<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\FloatModelTransformer;
use Lighthouse\CoreBundle\DataTransformer\FloatViewTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Extension\Core\Type\MoneyType as BaseMoneyType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\Service("form.type.markup")
 * @DI\Tag("form.type", attributes={"alias"="markup"})
 */
class MarkupType extends AbstractType
{
    /**
     * @var FloatViewTransformer
     */
    protected $floatViewTransformer;

    /**
     * @var FloatModelTransformer
     */
    protected $floatModelTransformer;

    /**
     * @DI\InjectParams({
     *      "floatViewTransformer" = @DI\Inject("lighthouse.core.data_transformer.float_view"),
     *      "floatModelTransformer" = @DI\Inject("lighthouse.core.data_transformer.float_model"),
     * })
     *
     * @param FloatViewTransformer $floatViewTransformer
     * @param FloatModelTransformer $floatModelTransformer
     */
    public function __construct(
        FloatViewTransformer $floatViewTransformer,
        FloatModelTransformer $floatModelTransformer
    ) {
        $this->floatViewTransformer = $floatViewTransformer;
        $this->floatModelTransformer = $floatModelTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->floatViewTransformer);
        $builder->addModelTransformer($this->floatModelTransformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'compound' => false,
                'invalid_message' => 'lighthouse.validation.errors.float.invalid_message'
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
        return 'markup';
    }
}
