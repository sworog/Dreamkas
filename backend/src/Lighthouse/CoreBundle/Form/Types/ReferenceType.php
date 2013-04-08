<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\DataTransformer\DocumentToIdTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyModelTransformer;
use Lighthouse\CoreBundle\DataTransformer\MoneyViewTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\Service("lighthouse.core.form.type.reference")
 * @DI\Tag("form.type", attributes={"alias"="reference"})
 */
class ReferenceType extends AbstractType
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $odm;

    /**
     * @DI\InjectParams({
     *     "odm"=@DI\Inject("doctrine_mongodb.odm.document_manager")
     * })
     * @param DocumentManager $odm
     */
    public function __construct(DocumentManager $odm)
    {
        $this->odm = $odm;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $viewTransformer = new DocumentToIdTransformer($this->odm, $options['class']);
        $builder->addViewTransformer($viewTransformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(
            array('class')
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
        return 'reference';
    }
}
