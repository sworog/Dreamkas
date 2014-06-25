<?php

namespace Lighthouse\CoreBundle\Document\LegalDetails;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @property string $inn
 * @property string $ogrnip
 * @property string $okpo
 * @property string $certificateNumber
 * @property DateTime $certificateDate
 *
 * @MongoDB\EmbeddedDocument
 */
class EntrepreneurLegalDetails extends LegalDetails
{
    const TYPE = 'entrepreneur';

    /**
     * @MongoDB\String
     * @Assert\Regex(pattern="/^\d{12}$/", message="lighthouse.validation.errors.legal_details.inn.entrepreneur")
     * @var string
     */
    protected $inn;

    /**
     * @MongoDB\String
     * @Assert\Regex(pattern="/^\d{15}$/", message="lighthouse.validation.errors.legal_details.ogrnip")
     * @var string
     */
    protected $ogrnip;

    /**
     * @MongoDB\String
     * @Assert\Regex(pattern="/^\d{10}$/", message="lighthouse.validation.errors.legal_details.okpo.entrepreneur")
     * @var string
     */
    protected $okpo;

    /**
     * @MongoDB\String
     * @Assert\Length(max=25, maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $certificateNumber;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    protected $certificateDate;
}
