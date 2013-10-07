<?php

namespace Lighthouse\CoreBundle\Document\Config;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class ConfigRepository extends DocumentRepository
{
    /**
     * @param string $name
     * @param string|null $default
     * @return string|null
     */
    public function findValueByName($name, $default = null)
    {
        $config = $this->findOneBy(array('name' => $name));
        if ($config) {
            return $config->value;
        } else {
            return $default;
        }
    }
}
