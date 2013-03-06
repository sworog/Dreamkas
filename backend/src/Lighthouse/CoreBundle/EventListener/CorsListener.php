<?php

namespace Lighthouse\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 */
class CorsListener
{
    const CORS_HEADER = 'Access-Control-Allow-Origin';

    /**
     * @DI\Observe("kernel.response")
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->headers->has('Origin')) {
            $origin = $request->headers->get('Origin');
            if ($this->checkOrigin($origin)) {
                $response = $event->getResponse();
                $response->headers->set(self::CORS_HEADER, $origin);
            }
        }
    }

    /**
     * @param string $httpHost
     * @return bool
     */
    protected function checkOrigin($origin)
    {
        return true;
    }
}
