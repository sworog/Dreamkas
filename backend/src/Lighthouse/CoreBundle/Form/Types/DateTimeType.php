<?php

namespace Lighthouse\CoreBundle\Form\Types;

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
