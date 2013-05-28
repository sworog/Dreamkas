<?php

namespace Lighthouse\CoreBundle\Document\Klass;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Document\Group\GroupRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove"})
 */
class KlassListener
{
    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @DI\InjectParams({
     *      "groupRepository"=@DI\Inject("lighthouse.core.document.repository.group")
     * })
     * @param GroupRepository $groupRepository
     */
    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof Klass) {
            $this->checkKlassHasNoGroups($document);
        }
    }

    /**
     * @param Klass $klass
     * @throws KlassNotEmptyException
     */
    protected function checkKlassHasNoGroups(Klass $klass)
    {
        $count = $this->groupRepository->countByKlass($klass->id);
        if ($count > 0) {
            throw new KlassNotEmptyException('Klass is not empty');
        }
    }
}
