<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyViewTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Extension\Core\Type\MoneyType as BaseMoneyType;

/**
 * @DI\Service("form.type.money")
 * @DI\Tag("form.type", attributes={"alias"="money"})
 */
class MoneyType extends BaseMoneyType
{
    /**
     * @var MoneyViewTransformer
     */
    protected $viewTransformer;

    /**
     * @var MoneyModelTransformer
     */
    protected $modelTransformer;

    /**
     * @DI\InjectParams({
     *      "viewTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_view"),
     *      "modelTransformer" = @DI\Inject("lighthouse.core.data_transformer.money_model")
     * })
     *
     * @param MoneyViewTransformer $viewTransformer
     * @param MoneyModelTransformer $modelTransformer
     */
    public function __construct(MoneyViewTransformer $viewTransformer, MoneyModelTransformer $modelTransformer)
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
}
