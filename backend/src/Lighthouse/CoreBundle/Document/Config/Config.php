<?php

namespace Lighthouse\CoreBundle\Document\Config;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;

/**
 *
 *
 * @MongoDB\Document(
 *      repositoryClass="Lighthouse\CoreBundle\Document\Config\ConfigRepository"
 * )
 *
 * @Unique(fields="name")
 */
class Config extends AbstractDocument
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
    protected $value;
}
