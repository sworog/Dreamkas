<?php

namespace Lighthouse\CoreBundle\MongoDB;

use Doctrine\MongoDB\Database;
use Doctrine\ODM\MongoDB\SchemaManager as BaseSchemaManager;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Project\Project;
use Lighthouse\CoreBundle\MongoDB\Mapping\ClassMetadata;

class SchemaManager extends BaseSchemaManager
{
    const GLOBAL_DB = true;
    const PROJECT_DB = false;

    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @param null|bool $global true: only global, false: only project, null: all
     * @return ClassMetadata[]
     */
    protected function getAllClassMetadata($global = null)
    {
        return array_filter(
            $this->metadataFactory->getAllMetadata(),
            function (ClassMetadata $class) use ($global) {
                $result = !$class->isMappedSuperclass && !$class->isEmbeddedDocument;
                if ($result && null !== $global) {
                    $result = $class->globalDb == $global;
                }
                return $result;
            }
        );
    }

    /**
     * @param string $documentName
     * @return ClassMetadata
     */
    protected function getClassMetadata($documentName)
    {
        $class = $this->dm->getClassMetadata($documentName);
        if ($class->isMappedSuperclass || $class->isEmbeddedDocument) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Document "%s" is mapped super classes or embedded and is not schema operable.',
                    $class->name
                )
            );
        }
        return $class;
    }

    /**
     * @return Project[]
     */
    protected function getProjects()
    {
        return $this->dm->getRepository(Project::getClassName())->findAll();
    }

    public function dropGlobalCollections()
    {
        foreach ($this->getAllClassMetadata(self::GLOBAL_DB) as $class) {
            $this->dropDocumentCollection($class->name);
        }
    }

    public function dropProjectCollections(Project $project)
    {
        foreach ($this->getAllClassMetadata(self::PROJECT_DB) as $class) {
            $this->dropProjectDocumentCollection($class->name, $project);
        }
    }

    public function dropAllProjectCollections()
    {
        foreach ($this->getProjects() as $project) {
            $this->dropProjectCollections($project);
        }
    }

    public function dropProjectDocumentCollection($documentName, Project $project)
    {
        $class = $this->getClassMetadata($documentName);
        $this->dm->getProjectDocumentCollection($class->name, $project)->drop();
    }

    public function dropCollections()
    {
        $this->dropAllProjectCollections();
        $this->dropGlobalCollections();
    }

    public function dropGlobalDatabases()
    {
        /* @var Database[] $databases */
        $databases = array();
        foreach ($this->getAllClassMetadata(self::GLOBAL_DB) as $class) {
            $database = $this->dm->getDocumentDatabase($class->name);
            $databases[$database->getName()] = $database;
        }
        foreach ($databases as $database) {
            $database->drop();
        }
    }

    public function dropProjectDatabases()
    {
        /* @var Database[] $databases */
        $databases = array();
        foreach ($this->getProjects() as $project) {
            foreach ($this->getAllClassMetadata(self::PROJECT_DB) as $class) {
                $database = $this->dm->getProjectDocumentDatabase($class->name, $project);
                $databases[$database->getName()] = $database;
            }
        }
        foreach ($databases as $database) {
            $database->drop();
        }
    }

    public function dropDatabases()
    {
        $this->dropProjectDatabases();
        $this->dropGlobalDatabases();
    }

    public function createGlobalDatabases()
    {
        foreach ($this->getAllClassMetadata(self::GLOBAL_DB) as $class) {
            $this->createDocumentDatabase($class->name);
        }
    }

    /**
     * @param string $documentName
     * @param Project $project
     */
    public function createProjectDocumentDatabase($documentName, Project $project)
    {
        $class = $this->getClassMetadata($documentName);
        $this->dm->getProjectDocumentDatabase($class->name, $project)->execute("function() { return true; }");
    }

    /**
     * @param Project $project
     */
    public function createProjectDatabases(Project $project)
    {
        foreach ($this->getAllClassMetadata(self::PROJECT_DB) as $class) {
            $this->createProjectDocumentDatabase($class->name, $project);
        }
    }

    public function createAllProjectDatabases()
    {
        foreach ($this->getProjects() as $project) {
            $this->createProjectDatabases($project);
        }
    }

    public function createDatabases()
    {
        $this->createGlobalDatabases();
        $this->createAllProjectDatabases();
    }

    public function createGlobalCollections()
    {
        foreach ($this->getAllClassMetadata(self::GLOBAL_DB) as $class) {
            $this->createDocumentCollection($class->name);
        }
    }

    /**
     * @param Project $project
     */
    public function createProjectCollections(Project $project)
    {
        foreach ($this->getAllClassMetadata(self::PROJECT_DB) as $class) {
            $this->createProjectDocumentCollection($class->name, $project);
        }
    }

    /**
     * @param string $documentName
     * @param Project $project
     */
    public function createProjectDocumentCollection($documentName, Project $project)
    {
        $class = $this->dm->getClassMetadata($documentName);
        $this->dm->getProjectDocumentDatabase($class->name, $project)->createCollection(
            $class->getCollection(),
            $class->getCollectionCapped(),
            $class->getCollectionSize(),
            $class->getCollectionMax()
        );
    }

    public function createAllProjectCollections()
    {
        foreach ($this->getProjects() as $project) {
            $this->createProjectCollections($project);
        }
    }

    public function createCollections()
    {
        $this->createGlobalCollections();
        $this->createAllProjectCollections();
    }

    /**
     * @param null|int $timeout
     */
    public function ensureGlobalIndexes($timeout = null)
    {
        foreach ($this->getAllClassMetadata(self::GLOBAL_DB) as $class) {
            $this->ensureDocumentIndexes($class->name, $timeout);
        }
    }

    /**
     * @param null $timeout
     */
    public function ensureAllProjectIndexes($timeout = null)
    {
        foreach ($this->getProjects() as $project) {
            $this->ensureProjectIndexes($project, $timeout);
        }
    }

    /**
     * @param Project $project
     * @param null|int $timeout
     */
    public function ensureProjectIndexes(Project $project, $timeout = null)
    {
        foreach ($this->getAllClassMetadata(self::PROJECT_DB) as $class) {
            $this->ensureProjectDocumentIndexes($class->name, $project, $timeout);
        }
    }

    /**
     * @param $documentName
     * @param Project $project
     * @param null|int $timeout
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function ensureProjectDocumentIndexes($documentName, Project $project, $timeout = null)
    {
        $class = $this->getClassMetadata($documentName);

        if ($indexes = $this->getDocumentIndexes($documentName)) {
            $collection = $this->dm->getProjectDocumentCollection($class->name, $project);
            foreach ($indexes as $index) {
                $keys = $index['keys'];
                $options = $this->getIndexOptions($index['options'], $timeout);

                $collection->ensureIndex($keys, $options);
            }
        }
    }

    /**
     * @param null $timeout
     */
    public function ensureIndexes($timeout = null)
    {
        $this->ensureGlobalIndexes($timeout);
        $this->ensureAllProjectIndexes($timeout);
    }

    public function deleteGlobalIndexes()
    {
        foreach ($this->getAllClassMetadata(self::GLOBAL_DB) as $class) {
            $this->dm->getDocumentCollection($class->name)->deleteIndexes();
        }
    }

    /**
     * @param Project $project
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function deleteProjectDocumentIndexes(Project $project)
    {
        foreach ($this->getAllClassMetadata(self::PROJECT_DB) as $class) {
            $this->dm->getProjectDocumentCollection($class->name, $project)->deleteIndexes();
        }
    }

    public function deleteAllProjectIndexes()
    {
        foreach ($this->getProjects() as $project) {
            $this->deleteProjectDocumentIndexes($project);
        }
    }

    public function deleteIndexes()
    {
        $this->deleteAllProjectIndexes();
        $this->deleteGlobalIndexes();
    }

    /**
     * @param array $options
     * @param null|int $timeout
     * @return array
     */
    protected function getIndexOptions(array $options, $timeout = null)
    {
        if (!isset($options['safe']) && !isset($options['w'])) {
            if (version_compare(phpversion('mongo'), '1.3.0', '<')) {
                $options['safe'] = true;
            } else {
                $options['w'] = 1;
            }
        }

        if (isset($options['safe']) && !isset($options['w']) &&
            version_compare(phpversion('mongo'), '1.3.0', '>=')) {

            $options['w'] = is_bool($options['safe']) ? (integer) $options['safe'] : $options['safe'];
            unset($options['safe']);
        }

        if (!isset($options['timeout']) && isset($timeout)) {
            $options['timeout'] = $timeout;
        }

        return $options;
    }

    /**
     * @param Project $project
     */
    public function createProject(Project $project)
    {
        $this->createProjectCollections($project);
        $this->ensureProjectIndexes($project);
    }
}
