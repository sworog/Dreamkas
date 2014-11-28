<?php

namespace Lighthouse\CoreBundle\Test;

use Lighthouse\CoreBundle\Test\Factory\CatalogFactory;

class DataAwareTestCase extends ContainerAwareTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->clearMongoDb();
        $this->authenticateProject();
    }

    /**
     * @param string $name
     * @param string $subCategoryId
     * @return string
     */
    protected function createProductByName($name = CatalogFactory::DEFAULT_PRODUCT_NAME, $subCategoryId = null)
    {
        $subCategory = ($subCategoryId) ? $this->factory()->catalog()->getSubCategoryById($subCategoryId) : null;
        return $this->factory()->catalog()->createProductByName($name, $subCategory)->id;
    }
}
