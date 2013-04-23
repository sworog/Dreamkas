<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyViewTransformer;
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
     * @var MoneyViewTransformer
     */
    protected $viewTransformer;

    /**
     * @DI\InjectParams({
     *      "viewTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_view"),
     * })
     *
     * @param MoneyViewTransformer $viewTransformer
     */
    public function __construct(MoneyViewTransformer $viewTransformer)
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

    public function getParent()
    {
        return 'field';
    }
}
