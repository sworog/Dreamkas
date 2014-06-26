<?php

namespace Lighthouse\CoreBundle\Document\BankAccount;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Lighthouse\CoreBundle\Document\Organization\Organization;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property string $id
 * @property string $bic
 * @property string $bankName
 * @property string $bankAddress
 * @property string $correspondentAccount
 * @property string $account
 * @property Organization $organization
 *
 * @MongoDB\Document(repositoryClass="Lighthouse\CoreBundle\Document\BankAccount\BankAccountRepository")
 */
class BankAccount extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDb\String
     * @Assert\Regex(pattern="/^\d{9}$/", message="lighthouse.validation.errors.bank_account.bic")
     * @var string
     */
    protected $bic;

    /**
     * @MongoDb\String
     * @Assert\Length(max=300, maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $bankName;

    /**
     * @MongoDb\String
     * @Assert\Length(max=300, maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $bankAddress;

    /**
     * @MongoDb\String
     * @Assert\Length(max=30, maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $correspondentAccount;

    /**
     * @MongoDb\String
     * @Assert\NotBlank
     * @Assert\Length(max=100, maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $account;

    /**
     * @MongoDB\ReferenceOne(
     *      targetDocument="Lighthouse\CoreBundle\Document\Organization\Organization",
     *      simple=true,
     *      cascade="persist",
     *      inversedBy="bankAccounts"
     * )
     * @var Organization
     */
    protected $organization;
}
