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
     * @var string
     */
    protected $responseBody;

    /**
     * @param integer $code
     */
    public function __construct($code)
    {
        if ($code instanceof Response) {
            $this->responseBody = $code->getContent();
            $code = $code->getStatusCode();
        } elseif ($code instanceof Client) {
            $this->responseBody = $code->getResponse()->getContent();
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
        $description =  $this->toString() . ' ' . $other;

        if (!empty($this->responseBody)) {
            $description.= "\nResponse body: ". $this->responseBody;
            $json = json_decode($this->responseBody, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                $description.= "\nJSON: " . var_export($json, true);
            }
        }

        return $description;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->code . ' response code matches expected';
    }
}
