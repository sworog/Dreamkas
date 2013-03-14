<?php

namespace Lighthouse\CoreBundle;

use Lighthouse\CoreBundle\Types\MongoDB\MoneyType;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ODM\MongoDB\Mapping\Types\Type;

class LighthouseCoreBundle extends Bundle
{
    public function __construct()
    {
        Type::registerType('money', 'Lighthouse\CoreBundle\Types\MongoDB\MoneyType');
    }
}
