<?php

namespace Lighthouse\CoreBundle\Form\Types;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\DataTransformer\DocumentToIdTransformer;
use Lighthouse\CoreBundle\Versionable\VersionFactory;
use Lighthouse\CoreBundle\Versionable\VersionRepository;
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
     * @var DocumentManager
     */
    protected $odm;

    /**
     * @var VersionFactory
     */
    protected $versionFactory;

    /**
     * @DI\InjectParams({
     *     "odm" = @DI\Inject("doctrine_mongodb.odm.document_manager"),
     *     "versionFactory" = @DI\Inject("lighthouse.core.versionable.factory")
     * })
     * @param DocumentManager $odm
     * @param VersionFactory $versionFactory
     */
    public function __construct(DocumentManager $odm, VersionFactory $versionFactory)
    {
        $this->odm = $odm;
        $this->versionFactory = $versionFactory;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(
            $this->createViewTransformer(
                $options['class'],
                $options['return_null_object_on_not_found']
            )
        );
    }

    /**
     * @param string $class
     * @param bool $returnNullObjectOnNotFound
     * @return DocumentToIdTransformer
     */
    protected function createViewTransformer($class, $returnNullObjectOnNotFound = false)
    {
        $repository = $this->odm->getRepository($class);
        if ($repository instanceof VersionRepository) {
            $repository->setVersionFactory($this->versionFactory);
        }
        return new DocumentToIdTransformer($repository, $returnNullObjectOnNotFound);
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
                'return_null_object_on_not_found' => false
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
