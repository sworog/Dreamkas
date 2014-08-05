<?php

namespace Lighthouse\CoreBundle\Request\ParamConverter\Filter;

interface FilterInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function populate(array $data);
}
