<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Rounding\RoundingManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoundingController extends FOSRestController
{
    /**
     * @DI\Inject("lighthouse.core.rounding.manager")
     * @var RoundingManager
     */
    protected $roundingsManager;

    /**
     * @return AbstractRounding[]
     * @ApiDoc(
     *      resource = true
     * )
     */
    public function getRoundingsAction()
    {
        $roundings = $this->roundingsManager->findAll();
        return $roundings;
    }

    /**
     * @param string $name
     * @return \Lighthouse\CoreBundle\Rounding\AbstractRounding
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @ApiDoc
     */
    public function getRoundingAction($name)
    {
        $rounding = $this->roundingsManager->findByName($name);
        if (null === $rounding) {
            throw new NotFoundHttpException(sprintf('Rounding %s not found', $name));
        }
        return $rounding;
    }
}
 