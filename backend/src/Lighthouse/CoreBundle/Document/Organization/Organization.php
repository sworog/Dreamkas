<?php

namespace Lighthouse\CoreBundle\Document\Organization;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\BankAccount\BankAccount;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $director
 * @property string $chiefAccountant
 * @property string $address
 * @property LegalDetails $legalDetails
 * @property BankAccount[]|Collection $bankAccounts
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Organization\OrganizationRepository"
 * )
 */
class Organization extends AbstractDocument implements Organizationable
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
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
    protected $director;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $chiefAccountant;

    /**
     * @MongoDB\String
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $address;

    /**
     * @MongoDB\EmbedOne(
     *   targetDocument="Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails"
     * )
     * @var LegalDetails
     */
    protected $legalDetails;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\BankAccount\BankAccount",
     *      simple=true,
     *      cascade="persist",
     *      mappedBy="organization"
     * )
     * @var BankAccount[]|Collection
     */
    protected $bankAccounts;

    public function __construct()
    {
        $this->bankAccounts = new ArrayCollection();
    }

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
