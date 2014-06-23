<?php

namespace Lighthouse\CoreBundle\Document\Organization;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $director
 * @property string $chiefAccountant
 * @property string $address
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Organization\OrganizationRepository"
 * )
 */
class Organization extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $phone;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $fax;

    /**
     * @MongoDB\String
     * @Assert\Email
     * @var string
     */
    protected $email;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $director;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $chiefAccountant;

    /**
     * @MongoDB\String
     * @var string
     */
    protected $address;
}
