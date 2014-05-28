<?php

namespace Lighthouse\CoreBundle\MongoDB;

use Doctrine\ODM\MongoDB\SchemaManager as BaseSchemaManager;

class SchemaManager extends BaseSchemaManager
{
    public function dropCollections()
    {
        foreach ($this->metadataFactory->getAllMetadata() as $class) {
            if ($class->isMappedSuperclass || $class->isEmbeddedDocument) {
                continue;
            }
            if ($class->globalDb) {
                $this->dropDocumentCollection($class->name);
            }
        }
    }

    public function createCollections()
    {
        foreach ($this->metadataFactory->getAllMetadata() as $class) {
            if ($class->isMappedSuperclass || $class->isEmbeddedDocument) {
                continue;
            }
            if ($class->globalDb) {
                $this->createDocumentCollection($class->name);
            }
        }
    }

    public function ensureIndexes($timeout = null)
    {
        foreach ($this->metadataFactory->getAllMetadata() as $class) {
            if ($class->isMappedSuperclass || $class->isEmbeddedDocument) {
                continue;
            }
            if ($class->globalDb) {
                $this->ensureDocumentIndexes($class->name, $timeout);
            }
        }
    }
}
