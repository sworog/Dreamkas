<?php

namespace Lighthouse\CoreBundle\DataFixtures\ODM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lighthouse\CoreBundle\Document\Classifier\Category\Category;
use Lighthouse\CoreBundle\Document\Classifier\Group\Group;
use Lighthouse\CoreBundle\Document\Classifier\SubCategory\SubCategory;

class LoadCatalogData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $group = new Group();
        $group->name = 'Алкоголь';

        $manager->persist($group);

        $category = new Category();
        $category->name = 'Легкий';
        $category->group = $group;

        $manager->persist($category);

        $subCategory1 = new SubCategory();
        $subCategory1->name = 'Пиво';
        $subCategory1->category = $category;

        $manager->persist($subCategory1);

        $subCategory2 = new SubCategory();
        $subCategory2->name = 'Вино';
        $subCategory2->category = $category;

        $manager->persist($subCategory2);

        $subCategory3 = new SubCategory();
        $subCategory3->name = 'Коктейли';
        $subCategory3->category = $category;

        $manager->persist($subCategory3);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 40;
    }
}
