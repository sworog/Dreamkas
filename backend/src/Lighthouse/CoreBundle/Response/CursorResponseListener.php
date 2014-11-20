<?php

namespace Lighthouse\CoreBundle\Response;

use Doctrine\ODM\MongoDB\Cursor;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @DI\Service("lighthouse.response.core.cursor_response_listener")
 */
class CursorResponseListener
{
    /**
     * @DI\Observe("kernel.view", priority=120)
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $controllerResult = $event->getControllerResult();

        if ($controllerResult instanceof Cursor) {

            $view = new View($controllerResult);

            $this->handleCursorTotalCount($view, $controllerResult);
            $this->handleViewSerializerGroups($view, $event->getRequest());

            $event->setControllerResult($view);
        }
    }

    /**
     * @param View $view
     * @param Cursor $cursor
     */
    protected function handleCursorTotalCount(View $view, Cursor $cursor)
    {
        $response = $view->getResponse();
        $response->headers->set('X-Total-Count', $cursor->count());
    }

    /**
     * @param View $view
     * @param Request $request
     */
    protected function handleViewSerializerGroups(View $view, Request $request)
    {
        /** @var \FOS\RestBundle\Controller\Annotations\View $configuration */
        $configuration = $request->attributes->get('_view');

        if ($configuration && $configuration->getSerializerGroups()) {
            $context = new SerializationContext();
            $context->setGroups($configuration->getSerializerGroups());
            $view->setSerializationContext($context);
        }
    }
}
