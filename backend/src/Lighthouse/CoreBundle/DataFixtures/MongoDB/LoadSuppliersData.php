<?php

namespace Lighthouse\CoreBundle\DataFixtures\MongoDB;

use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\Supplier\Supplier;

class LoadSuppliersData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->authenticateProjectByUsername('owner@lighthouse.pro');

        $suppliers = array(
            'ООО "ЕВРО-АРТ"',
            'ЗАО "ТК "МЕГАПОЛИС"',
            'ООО"Вельд-СПБ"',
            'ООО "СЗК "Аврора"',
            'ООО "ТК Балтика"',
            'ООО "ПепсиКо Холдингс"'
        );

        foreach ($suppliers as $name) {
            $supplier = new Supplier();
            $supplier->name = $name;

            $manager->persist($supplier);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 50;
    }
}
