<?php

namespace Lighthouse\CoreBundle\Document\Klass;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Lighthouse\CoreBundle\Document\Category\CategoryRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\DoctrineMongoDBListener(events={"preRemove"})
 */
class KlassListener
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @DI\InjectParams({
     *      "categoryRepository"=@DI\Inject("lighthouse.core.document.repository.category")
     * })
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();
        if ($document instanceof Klass) {
            $this->checkKlassHasNoCategories($document);
        }
    }

    /**
     * @param Klass $klass
     * @throws KlassNotEmptyException
     */
    protected function checkKlassHasNoCategories(Klass $klass)
    {
        $count = $this->categoryRepository->countByKlass($klass->id);
        if ($count > 0) {
            throw new KlassNotEmptyException('Klass is not empty');
        }
    }
}
