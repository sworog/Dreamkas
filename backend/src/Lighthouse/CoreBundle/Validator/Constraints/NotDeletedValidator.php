<?php

namespace Lighthouse\CoreBundle\Validator\Constraints;

use Lighthouse\CoreBundle\Document\SoftDeleteableDocument;
use Lighthouse\CoreBundle\MongoDB\DocumentManager;
use Symfony\Component\Validator\Constraint;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @DI\Service("lighthouse.core.validator.not_deleted")
 * @DI\Validator("not_deleted")
 */
class NotDeletedValidator extends ConstraintValidator
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @DI\InjectParams({
     *      "documentManager" = @DI\Inject("doctrine.odm.mongodb.document_manager")
     * })
     * @param $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @param SoftDeleteableDocument $value
     * @param Constraint|NotDeleted $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!$value instanceof SoftDeleteableDocument) {
            throw new UnexpectedTypeException($value, 'SoftDeleteableDocument');
        }

        if (null !== $value->getDeletedAt()) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
