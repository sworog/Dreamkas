<?php

namespace Lighthouse\CoreBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\Form\RoundingRoundType;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Lighthouse\CoreBundle\Rounding\RoundingManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
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
     * @return AbstractRounding
     * @ApiDoc
     */
    public function getRoundingAction($name)
    {
        return $this->findRounding($name);
    }

    /**
     * @param string $name
     * @param Request $request
     * @return array|FormInterface
     * @ApiDoc
     * @Rest\View(statusCode=201)
     * @Rest\Route("roundings/{name}/round")
     */
    public function postRoundingRoundAction(Request $request, $name)
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
            return $form;
        }
    }

    /**
     * @param $name
     * @return AbstractRounding
     * @throws NotFoundHttpException
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
