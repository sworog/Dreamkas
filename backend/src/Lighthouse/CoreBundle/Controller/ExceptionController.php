<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\ExceptionController as BaseExceptionController;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends BaseExceptionController
{
    /**
     * @param ViewHandler $viewHandler
     * @param string $currentContent
     * @param int $code
     * @param FlattenException $exception
     * @param DebugLoggerInterface $logger
     * @param string $format
     * @return array
     */
    protected function getParameters(
        ViewHandler $viewHandler,
        $currentContent,
        $code,
        $exception,
        DebugLoggerInterface $logger = null,
        $format = 'html'
    ) {
        $parameters = parent::getParameters($viewHandler, $currentContent, $code, $exception, $logger, $format);
        if ('json' === $format) {
            $parameters['exceptions'] = $exception->toArray();
        }
        return $parameters;
    }
}
