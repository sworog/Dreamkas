<?php

namespace Lighthouse\CoreBundle\Document\Order;

use Lighthouse\CoreBundle\Request\ParamConverter\Filter\FilterInterface;

class OrdersFilter implements FilterInterface
{
    /**
     * @var bool
     */
    protected $incomplete;

    /**
     * @return boolean
     */
    public function getIncomplete()
    {
        return $this->incomplete;
    }

    /**
     * @param boolean $incomplete
     */
    public function setIncomplete($incomplete)
    {
        $this->incomplete = (bool) $incomplete;
    }

    /**
     * @return boolean
     */
    public function hasIncomplete()
    {
        return null !== $this->incomplete;
    }

    /**
     * @param array $data
     * @return null
     */
    public function populate(array $data)
    {
        if (isset($data['incomplete'])) {
            $this->setIncomplete($data['incomplete']);
        }
    }
}
