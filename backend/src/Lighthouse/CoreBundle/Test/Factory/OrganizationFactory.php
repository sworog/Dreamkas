<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Lighthouse\CoreBundle\Document\Organization\Organization;
use Lighthouse\CoreBundle\Document\Organization\OrganizationRepository;

class OrganizationFactory extends AbstractFactory
{
    const DEFAULT_ORGANIZATION_NAME = 'Организация';

    /**
     * @var array(name => id)
     */
    protected $organizations = array();

    /**
     * @param string $name
     * @param array $data
     * @return Organization
     */
    public function createOrganization($name = self::DEFAULT_ORGANIZATION_NAME, array $data = array())
    {
        $organization = new Organization();
        $organization->name = $name;

        $this->populate($organization, $data);

        $this->getOrganizationRepository()->save($organization);

        $this->organizations[$name] = $organization->id;

        return $organization;
    }

    /**
     * @param string $name
     * @return Organization
     */
    public function getOrganization($name = self::DEFAULT_ORGANIZATION_NAME)
    {
        if (!isset($this->organizations[$name])) {
            $this->createOrganization($name);
        }
        return $this->getOrganizationById($this->organizations[$name]);
    }

    /**
     * @param string $id
     * @return Organization
     */
    public function getOrganizationById($id)
    {
        return $this->getOrganizationRepository()->find($id);
    }

    /**
     * @return OrganizationRepository
     */
    protected function getOrganizationRepository()
    {
        return $this->container->get('lighthouse.core.document.repository.organization');
    }
}
