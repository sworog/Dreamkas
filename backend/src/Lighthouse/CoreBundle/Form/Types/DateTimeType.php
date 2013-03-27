<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\DateTimeToRfc3339Transformer as LighthouseDateTimeToRfc3339Transformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToRfc3339Transformer;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as BaseDateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\Service("form.type.datetime")
 * @DI\Tag("form.type", attributes={"alias"="datetime"})
 */
class DateTimeType extends BaseDateTimeType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->replaceDateTimeToRfc3339Transformer($builder, $options);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function replaceDateTimeToRfc3339Transformer(FormBuilderInterface $builder, array $options)
    {
        $transformers = $builder->getViewTransformers();
        $builder->resetViewTransformers();
        foreach ($transformers as $transformer) {
            if ($transformer instanceof DateTimeToRfc3339Transformer) {
                $transformer = new LighthouseDateTimeToRfc3339Transformer(
                    $options['model_timezone'],
                    $options['view_timezone']
                );
            }
            $builder->addViewTransformer($transformer);
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                'date_format' => self::HTML5_FORMAT,
                'widget' => 'single_text',
                'invalid_message' => 'lighthouse.validation.errors.datetime.invalid_value',
            )
        );
    }
}
