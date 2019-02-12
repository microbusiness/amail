<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.01.2019
 * Time: 18:01
 */

namespace App\Service;

use App\Document\InternalResult;
use App\Document\MailDocument;
use App\Document\MailTransport;
use App\Entity\ExternalService;
use App\Security\CryptService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use DateTime;

class MailJobService
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;
    /**
     * @var \App\Security\CryptService
     */
    protected $crypt;
    /**
     * @var \App\Service\ParamService
     */
    protected $params;

    public function __construct(Connection $conn,CryptService $crypt,ParamService $params)
    {
        $this->conn=$conn;
        $this->crypt=$crypt;
        $this->params=$params;
    }

    public function createMailJob($data,ExternalService $user)
    {
        $result= new InternalResult();
        $mailDocResult=$this->validateMailData($data);
        if ($mailDocResult->isStatus())
        {
            $mailDocResultData=$mailDocResult->getData();
            /**
             * @var $mailDoc \App\Document\MailDocument
             */
            $mailDoc=$mailDocResultData['data'];

            $sql = "select id from mail_job_status where code=:code ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue('code','new');
            $stmt->execute();
            $mailJobStatusId=false;
            while ($row = $stmt->fetch()) {
                $mailJobStatusId=$row['id'];
            }
            $this->conn->beginTransaction();
            try {
                $sql = "insert into mail_job ";
                $insertColumns=[];
                $insertColumns['mail_job_status_id']        =['val'=>$mailJobStatusId,'type'=>''];
                $insertColumns['external_service_id']       =['val'=>$user->getId(),'type'=>''];
                $insertColumns['email']                     =['val'=>$mailDoc->getEmail(),'type'=>''];
                $insertColumns['subject']                   =['val'=>$mailDoc->getSubject(),'type'=>''];
                $insertColumns['sender_email']              =['val'=>$mailDoc->getSenderEmail(),'type'=>''];
                $insertColumns['body_data']                 =['val'=>base64_encode($this->crypt->ec(json_encode($mailDoc->getBodyData()))),'type'=>''];
                $insertColumns['template']                  =['val'=>base64_encode($this->crypt->ec(json_encode($mailDoc->getTemplate()))),'type'=>''];
                $insertColumns['transport']                 =['val'=>base64_encode($this->crypt->ec(json_encode($mailDoc->getMailTransport()->toArray()))),'type'=>''];
                $insertColumns['mail_list']                 =['val'=>json_encode($mailDoc->getMailList()->toArray()),'type'=>''];
                $insertColumns['mail_copy']                 =['val'=>json_encode($mailDoc->getMailCopyList()->toArray()),'type'=>''];
                $insertColumns['image_list']                =['val'=>json_encode($mailDoc->getImageList()->toArray()),'type'=>''];
                $insertColumns['created_at']                =['val'=>new DateTime(),'type'=>'datetime'];
                $insertColumns['updated_at']                =['val'=>new DateTime(),'type'=>'datetime'];

                $fieldInsert=implode(',',array_keys($insertColumns));
                $valuesInsert='';
                foreach ($insertColumns as $key=>$item)
                {
                    $glue=($valuesInsert=='')?(':'):(',:');
                    $valuesInsert.=$glue.$key;
                }

                $sql .= ' ('.$fieldInsert.') values '.' ('.$valuesInsert.') returning id, code ';
                $stmt = $this->conn->prepare($sql);
                foreach ($insertColumns as $key=>$field)
                {
                    if ($field['type']!='')
                    {
                        $stmt->bindValue($key, $field['val'],$field['type']);
                    }
                    else
                    {
                        $stmt->bindValue($key, $field['val']);
                    }
                }
                $stmt->execute();
                $resultInsert=$stmt->fetchAll();
                $mailJobCode=$resultInsert[0]['code'];

                $this->conn->commit();
                $result->setData(['id'=>$mailJobCode]);
            } catch (\Exception $e) {
                $this->conn->rollBack();
                $result->setBadMessage('Unsuccessful sql query execute. '.$e->getMessage());
            }
        }


        return $result;
    }

    private function validateMailData($base64EncodedData)
    {
        $result=new InternalResult();
        try {
            $enctyptedData=base64_decode($base64EncodedData);
        } catch (\Exception $e) {
            $result->setBadMessage('Unsuccessful Base64 decode mail data');
        }
        if ($result->isStatus()) {
            try {
                $jsonData=$this->crypt->dc($enctyptedData);
                if ($jsonData=='') {
                    $result->setBadMessage('Empty encrypted mail data');
                }
            } catch (\Exception $e) {
                $result->setBadMessage('Unsuccessful encrypt decode mail data');
            }
        }
        if ($result->isStatus()) {
            try {
                $data=json_decode($jsonData,true);
                if (false===is_array($data)) {
                    $result->setBadMessage('Empty json mail data');
                }
            } catch (\Exception $e) {
                $result->setBadMessage('Unsuccessful json decode mail data');
            }
        }
        if ($result->isStatus())
        {
            $doc=new MailDocument($this->mailTransportFactory());
            $propsList=$doc->getPropertyList();
            $preparedRemoteList=[];
            $keysEqualList=[];
            foreach ($data as $remoteKey=>$remoteItem) {
                $preparedRemoteList[$this->camelCase($remoteKey)]=$remoteItem;
                $keysEqualList[$this->camelCase($remoteKey)]=$remoteKey;
            }
            foreach ($propsList as $prop) {
                if (array_key_exists($prop,$preparedRemoteList)) {
                    $method='set'.strtoupper(substr($prop,0,1)).substr($prop,1,strlen($prop)-1);
                    $doc->$method($this->getPreparedMailDocVal($keysEqualList[$prop],$preparedRemoteList[$prop]));
                } else {
                    $result->setBadMessage('Key '.$keysEqualList[$prop]);
                    break;
                }
            }
            if ($result->isStatus())
            {
                $result->setData(['data'=>$doc]);
            }
        }
        return $result;
    }

    private function getPreparedMailDocVal($remoteKey,$val)
    {
        switch ($remoteKey) {
            case 'template':
                $result=$val;
                break;
            case 'subject':
                $result=$val;
                break;
            case 'sender_email':
                $result=$val;
                break;
            case 'email':
                $result=$val;
                break;
            case 'mail_copy_list':
                $result=new ArrayCollection();
                if (is_array($val)) {
                    foreach ($val as $item) {
                        $result->add($item);
                    }
                }
                break;
            case 'mail_list':
                $result=new ArrayCollection();
                if (is_array($val)) {
                    foreach ($val as $item) {
                        $result->add($item);
                    }
                }
                break;
            case 'body_data':
                $result=$val;
                break;
            case 'image_list':
                $result=new ArrayCollection();
                if (is_array($val)) {
                    foreach ($val as $item) {
                        $result->add($item);
                    }
                }
                break;
            case 'mail_transport':
                $result=new MailTransport($val);
                break;
            default:
                $result=false;
        }
        return $result;
    }

    private function mailTransportFactory() : MailTransport
    {
        $defaultMailParams=[
          'mailer_transport'=>$this->params->get('mailer_transport'),
          'mailer_port'=>$this->params->get('mailer_port'),
          'mailer_host'=>$this->params->get('mailer_host'),
          'mailer_user'=>$this->params->get('mailer_user'),
          'mailer_password'=>$this->params->get('mailer_password'),
          'mailer_encryption'=>$this->params->get('mailer_encryption'),
          'sender_email'=>$this->params->get('sender_email'),
          'copy_email'=>$this->params->get('copy_email')
        ];
        return new MailTransport($defaultMailParams);
    }

    private function camelCase($str) {
        $i = array("-","_");
        $str = preg_replace('/([a-z])([A-Z])/', "\\1 \\2", $str);
        $str = preg_replace('@[^a-zA-Z0-9\-_ ]+@', '', $str);
        $str = str_replace($i, ' ', $str);
        $str = str_replace(' ', '', ucwords(strtolower($str)));
        $str = strtolower(substr($str,0,1)).substr($str,1);
        return $str;
    }
}