<?php

namespace Lighthouse\CoreBundle\Exception;

use Symfony\Component\Form\FormInterface;
use Exception;

class FlushFailedException extends RuntimeException
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @param Exception $cause
     * @param FormInterface $form
     */
    public function __construct(Exception $cause, FormInterface $form)
    {
        parent::__construct($cause->getMessage(), $cause->getCode(), $cause);
        $this->form = $form;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return Exception
     */
    public function getCause()
    {
        return $this->getPrevious();
    }
}
