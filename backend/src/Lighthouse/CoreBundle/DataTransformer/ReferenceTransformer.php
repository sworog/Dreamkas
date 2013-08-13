<?php

namespace Lighthouse\CoreBundle\DataTransformer;

use Lighthouse\CoreBundle\MongoDB\Reference\ReferenceProviderInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ReferenceTransformer implements DataTransformerInterface
{
    /**
     * @var ReferenceProviderInterface
     */
    protected $referenceProvider;

    /**
     * @param ReferenceProviderInterface $referenceProvider
     */
    public function __construct(ReferenceProviderInterface $referenceProvider)
    {
        $this->referenceProvider = $referenceProvider;
    }

    /**
     * @param mixed $value
     * @return int|mixed|string
     */
    public function transform($value)
    {
        if (null === $value) {
            return $value;
        } else {
            return $this->referenceProvider->getRefObjectId($value);
        }
    }

    /**
     * @param mixed $value
     * @return mixed
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function reverseTransform($value)
    {
        $document = $this->referenceProvider->getRefObject($value);

        if (null === $document) {
            throw new TransformationFailedException('Document not found');
        }

        return $document;
    }
}
