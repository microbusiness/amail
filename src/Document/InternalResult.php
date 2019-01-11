<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 24.06.2018
 * Time: 20:22
 */

namespace App\Document;


use App\Entity\ExternalService;

class InternalResult
{
    private $data = [];

    private $status = true;

    private $message = '';

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function setDataByKey($key,$data): void
    {
        if ((array_key_exists($key,$this->data))&&(isset($this->data[$key])))
        {
            $this->data[$key] = $data;
        }
    }

    /**
     * @param array $data
     */
    public function addData($key,$data): void
    {
        $this->data[$key] = $data;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @param string $message
     */
    public function setBadMessage(string $message): void
    {
        $this->message = $message;
        $this->status=false;
    }

    public function toArray($user = false)
    {
        $userData=false;
        if ($user)
        {
            if ($user instanceof ExternalService)
            {
                $userData['apikey']=$user->getApikey();
                $userData['authComplete']=true;
            }
        }
        return ['data'=>$this->data,'status'=>$this->status,'message'=>$this->message,'user'=>$userData];
    }
}