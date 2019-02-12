<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 12.01.2019
 * Time: 9:59
 */

namespace App\Document;


class MailTransport
{
    private $transport = 'gmail';
    private $port = 465;
    private $host = 'smtp.gmail.com';
    private $user;
    private $password;
    private $encryption = 'ssl';

    public function __construct(array $params = [])
    {
        foreach (get_object_vars($this) as $key=>$item) {
            if (array_key_exists($key,$params)) {
                $this->$key=$params[$key];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param mixed $transport
     */
    public function setTransport($transport): void
    {
        $this->transport = $transport;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port): void
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEncryption()
    {
        return $this->encryption;
    }

    /**
     * @param mixed $encryption
     */
    public function setEncryption($encryption): void
    {
        $this->encryption = $encryption;
    }

    public function toArray()
    {
        $result=[];
        foreach (get_object_vars($this) as $key=>$item) {
            $result[$key]=$item;
        }
        return $result;
    }
}