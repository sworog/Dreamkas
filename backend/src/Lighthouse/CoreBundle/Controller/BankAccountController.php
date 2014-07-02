<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\BankAccount\BankAccount;
use Lighthouse\CoreBundle\Document\BankAccount\BankAccountRepository;
use Lighthouse\CoreBundle\Document\Organization\Organization;
use Lighthouse\CoreBundle\Document\Organization\Organizationable;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;
use Lighthouse\CoreBundle\Form\BankAccountType;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BankAccountController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.bank_account")
     * @var BankAccountRepository
     */
    protected $documentRepository;

    /**
     * @return BankAccountType
     */
    protected function getDocumentFormType()
    {
        return new BankAccountType();
    }

    /**
     * @param Organization $organization
     * @param BankAccount $bankAccount
     * @return BankAccount
     *
     * @Rest\Route("organizations/{organization}/bankAccounts/{bankAccount}")
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function getOrganizationBankAccountAction(
        Organization $organization,
        BankAccount $bankAccount
    ) {
        $this->checkBankAccountOrganization($organization, $bankAccount);
        return $bankAccount;
    }

    /**
     * @param Organization $organization
     * @return Cursor|BankAccount[]
     *
     * @Rest\Route("organizations/{organization}/bankAccounts")
     * @Rest\View(statusCode=200)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function getOrganizationBankAccountsAction(Organization $organization)
    {
        return $this->documentRepository->findByOrganization($organization);
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return BankAccount|FormInterface
     *
     * @Rest\Route("organizations/{organization}/bankAccounts")
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postOrganizationBankAccountAction(Request $request, Organization $organization)
    {
        $bankAccount = $this->documentRepository->createNew();
        $bankAccount->organization = $organization;
        return $this->processForm($request, $bankAccount);
    }

    /**
     * @param Request $request
     * @param Supplier $supplier
     * @return BankAccount|FormInterface
     *
     * @Rest\Route("suppliers/{supplier}/bankAccounts")
     * @Rest\View(statusCode=201)
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function postSupplierBankAccountAction(Request $request, Supplier $supplier)
    {
        $bankAccount = $this->documentRepository->createNew();
        $bankAccount->organization = $supplier;
        return $this->processForm($request, $bankAccount);
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @param BankAccount $bankAccount
     * @return BankAccount|FormInterface
     *
     * @Rest\Route("organizations/{organization}/bankAccounts/{bankAccount}")
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function putOrganizationBankAccountAction(
        Request $request,
        Organization $organization,
        BankAccount $bankAccount
    ) {
        $this->checkBankAccountOrganization($organization, $bankAccount);
        return $this->processForm($request, $bankAccount);
    }

    /**
     * @param Organizationable $organization
     * @param BankAccount $bankAccount
     */
    protected function checkBankAccountOrganization(Organizationable $organization, BankAccount $bankAccount)
    {
        if ($bankAccount->organization !== $organization) {
            throw new NotFoundHttpException(sprintf("%s object not found", get_class($bankAccount)));
        }
    }
}
