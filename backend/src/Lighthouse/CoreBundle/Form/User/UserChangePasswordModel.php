<?php

namespace Lighthouse\CoreBundle\Form\User;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\User\User;

/**
 * @property string $email
 * @property string $password
 * @property string $newPassword
 */
class UserChangePasswordModel extends AbstractDocument
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $newPassword;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->email = $user->email;
    }
}
