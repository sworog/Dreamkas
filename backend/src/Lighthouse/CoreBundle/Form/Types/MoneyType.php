<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\FloatViewTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Extension\Core\Type\MoneyType as BaseMoneyType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\Service("form.type.money")
 * @DI\Tag("form.type", attributes={"alias"="money"})
 */
class MoneyType extends BaseMoneyType
{
    /**
     * @var FloatViewTransformer
     */
    protected $viewTransformer;

    /**
     * @var MoneyModelTransformer
     */
    protected $modelTransformer;

    /**
     * @DI\InjectParams({
     *      "viewTransformer" = @DI\Inject("lighthouse.core.data_transformer.float_view"),
     *      "modelTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model")
     * })
     *
     * @param FloatViewTransformer $viewTransformer
     * @param MoneyModelTransformer $modelTransformer
     */
    public function __construct(FloatViewTransformer $viewTransformer, MoneyModelTransformer $modelTransformer)
    {
        $this->viewTransformer = $viewTransformer;
        $this->modelTransformer = $modelTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addViewTransformer($this->viewTransformer)
            ->addModelTransformer($this->modelTransformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                'invalid_message' => 'lighthouse.validation.errors.money.invalid'
            )
        );
    }
}
