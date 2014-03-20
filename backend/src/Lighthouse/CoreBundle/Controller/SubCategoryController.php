<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryCollection;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategoryRepository;
use Lighthouse\CoreBundle\Document\Store\Store;
use Lighthouse\CoreBundle\Exception\FlushFailedException;
use Lighthouse\CoreBundle\Form\SubCategoryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use MongoDuplicateKeyException;
use Exception;

class SubCategoryController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.classifier.subcategory")
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
     * @throws Exception
     * @throws FlushFailedException
     * @return View|SubCategory
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postSubcategoriesAction(Request $request)
    {
        try {
            return $this->processPost($request);
        } catch (FlushFailedException $e) {
            if ($e->getCause() instanceof MongoDuplicateKeyException) {
                return $this->addFormError(
                    $e->getForm(),
                    'name',
                    'lighthouse.validation.errors.subCategory.name.unique'
                );
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param Request $request
     * @param SubCategory $subCategory
     * @return View|SubCategory
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
     * @param Store $store
     * @param SubCategory $subCategory
     * @return SubCategory
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER,ACL_DEPARTMENT_MANAGER")
     * @ApiDoc(
     *      resource = true
     * )
     */
    public function getStoreSubcategoryAction(Store $store, SubCategory $subCategory)
    {
        return $subCategory;
    }

    /**
     * @param Category $category
     * @return SubCategoryCollection
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(
     *      resource = true
     * )
     */
    public function getCategorySubcategoriesAction(Category $category)
    {
        $cursor = $this->documentRepository->findByParent($category->id);
        return new SubCategoryCollection($cursor);
    }

    /**
     * @param Store $store
     * @param Category $category
     * @return SubCategoryCollection
     * @SecureParam(name="store", permissions="ACL_STORE_MANAGER,ACL_DEPARTMENT_MANAGER")
     * @ApiDoc
     */
    public function getStoreCategorySubcategoriesAction(Store $store, Category $category)
    {
        $cursor = $this->documentRepository->findByParent($category->id);
        return new SubCategoryCollection($cursor);
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
