<?php

namespace Lighthouse\CoreBundle\Document\Supplier;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\File\File;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails;
use Lighthouse\CoreBundle\Document\Organization\Organizationable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $contactPerson
 * @property File $agreement
 * @property LegalDetails $legalDetails
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Supplier\SupplierRepository"
 * )
 * @Unique(fields="name", message="lighthouse.validation.errors.supplier.name.unique")
 */
class Supplier extends AbstractDocument implements Organizationable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $phone;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $fax;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $email;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $contactPerson;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\File\File",
     *     simple=true
     * )
     * @var File
     */
    protected $agreement;

    /**
     * @MongoDB\EmbedOne(
     *   targetDocument="Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails"
     * )
     * @var LegalDetails
     */
    protected $legalDetails;

    /**
     * @return LegalDetails
     */
    public function getLegalDetails()
    {
        return $this->legalDetails;
    }

    /**
     * @param LegalDetails $legalDetails
     * @return $this
     */
    public function setLegalDetails(LegalDetails $legalDetails)
    {
        $this->legalDetails = $legalDetails;
        return $this;
    }
}
