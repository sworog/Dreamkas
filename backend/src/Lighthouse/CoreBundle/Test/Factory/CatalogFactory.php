<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Rounding\Nearest1;
use Lighthouse\CoreBundle\Rounding\RoundingManager;

class CatalogFactory extends AbstractFactory
{
    const DEFAULT_GROUP_NAME = 'Продовольственные товары';
    const DEFAULT_ROUNDING_NAME = Nearest1::NAME;

    /**
     * @var Group[]
     */
    protected $groups = array();

    /**
     * @var array name => id
     */
    protected $groupNames = array();

    /**
     * @param string $name
     * @param float $retailMarkupMin
     * @param float $retailMarkupMax
     * @param string $rounding
     * @return Group
     */
    public function createGroup(
        $name = self::DEFAULT_GROUP_NAME,
        $retailMarkupMin = null,
        $retailMarkupMax = null,
        $rounding = self::DEFAULT_ROUNDING_NAME
    ) {
        $group = new Group();
        $group->name = $name;
        $group->retailMarkupMin = $retailMarkupMin;
        $group->retailMarkupMax = $retailMarkupMax;
        $group->rounding = $this->getRoundingManager()->findByName($rounding);

        $this->getDocumentManager()->persist($group);
        $this->getDocumentManager()->flush();

        return $group;
    }

    /**
     * @param string $name
     * @return Group
     */
    public function getGroup($name = self::DEFAULT_GROUP_NAME)
    {
        if (!isset($this->groupNames[$name])) {
            $group = $this->createGroup($name);
            $this->groups[$group->id] = $group;
            $this->groupNames[$group->name] = $group->id;
        }
        return $this->groups[$this->groupNames[$name]];
    }

    /**
     * @return RoundingManager
     */
    protected function getRoundingManager()
    {
        return $this->container->get('lighthouse.core.rounding.manager');
    }
}
