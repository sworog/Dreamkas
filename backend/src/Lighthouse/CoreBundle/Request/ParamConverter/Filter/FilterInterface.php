<?php

namespace Lighthouse\CoreBundle\Request\ParamConverter\Filter;

interface FilterInterface
{
    /**
     * @param array $data
     * @return null
     */
    public function populate(array $data);
}
