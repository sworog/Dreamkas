<?php

namespace Lighthouse\CoreBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @DI\Service("lighthouse.core.controller.debug")
 * @Route("/debug", service="lighthouse.core.controller.debug")
 */
class DebugController
{
    /**
     * @Route("", name="debug.index")
     */
    public function indexAction()
    {
        ob_start();
        phpinfo();
        $content = ob_get_contents();
        ob_end_clean();

        return new Response($content);
    }
}
