<?php

namespace Lighthouse\CoreBundle\EventListener;

use FOS\RestBundle\View\View;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Response\DocumentResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @DI\Service("lighthouse.core.document_view_response_listener")
 */
class DocumentViewResponseListener
{
    /**
     * @DI\Observe("kernel.view", priority=110)
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if (!(HttpKernelInterface::SUB_REQUEST === $event->getRequestType())) {
            return;
        }

        $controllerResult = $event->getControllerResult();

        if ($controllerResult instanceof AbstractDocument) {
            $response = new DocumentResponse();
            $response->setDocument($controllerResult);

            $view = new View($controllerResult);
            $view->setResponse($response);

            $event->setControllerResult($view);
        }
    }
}
