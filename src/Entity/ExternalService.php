<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Security\Core\Role\Role as MyRole;


class ExternalService extends BaseUser
{
    protected $id;

    protected $locked;

    protected $expired;

    protected $credentialsExpired;

    protected $apikey;

    public function setSalt($salt)
    {
        if ($salt===null)
        {
            $salt=md5($this->username.(string)rand(1,10000));
        }
        parent::setSalt($salt);
    }

    /**
     * @return mixed
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @param mixed $locked
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return mixed
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param mixed $expired
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return mixed
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }

    /**
     * @param mixed $credentialsExpired
     */
    public function setCredentialsExpired($credentialsExpired)
    {
        $this->credentialsExpired = $credentialsExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles[] = 'ROLE_API';

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->addRole(new MyRole('ROLE_API'));
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * @param mixed $apikey
     */
    public function setApikey($apikey): void
    {
        $this->apikey = $apikey;
    }

}