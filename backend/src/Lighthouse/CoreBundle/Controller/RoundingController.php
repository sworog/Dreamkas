<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Form\RoundingRoundType;
use Lighthouse\CoreBundle\Rounding\RoundingManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

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
        return $this->findRounding($name);
    }

    /**
     * @param string $name
     * @param Request $request
     * @return array|\FOS\RestBundle\View\View
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @ApiDoc
     * @Rest\View(statusCode=201)
     */
    public function postRoundingRoundAction($name, Request $request)
    {
        $rounding = $this->findRounding($name);

        $roundingRoundType = new RoundingRoundType();
        $form = $this->createForm($roundingRoundType);
        $form->submit($request);

        if ($form->isValid()) {
            $roundedPrice = $rounding->round($form->get('price')->getData());
            return array(
                'price' => $roundedPrice,
            );
        } else {
            return $this->view($form, Codes::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $name
     * @return \Lighthouse\CoreBundle\Rounding\AbstractRounding
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function findRounding($name)
    {
        $rounding = $this->roundingsManager->findByName($name);
        if (null === $rounding) {
            throw new NotFoundHttpException(sprintf('Rounding %s not found', $name));
        }
        return $rounding;
    }
}
