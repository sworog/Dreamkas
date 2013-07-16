<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\Category\Category;
use Lighthouse\CoreBundle\Document\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\SubCategory\SubCategoryCollection;
use Lighthouse\CoreBundle\Document\SubCategory\SubCategoryRepository;
use Lighthouse\CoreBundle\Form\SubCategoryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

class SubCategoryController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.subcategory")
     * @var SubCategoryRepository
     */
    protected $documentRepository;

    /**
     * @return AbstractType
     */
    protected function getDocumentFormType()
    {
        return new SubCategoryType();
    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\AbstractDocument
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource=true
     * )
     */
    public function postSubcategoriesAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @param Request $request
     * @param SubCategory $subCategory
     * @return \FOS\RestBundle\View\View|\Lighthouse\CoreBundle\Document\SubCategory\SubCategory
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putSubcategoriesAction(Request $request, SubCategory $subCategory)
    {
        return $this->processForm($request, $subCategory);
    }

    /**
     * @param SubCategory $subCategory
     * @return SubCategory
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getSubcategoryAction(SubCategory $subCategory)
    {
        return $subCategory;
    }

    /**
     * @param \Lighthouse\CoreBundle\Document\Category\Category $category
     * @return SubCategoryCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getCategorySubcategoriesAction(Category $category)
    {
        $cursor = $this->getDocumentRepository()->findByCategory($category->id);
        $collection = new SubCategoryCollection($cursor);
        return $collection;
    }

    /**
     * @param SubCategory $subCategory
     * @return null
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteSubcategoriesAction(SubCategory $subCategory)
    {
        return $this->processDelete($subCategory);
    }
}
