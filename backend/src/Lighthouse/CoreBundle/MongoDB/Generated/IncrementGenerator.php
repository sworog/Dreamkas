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

        $command = array();
        $command['findandmodify'] = $coll;
        $command['query'] = $query;
        $command['update'] = $newObj;
        $command['upsert'] = true;
        $command['new'] = true;
        $result = $db->command($command);
        return $result['value']['current_id'];
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
}
