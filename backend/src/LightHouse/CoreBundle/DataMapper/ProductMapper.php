<?php

namespace LightHouse\CoreBundle\DataMapper;

use JMS\DiExtraBundle\Annotation as DI;
use LightHouse\CoreBundle\Entity\Product;

/**
 * @DI\Service("light_house_core.mapper.product", public=true)
 */
class ProductMapper
{
    protected $collectionName = 'products';

    /**
     * @DI\Inject("light_house_core.db.mongo.db")
     * @var \MongoDB
     */
    public $mongoDb;

    /**
     * @param \LightHouse\CoreBundle\Entity\Product $product
     * @return bool
     */
    public function create(Product $product)
    {
        $data = $product->toArray();
        if (array_key_exists('id', $data)) {
            if (null !== $data['id']) {
                $data['_id'] = new \MongoId($data['id']);
            }
            unset($data['id']);
        }
        $result = $this->getCollection()->insert($data, array('safe' => true));
        if (isset($data['_id']) && $data['_id'] instanceof \MongoId) {
            $product->id = (string) $data['_id'];
        }
        if (isset($result['ok']) && 1 == $result['ok']) {
            return true;
        }
    }

    /**
     * @return \MongoCollection
     */
    protected function getCollection()
    {
        return $this->mongoDb->selectCollection($this->collectionName);
    }
}