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
     * @deprecated
     * @param array $names
     * @param bool $unique
     * @throws \PHPUnit_Framework_AssertionFailedError
     * @return array|string[] sku => id
     */
    protected function createProductsByNames(array $names, $unique = false)
    {
        if ($unique) {
            $names = array_unique($names);
        }
        $products = array();
        $failedNames = array();
        foreach ($names as $name) {
            try {
                $products[$name] = $this->createProductByName($name);
            } catch (\PHPUnit_Framework_AssertionFailedError $e) {
                $failedNames[] = $name;
            }
        }
        if (count($failedNames) > 0) {
            throw new \PHPUnit_Framework_AssertionFailedError(
                sprintf('Failed to create products with following names: %s', implode(', ', $failedNames))
            );
        }
        return $products;
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
