<?php

namespace Lighthouse\CoreBundle\Document\Project;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Lighthouse\CoreBundle\MongoDB\SchemaManager;

/**
 * @DI\DoctrineMongoDBListener(events={"postPersist", "postRemove"})
 */
class ProjectSchemaListener
{
    /**
     * @var RunAsProjectInvoker
     */
    protected $invoker;

    /**
     * @DI\InjectParams({
     *      "invoker" = @DI\Inject("lighthouse.core.project.run_as_project_invoker")
     * })
     * @param RunAsProjectInvoker $invoker
     */
    public function __construct(RunAsProjectInvoker $invoker)
    {
        $this->invoker = $invoker;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof Project) {
            /* @var SchemaManager $schemaManager */
            $schemaManager = $eventArgs->getDocumentManager()->getSchemaManager();
            $this->invoker->invoke($schemaManager, $document)->createProject($document);
        }
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof Project) {
            /* @var SchemaManager $schemaManager */
            $schemaManager = $eventArgs->getDocumentManager()->getSchemaManager();
            $schemaManager->dropProjectCollections($document);
        }
    }
}
