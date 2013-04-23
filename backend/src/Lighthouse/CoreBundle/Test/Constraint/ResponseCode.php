<?php

namespace Lighthouse\CoreBundle\Test\Constraint;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Response;

class ResponseCode extends \PHPUnit_Framework_Constraint
{
    /**
     * @var integer
     */
    protected $code;

    /**
     * @param integer $code
     */
    public function __construct($code)
    {
        if ($code instanceof Response) {
            $code = $code->getStatusCode();
        } elseif ($code instanceof Client) {
            $code = $code->getResponse()->getStatusCode();
        }
        $this->code = $code;
    }

    /**
     * @param int $other expected response code
     * @return bool
     */
    public function matches($other)
    {
        return $other == $this->code;
    }

    /**
     * @param mixed $other
     * @return string
     */
    protected function failureDescription($other)
    {
        return $this->toString() . ' ' . $other;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->code . ' response code matches expected';
    }
}
