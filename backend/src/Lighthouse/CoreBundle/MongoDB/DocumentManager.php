<?php

namespace Lighthouse\CoreBundle\MongoDB;

use Doctrine\ODM\MongoDB\DocumentManager as BaseDocumentManager;
use Lighthouse\CoreBundle\Document\User\User;
use Lighthouse\CoreBundle\Exception\RuntimeException;
use Lighthouse\CoreBundle\MongoDB\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DocumentManager extends BaseDocumentManager
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var \Doctrine\MongoDB\Database[][]
     */
    protected $projectDocumentDatabases;

    /**
     * @var SchemaManager
     */
    protected $schemaManager;

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
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
        }

        $project = $this->getCurrentProject();

        if (!isset($this->projectDocumentDatabases[$project->getNamespace()][$metadata->name])) {
            $db = $this->getDocumentDatabaseName($metadata);
            $db .= '_' . $project->getNamespace();

            $conn = $this->getConnection();
            $this->projectDocumentDatabases[$project->getNamespace()][$metadata->name] = $conn->selectDatabase($db);
        }

        return $this->projectDocumentDatabases[$project->getNamespace()][$metadata->name];
    }

    /**
     * @return \Lighthouse\CoreBundle\Document\Project\Project
     */
    protected function getCurrentProject()
    {
        if ($this->securityContext->getToken()) {
            $user = $this->securityContext->getToken()->getUser();
            if ($user instanceof User && $user->getProject()) {
                return $user->getProject();
            }
        }

        throw new RuntimeException("User with project is not signed in");
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
