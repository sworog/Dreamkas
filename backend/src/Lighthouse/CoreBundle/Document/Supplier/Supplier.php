<?php

namespace Lighthouse\CoreBundle\Document\Supplier;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property string $id
 * @property string $name
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Supplier\SupplierRepository"
 * )
 * @Unique(fields="name", message="lighthouse.validation.errors.supplier.name.unique")
 */
class Supplier extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank
     * @Assert\Length(max="100", maxMessage="lighthouse.validation.errors.length")
     * @var string
     */
    protected $name;
}
