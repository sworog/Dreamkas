<?php

namespace Lighthouse\CoreBundle\Document\Product\Version;

use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"loadClassMetadata"})
 */
class ProductVersionMetadataListener
{
    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        // FIXME Dummy workaround to remove product unique index on sku
        if ('Lighthouse\\CoreBundle\\Document\\Product\\Version\\ProductVersion' === $classMetadata->getName()) {
            foreach ($classMetadata->indexes as $i => $index) {
                if (isset($index['keys']['sku']) && 1 == count($index['keys'])) {
                    unset($classMetadata->indexes[$i]);
                }
            }
        }
    }
}
