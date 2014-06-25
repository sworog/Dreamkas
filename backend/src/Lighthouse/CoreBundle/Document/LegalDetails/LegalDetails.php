<?php

namespace Lighthouse\CoreBundle\Document\LegalDetails;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\MappedSuperclass(repositoryClass="Lighthouse\CoreBundle\Document\LegalDetails\LegalDetailsRepository")
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField("type")
 * @MongoDB\DiscriminatorMap({
 *      "entrepreneur" = "Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails",
 *      "legalEntity" = "Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails"
 * })
 */
abstract class LegalDetails extends AbstractDocument
{
    const TYPE_ENTREPRENEUR = 'entrepreneur';
    const TYPE_LEGAL_ENTITY = 'legalEntity';

    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\Length(max=300, maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $fullName;

    /**
     * @MongoDB\String
     * @Assert\Length(max=300, maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $legalAddress;

    /**
     * @MongoDB\String
     * @Assert\Length(
     *      min=10,
     *      max=10,
     *      exactMessage="lighthouse.validation.errors.legal_details.okpo.length"
     * )
     * @var string
     */
    protected $okpo;
}
