<?php

namespace Lighthouse\CoreBundle\Util\Rest;

use FOS\RestBundle\Util\ExceptionWrapper as BaseExceptionWrapper;
use Symfony\Component\Form\Form;

class ExceptionWrapper extends BaseExceptionWrapper
{
    /**
     * @var array
     */
    private $exceptions;

    /**
     * @var array
     */
    private $children;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        if (isset($data['errors']) && $data['errors'] instanceof Form) {
            $this->children = $data['errors']->all();
            $data['errors'] = $data['errors']->getErrors();
        }

        if (isset($data['exceptions'])) {
            $this->exceptions = $data['exceptions'];
        }

        parent::__construct($data);
    }
}
