<?php

namespace Lighthouse\CoreBundle\MongoDB;

use Doctrine\ODM\MongoDB\DocumentManager as BaseDocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\MongoDB\Mapping\ClassMetadata;
use Lighthouse\CoreBundle\Security\Project\ProjectContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DocumentManager extends BaseDocumentManager implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var \Doctrine\MongoDB\Database[][]
     */
    protected $projectDocumentDatabases;

    /**
     * @var \Doctrine\MongoDB\Collection[][]
     */
    protected $projectDocumentCollections;

    /**
     * @var SchemaManager
     */
    protected $schemaManager;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return ProjectContext
     */
    protected function getProjectContext()
    {
        return $this->container->get('project.context');
    }

    /**
     * @param string $className
     * @return ClassMetadata
     */
    public function getClassMetadata($className)
    {
        return parent::getClassMetadata($className);
    }

    /**
     * @param string $className
     * @return \Doctrine\MongoDB\Database
     */
    public function getDocumentDatabase($className)
    {
        $metadata = $this->getClassMetadata($className);

        if ($metadata->globalDb) {
            return parent::getDocumentDatabase($className);
        } else {
            return $this->getProjectDocumentDatabase($className, $this->getCurrentProject());
        }
    }

    /**
     * @param string $className
     * @param Project $project
     * @return \Doctrine\MongoDB\Database
     */
    public function getProjectDocumentDatabase($className, Project $project)
    {
        $metadata = $this->getClassMetadata($className);

        if (!isset($this->projectDocumentDatabases[$project->getName()][$metadata->name])) {
            $db = $this->getDocumentDatabaseName($metadata);
            $db .= '_' . $project->getName();

            $conn = $this->getConnection();
            $this->projectDocumentDatabases[$project->getName()][$metadata->name] = $conn->selectDatabase($db);
        }

        return $this->projectDocumentDatabases[$project->getName()][$metadata->name];
    }

    /**
     * @param ClassMetadata $metadata
     * @return string
     */
    protected function getDocumentDatabaseName(ClassMetadata $metadata)
    {
        $db = $metadata->getDatabase();
        $db = $db ? $db : $this->getConfiguration()->getDefaultDB();
        $db = $db ? $db : 'doctrine';

        return $db;
    }

    /**
     * @param string $className
     * @return \Doctrine\MongoDB\Collection
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getDocumentCollection($className)
    {
        $metadata = $this->getClassMetadata($className);
        if ($metadata->globalDb) {
            return parent::getDocumentCollection($className);
        } else {
            return $this->getProjectDocumentCollection($className, $this->getCurrentProject());
        }
    }

    /**
     * @param string $className
     * @param Project $project
     * @return \Doctrine\MongoDB\Collection
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function getProjectDocumentCollection($className, Project $project)
    {
        $metadata = $this->getClassMetadata($className);

        if (!isset($this->projectDocumentCollections[$project->getName()][$metadata->name])) {

            $collectionName = $metadata->getCollection();

            if (!$collectionName) {
                throw MongoDBException::documentNotMappedToCollection($metadata->name);
            }

            $db = $this->getProjectDocumentDatabase($className, $project);

            $collection = $metadata->isFile()
                ? $db->getGridFS($collectionName)
                : $db->selectCollection($collectionName);

            if ($metadata->slaveOkay !== null) {
                $collection->setSlaveOkay($metadata->slaveOkay);
            }

            $this->projectDocumentCollections[$project->getName()][$metadata->name] = $collection;
        }

        return $this->projectDocumentCollections[$project->getName()][$metadata->name];
    }

    /**
     * @return Project
     */
    protected function getCurrentProject()
    {
        $project = $this->getProjectContext()->getCurrentProject();
        if (!$project) {
            throw new RuntimeException("User with project is not signed in");
        }
        return $project;
    }

    /**
     * @return SchemaManager
     */
    public function getSchemaManager()
    {
        if (null === $this->schemaManager) {
            $this->schemaManager = new SchemaManager($this, $this->getMetadataFactory());
        }
        return $this->schemaManager;
    }
}
