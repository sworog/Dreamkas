<?php

namespace Lighthouse\CoreBundle\Document\Classifier;

use Lighthouse\CoreBundle\MongoDB\Reference\ReferenceProviderInterface;
use Lighthouse\CoreBundle\Rounding\AbstractRounding;
use Lighthouse\CoreBundle\Rounding\RoundingManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.document.classifier.rounding_reference_provider")
 * @DI\Tag("reference.provider", attributes={"alias"="rounding", "op"=true})
 */
class RoundingReferenceProvider implements ReferenceProviderInterface
{
    /**
     * @var RoundingManager
     */
    protected $roundingManager;

    /**
     * @DI\InjectParams({
     *      "roundingManager" = @DI\Inject("lighthouse.core.rounding.manager")
     * })
     * @param RoundingManager $roundingManager
     */
    public function __construct(RoundingManager $roundingManager)
    {
        $this->roundingManager = $roundingManager;
    }

    /**
     * Field that holds reference object
     * @return string
     */
    public function getReferenceField()
    {
        return 'rounding';
    }

    /**
     * Field that will be stored in db
     * @return string
     */
    public function getIdentifier()
    {
        return 'roundingId';
    }

    /**
     * @param mixed $document
     * @return bool
     */
    public function supports($document)
    {
        if ($document instanceof AbstractNode) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param AbstractRounding $refObject
     * @return string|int
     */
    public function getRefObjectId($refObject)
    {
        return $refObject->getName();
    }

    /**
     * @param string|int $refObjectId
     * @return AbstractRounding
     */
    public function getRefObject($refObjectId)
    {
        return $this->roundingManager->findByName($refObjectId);
    }
}
 