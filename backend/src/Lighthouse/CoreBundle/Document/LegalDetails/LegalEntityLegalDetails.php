<?php

namespace Lighthouse\CoreBundle\Document\LegalDetails;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $okpo
 *
 * @MongoDB\Document
 */
class LegalEntityLegalDetails extends LegalDetails
{
    const TYPE = 'legalEntity';

    /**
     * @MongoDB\String
     * @Assert\Regex(pattern="/^\d{10}$/", message="lighthouse.validation.errors.legal_details.inn.legal_entity")
     * @var string
     */
    protected $inn;

    /**
     * @MongoDB\String
     * @Assert\Regex(pattern="/^\d{9}$/", message="lighthouse.validation.errors.legal_details.kpp")
     * @var string
     */
    protected $kpp;

    /**
     * @MongoDB\String
     * @Assert\Regex(pattern="/^\d{13}$/", message="lighthouse.validation.errors.legal_details.ogrn")
     * @var string
     */
    protected $ogrn;

    /**
     * @MongoDB\String
     * @Assert\Regex(pattern="/^\d{8}$/", message="lighthouse.validation.errors.legal_details.okpo.legal_entity")
     * @var string
     */
    protected $okpo;
}
