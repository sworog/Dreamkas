<?php

namespace Lighthouse\CoreBundle\Document\Store;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Store\StoreRepository"
 * )
 * @Unique(fields="number", message="lighthouse.validation.errors.store.number.unique")
 */
class Store extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Номер
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Regex("/^[\w\d_\-]+$/u")
     * @Assert\Length(max="50", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $number;

    /**
     * Адрес
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="300", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $address;

    /**
     * Контакты
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $contacts;
}
