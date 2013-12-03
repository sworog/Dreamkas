<?php

namespace Lighthouse\CoreBundle\Rest;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler as BaseViewHandler;
use JMS\Serializer\SerializationContext;

class ViewHandler extends BaseViewHandler
{
    /**
     * @param View $view
     * @return SerializationContext
     */
    public function getSerializationContext(View $view)
    {
        $context = parent::getSerializationContext($view);

        $context->enableMaxDepthChecks();

        return $context;
    }
}
