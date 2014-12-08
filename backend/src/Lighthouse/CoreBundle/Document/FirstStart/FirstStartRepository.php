<?php

namespace Lighthouse\CoreBundle\Document\FirstStart;

use Doctrine\ODM\MongoDB\LockMode;
use Lighthouse\CoreBundle\Document\DocumentRepository;
use Lighthouse\CoreBundle\Document\Store\StoreRepository;

/**
 * @method FirstStart find($id, $lockMode = LockMode::NONE, $lockVersion = null)
 * @method FirstStart findOneBy(array $criteria, array $sort = array(), array $hints = array())
 */
class FirstStartRepository extends DocumentRepository
{
    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @param StoreRepository $storeRepository
     */
    public function setStoreRepository(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * @return FirstStart
     */
    public function findOrCreate()
    {
        $firstStart = $this->findOneBy(array());
        if (null === $firstStart) {
            $firstStart = $this->createNew();
            $this->save($firstStart);
        }
        $this->populateFirstStart($firstStart);
        return $firstStart;
    }

    /**
     * @param FirstStart $firstStart
     */
    public function populateFirstStart(FirstStart $firstStart)
    {
        if ($firstStart->complete) {
            return;
        }

        $stores = $this->storeRepository->findAllActive();
        foreach ($stores as $store) {
            $storeFirstStart = new StoreFirstStart();
            $storeFirstStart->store = $store;

            $firstStart->addStoreFirstStart($storeFirstStart);
        }
    }
}
