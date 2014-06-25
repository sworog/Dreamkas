<?php

namespace Lighthouse\CoreBundle\Document\LegalDetails;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Organization\Organization;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string $id
 * @property string $fullName
 * @property string $legalAddress
 * @property Organization $organization
 *
 * @MongoDB\MappedSuperclass(repositoryClass="Lighthouse\CoreBundle\Document\LegalDetails\LegalDetailsRepository")
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField("type")
 * @MongoDB\DiscriminatorMap({
 *      "entrepreneur" = "Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails",
 *      "legalEntity" = "Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails"
 * })
 */
class LegalDetails extends AbstractDocument
{
    const TYPE = 'abstract';

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
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Organization\Organization",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Organization
     */
    protected $organization;

    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("type")
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }
}
