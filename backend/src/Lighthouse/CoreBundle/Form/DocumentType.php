<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\ClassNameable;
use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class DocumentType extends BaseAbstractType implements ClassNameable
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->getDataClass(),
                'csrf_protection' => false
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
        return '';
    }

    /**
     * @return string
     */
    abstract protected function getDataClass();

    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }
}
