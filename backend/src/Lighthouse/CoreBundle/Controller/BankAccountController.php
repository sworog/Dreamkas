<?php

namespace Lighthouse\CoreBundle\Controller;

use Lighthouse\CoreBundle\Document\BankAccount\BankAccount;
use Lighthouse\CoreBundle\Document\BankAccount\BankAccountRepository;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Document\Organization\Organization;
use Lighthouse\CoreBundle\Form\BankAccountType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @param Organization $organization
     * @return BankAccount|FormInterface
     */
    public function postOrganizationBankAccountAction(Request $request, Organization $organization)
    {
        $bankAccount = $this->documentRepository->createNew();
        $bankAccount->organization = $organization;
        return $this->processForm($request, $bankAccount);
    }
}
