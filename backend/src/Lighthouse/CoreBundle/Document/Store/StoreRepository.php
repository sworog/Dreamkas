<?php

namespace Lighthouse\CoreBundle\Document\Store;

use Lighthouse\CoreBundle\Document\DocumentRepository;

class StoreRepository extends DocumentRepository
{
    /**
     * @return string[]
     */
    public function findAllStoresManagerIds()
    {
        $query = $this
            ->createQueryBuilder()
            ->hydrate(false)
            ->select('managers')
            ->getQuery();
        $result = $query->execute();
        $userIds = array();
        foreach ($result as $row) {
            if (isset($row['managers']) && is_array($row['managers'])) {
                foreach ($row['managers'] as $manager) {
                    $userIds[] = (string) $manager;
                }
            }
        }
        return $userIds;
    }
}
