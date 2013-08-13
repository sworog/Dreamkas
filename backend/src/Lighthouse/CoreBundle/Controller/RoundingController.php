<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Rounding\RoundingManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RoundingController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.rounding.manager")
     * @var RoundingManager
     */
    protected $roundingsManager;

    /**
     * @ApiDoc
     */
    public function getRoundingsAction()
    {
        $roundings = $this->roundingsManager->findAll();
        return $roundings;
    }
}
 