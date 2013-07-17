<?php

namespace Lighthouse\CoreBundle\Document\Department;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Store\Store;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

/**
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Department\DepartmentRepository"
 * )
 * @Unique(fields={"number", "store"}, message="lighthouse.validation.errors.department.number.unique")
 */
class Department extends AbstractDocument
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
     * Наименование
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;

    /**
     * @MongoDB\ReferenceOne(
     *     targetDocument="Lighthouse\CoreBundle\Document\Store\Store",
     *     simple=true,
     *     cascade="persist"
     * )
     * @var Store
     */
    protected $store;
}
