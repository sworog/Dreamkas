<?php

namespace Lighthouse\CoreBundle\MongoDB\Reference;

interface ReferenceProviderInterface
{
    /**
     * Field that holds reference object
     * @return string
     */
    public function getReferenceField();

    /**
     * Field that will be stored in db
     * @return string
     */
    public function getIdentifier();

    /**
     * @param mixed $document
     * @return bool
     */
    public function supports($document);

    /**
     * @param $refObject
     * @return string|int
     */
    public function getRefObjectId($refObject);

    /**
     * @param string|int $refObjectId
     * @return mixed
     */
    public function getRefObject($refObjectId);
}
