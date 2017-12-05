<?php

namespace ExternalBundle\Domain\User\Connect;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @CheckExternalCredentials
 */
class Credentials
{
    /**
     * @Assert\NotBlank
     */
    protected $email;

    /**
     * @Assert\NotBlank
     */
    protected $password;

    protected $externalId;

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return integer
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param integer $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }
}
