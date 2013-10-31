<?php

namespace Lighthouse\CoreBundle\Document\WriteOff;

use Lighthouse\CoreBundle\Request\ParamConverter\Filter\FilterInterface;

class WriteOffsFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $number;

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return bool
     */
    public function hasNumber()
    {
        return null !== $this->number;
    }

    /**
     * @param array $data
     * @return null
     */
    public function populate(array $data)
    {
        if (isset($data['number'])) {
            $this->number = $data['number'];
        }
    }
}
