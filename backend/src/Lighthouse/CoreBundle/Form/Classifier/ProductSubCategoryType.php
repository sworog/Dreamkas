<?php

namespace Lighthouse\CoreBundle\Form\Classifier;

use Lighthouse\CoreBundle\Document\Classifier\CatalogManager;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.form.classifier.product_subcategory_type")
 * @DI\Tag("form.type", attributes={"alias"="product_subcategory"})
 */
class ProductSubCategoryType extends SubCategoryType
{
    /**
     * @var CatalogManager
     */
    protected $catalogManager;

    /**
     * @DI\InjectParams({
     *      "catalogManager" = @DI\Inject("lighthouse.core.document.catalog.manager")
     * })
     * @param CatalogManager $catalogManager
     */
    public function __construct(CatalogManager $catalogManager)
    {
        $this->catalogManager = $catalogManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->get('category')->setData($this->catalogManager->getDefaultCategory());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_subcategory';
    }
}
