<?php

namespace Lighthouse\CoreBundle\Tests\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class ValidateTestObject
{
    /**
     * @Assert\NotBlank
     * @var string
     */
    protected $field;

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
}
