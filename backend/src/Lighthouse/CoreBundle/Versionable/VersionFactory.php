<?php

namespace Lighthouse\CoreBundle\Versionable;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.versionable.factory")
 */
class VersionFactory
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @DI\InjectParams({
     *      "dm" = @DI\Inject("doctrine_mongodb.odm.document_manager")
     * })
     * @param DocumentManager $dm
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * @param VersionableInterface $document
     * @return VersionInterface
     */
    public function createDocumentVersion(VersionableInterface $document)
    {
        $classMetadata = $this->getClassMetadata($document);
        $versionableMetadata = $this->getClassVersionable($document);

        /* @var VersionInterface $versionable */
        $versionable = $versionableMetadata->getReflectionClass()->newInstance();

        foreach ($versionable->getVersionFields() as $field) {
            $value = $classMetadata->getFieldValue($document, $field);
            $versionableMetadata->setFieldValue($versionable, $field, $value);
        }

        $versionable->setObject($document);

        $version = $this->getVersion($versionable);
        $versionable->setVersion($version);

        return $versionable;
    }

    /**
     * @param VersionInterface $objectVersion
     * @return string
     */
    public function getVersion(VersionInterface $objectVersion)
    {
        $data = array();
        $classMeta = $this->getClassMetadata($objectVersion);

        foreach ($objectVersion->getVersionFields() as $field) {
            $data[$field] = $this->getFieldValue($objectVersion, $classMeta, $field);
        }

        $version = md5(serialize($data));

        return $version;
    }

    /**
     * @param VersionInterface $version
     * @param ClassMetadata $classMeta
     * @param string $field
     * @return string
     */
    protected function getFieldValue(VersionInterface $version, ClassMetadata $classMeta, $field)
    {
        $value = $classMeta->getFieldValue($version, $field);
        if ($classMeta->isSingleValuedAssociation($field) && null !== $value) {
            $associationMetadata = $this->getClassMetadata($value);
            $value = $associationMetadata->getIdentifierValue($value);
        }
        return $value;
    }

    /**
     * @param $document
     * @return ClassMetadata
     */
    protected function getClassMetadata($document)
    {
        return $this->dm->getClassMetadata(get_class($document));
    }

    /**
     * @param VersionableInterface $object
     * @return ClassMetadata
     * @throws RuntimeException
     */
    protected function getClassVersionable(VersionableInterface $object)
    {
        $versionClass = $object->getVersionClass();
        $versionClassMetadata = $this->dm->getClassMetadata($versionClass);
        if ($versionClassMetadata->getReflectionClass()->implementsInterface(
            'Lighthouse\\CoreBundle\\Versionable\\VersionInterface'
        )) {
            return $versionClassMetadata;
        }
        throw new RuntimeException('Document is not versionable');
    }
}
