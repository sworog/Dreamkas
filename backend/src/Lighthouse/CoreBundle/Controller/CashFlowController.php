<?php

namespace Lighthouse\CoreBundle\Controller;

use Doctrine\ODM\MongoDB\Cursor;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlow;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlowFilter;
use Lighthouse\CoreBundle\Document\CashFlow\CashFlowRepository;
use Lighthouse\CoreBundle\Exception\NotEditableException;
use Lighthouse\CoreBundle\Form\CashFlowFilterType;
use Lighthouse\CoreBundle\Form\CashFlowType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;

class CashFlowController extends AbstractRestController
{
    /**
     * @DI\Inject("lighthouse.core.document.repository.cash_flow")
     * @var CashFlowRepository
     */
    protected $documentRepository;

    /**
     * @return FormTypeInterface
     */
    protected function getDocumentFormType()
    {
        return new CashFlowType();
    }

    /**
     * @Rest\Route("cashFlows")
     * @Rest\View(statusCode=201)
     *
     * @param Request $request
     * @return FormInterface|CashFlow
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function postCashFlowsAction(Request $request)
    {
        return $this->processPost($request);
    }

    /**
     * @Rest\Route("cashFlows/{cashFlow}")
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @param CashFlow $cashFlow
     * @return FormInterface|CashFlow
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function putCashFlowsAction(Request $request, CashFlow $cashFlow)
    {
        $this->checkCashFlowIsEditable($cashFlow);
        return $this->processForm($request, $cashFlow);
    }

    /**
     * @Rest\Route("cashFlows")
     *
     * @param Request $request
     * @return CashFlow[]|Cursor
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc(resource=true)
     */
    public function getCashFlowsAction(Request $request)
    {
        $repository = $this->documentRepository;

        return $this->processFormCallback(
            $request,
            function (CashFlowFilter $filter) use ($repository) {
                return $repository->findCashFlowsByFilter($filter);
            },
            new CashFlowFilter(),
            new CashFlowFilterType()
        );
    }

    /**
     * @Rest\Route("cashFlows/{cashFlow}")
     *
     * @param CashFlow $cashFlow
     * @return CashFlow
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function getCashFlowAction(CashFlow $cashFlow)
    {
        return $cashFlow;
    }

    /**
     * @Rest\Route("cashFlows/{cashFlow}")
     *
     * @param CashFlow $cashFlow
     * @Secure(roles="ROLE_COMMERCIAL_MANAGER")
     * @ApiDoc
     */
    public function deleteCashFlowAction(CashFlow $cashFlow)
    {
        $this->checkCashFlowIsEditable($cashFlow);
        $this->processDelete($cashFlow);
    }

    /**
     * @param CashFlow $cashFlow
     */
    protected function checkCashFlowIsEditable(CashFlow $cashFlow)
    {
        if (!$cashFlow->isEditable()) {
            throw new NotEditableException(
                $this->container->get('translator')
                    ->trans('lighthouse.messages.cash_flow.edit', array(), 'messages')
            );
        }
    }
}
