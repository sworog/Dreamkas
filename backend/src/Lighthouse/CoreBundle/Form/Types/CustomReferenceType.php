<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Lighthouse\CoreBundle\DataTransformer\ReferenceTransformer;
use Lighthouse\CoreBundle\MongoDB\Reference\ReferenceManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\Service("lighthouse.core.form.type.custom_reference")
 * @DI\Tag("form.type", attributes={"alias"="custom_reference"})
 */
class CustomReferenceType extends AbstractType
{
    /**
     * @var ReferenceManager
     */
    protected $referenceManager;

    /**
     * @DI\InjectParams({
     *     "referenceManager" = @DI\Inject("lighthouse.core.mongodb.reference.manager")
     * })
     * @param ReferenceManager $referenceManager
     */
    public function __construct(ReferenceManager $referenceManager)
    {
        $this->referenceManager = $referenceManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->getViewTransformer($options));
    }

    /**
     * @param array $options
     * @return ReferenceTransformer
     */
    protected function getViewTransformer(array $options)
    {
        $referenceProvider = $this->referenceManager->getReferenceProvider($options['reference_provider_alias']);
        return new ReferenceTransformer($referenceProvider);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(
            array('reference_provider_alias')
        );

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
        return 'custom_reference';
    }
}
