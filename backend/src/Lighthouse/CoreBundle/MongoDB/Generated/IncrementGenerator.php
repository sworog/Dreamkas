<?php

namespace Lighthouse\CoreBundle\MongoDB\Generated;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Id\IncrementGenerator as BaseIncrementGenerator;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.mongodb.generator.increment")
 */
class IncrementGenerator extends BaseIncrementGenerator
{
    /**
     * @DI\InjectParams({
     *      "collection" = @DI\Inject("%doctrine_mongodb.odm.generator.increment.collection%")
     * })
     * @param string $collection
     */
    public function __construct($collection)
    {
        $this->setCollection($collection);
    }

    /**
     * @param DocumentManager $dm
     * @param string $className
     * @param int $startValue
     * @return int
     */
    public function setStartValue(DocumentManager $dm, $className, $startValue)
    {
        $db = $dm->getDocumentDatabase($className);

        $coll = $this->collection ?: 'doctrine_increment_ids';
        $key = $this->key ?: $dm->getDocumentCollection($className)->getName();

        $query = array('_id' => $key);
        $newObj = array('$set' => array('current_id' => $startValue));
        $options = array('upsert' => true, 'new' => true);

        $result = $db->selectCollection($coll)->findAndUpdate($query, $newObj, $options);

        return $result['current_id'];
    }

    /**
     * @param DocumentManager $dm
     * @param string $className
     */
    public function remove(DocumentManager $dm, $className)
    {
        $db = $dm->getDocumentDatabase($className);
        $coll = $this->collection ?: 'doctrine_increment_ids';
        $key = $this->key ?: $dm->getDocumentCollection($className)->getName();

        $query = array('_id' => $key);
        $db->selectCollection($coll)->remove($query);
    }

    /**
     * @param DocumentManager $dm
     * @param object $document
     * @return string
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function generate(DocumentManager $dm, $document)
    {
        return (string) parent::generate($dm, $document);
    }
}
