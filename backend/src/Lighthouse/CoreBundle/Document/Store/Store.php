<?php

namespace Lighthouse\CoreBundle\Document\Store;

use Doctrine\Common\Collections\ArrayCollection;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Department\Department;
use Lighthouse\CoreBundle\Document\User\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use InvalidArgumentException;
use JMS\Serializer\Annotation\Exclude;

/**
 * @property string $id
 * @property string $number
 * @property string $address
 * @property string $contacts
 * @property ArrayCollection|Department[] $departments
 * @property ManagerCollection|User[] $storeManagers
 * @property ManagerCollection|User[] $departmentManagers
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\Store\StoreRepository"
 * )
 * @Unique(fields="number", message="lighthouse.validation.errors.store.number.unique")
 */
class Store extends AbstractDocument
{
    const REL_STORE_MANAGERS = 'storeManagers';
    const REL_DEPARTMENT_MANAGERS = 'departmentManagers';

    /**
     * @Exclude
     * @var array
     */
    public static $roles = array(
        self::REL_DEPARTMENT_MANAGERS => User::ROLE_DEPARTMENT_MANAGER,
        self::REL_STORE_MANAGERS => User::ROLE_STORE_MANAGER
    );

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

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\Department\Department",
     *      simple=true,
     *      cascade="persist",
     *      mappedBy="store"
     * )
     * @var Department[]|ArrayCollection
     */
    protected $departments;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\User\User",
     *      simple=true,
     *      cascade="persist"
     * )
     * @var ManagerCollection|User[]
     */
    protected $storeManagers;

    /**
     * @MongoDB\ReferenceMany(
     *      targetDocument="Lighthouse\CoreBundle\Document\User\User",
     *      simple=true,
     *      cascade="persist"
     * )
     * @var ManagerCollection|User[]
     */
    protected $departmentManagers;

    /**
     *
     */
    public function __construct()
    {
        $this->departments = new ArrayCollection();
        $this->storeManagers = new ManagerCollection();
        $this->departmentManagers = new ManagerCollection();
    }

    /**
     * @param string $rel
     * @return ManagerCollection|User[]
     * @throws InvalidArgumentException
     */
    public function getManagersByRel($rel)
    {
        switch ($rel) {
            case self::REL_STORE_MANAGERS:
                return $this->storeManagers;
            case self::REL_DEPARTMENT_MANAGERS:
                return $this->departmentManagers;
            default:
                throw new InvalidArgumentException(sprintf("Invalid rel '%s' given", $rel));
        }
    }

    /**
     * @param string $rel
     * @throws InvalidArgumentException
     * @return string
     */
    public static function getRoleByRel($rel)
    {
        if (isset(self::$roles[$rel])) {
            return self::$roles[$rel];
        }
        throw new InvalidArgumentException(sprintf("Invalid rel '%s' given", $rel));
    }
}
