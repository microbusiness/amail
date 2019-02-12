<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 26.06.2018
 * Time: 13:57
 */

namespace App\Security;

use App\Document\InternalResult as Res;
use DateTime;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\Entity\ExternalService;


class AuthApiV1Service
{
    /**
     * @var Connection
     */
    private $conn;

    private $encoderFactory;
    /**
     * @var \App\Security\CryptService
     */
    private $cryptService;

    public function __construct(Connection $conn,EncoderFactoryInterface $encoderFactory,CryptService $cryptService)
    {
        $this->conn=$conn;
        $this->encoderFactory = $encoderFactory;
        $this->cryptService=$cryptService;
    }

    public function createExternalService($data)
    {
        $result = new Res();
        if (array_key_exists('data',$data)) {
            $data=$data['data'];
        } else {
            $result->setBadMessage('Not data with data');
        }
        if ($result->isStatus())
        {
            try {
                $dataBase64Decoded=base64_decode($data);
            } catch (\Exception $e) {
                $result->setBadMessage('Not base64 decode');
            }
        }
        if ($result->isStatus())
        {
            try {
                $decryptedData=$this->cryptService->dc($dataBase64Decoded);
            } catch (\Exception $e) {
                $result->setBadMessage('Not decrypted data');
            }
        }
        if ($result->isStatus())
        {
            try {
                $realData=json_decode($decryptedData,true);
                if (false===array_key_exists('apikey',$realData)) {
                    $result->setBadMessage('Not data with apikey');
                }
            } catch (\Exception $e) {
                $result->setBadMessage('Unsuccessful json decoded data');
            }
        }

        if ($result->isStatus())
        {
            $sql = "select id from external_service where apikey=:apikey ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue('apikey',$realData['apikey']);
            $stmt->execute();
            $externalServiceId=false;
            while ($row = $stmt->fetch()) {
                $externalServiceId=$row['id'];
            }
            if ($externalServiceId) {
                $result->setBadMessage('Service with apikey '.$realData['apikey'].' already exist');
            }
        }

        if ($result->isStatus()) {
            $now=new DateTime();
            $this->conn->beginTransaction();
            try {
                $sql = "insert into external_service ";
                $sql.= " (apikey, ip, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, locked, expired, expires_at, confirmation_token, password_requested_at, roles, credentials_expired, credentials_expire_at,created_at,updated_at) ";
                $sql.= " values(:apikey,:ip,:username, :username, :email, :email, false, '', '', '2000-01-01 00:00:00.000', false, false, NULL, NULL, NULL, 'a:0:{}', false, NULL,:now,:now) returning apikey ";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue('apikey',$realData['apikey']);
                $stmt->bindValue('username',$realData['username']);
                $stmt->bindValue('ip',$realData['ip']);
                $stmt->bindValue('email',$realData['email']);
                $stmt->bindValue('now', $now,'datetime');
                $stmt->execute();
                $remoteUserId = false;
                while ($row = $stmt->fetch())
                {
                    $remoteUserId = $row['apikey'];
                }
                $this->conn->commit();
                $result->setData(['apikey'=>$remoteUserId]);
            } catch (\Exception $e) {
                $this->conn->rollBack();
                $result->setBadMessage('Unsuccessful sql query execute. '.$e->getMessage());
            }
        }
        return $result;
    }

    public function getUuid()
    {
        if (function_exists('com_create_guid') === true)
        {
            $uuid=trim(com_create_guid(), '{}');
            $result =$uuid;
        }
        else
        {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
            $result = $uuid;
        }
        return $result;
    }

    public function generatePassword($number){
        $arr = array(
          'A','B','C','D','E','F',
          'G','H','I','J','K','L',
          'M','N','O','P','R','S',
          'T','U','V','X','Y','Z',
          '1','2','3','4','5','6',
          '7','8','9','0');
        // Генерируем пароль
        $pass = "";
        for($i = 0; $i < $number; $i++)
        {
            // Вычисляем случайный индекс массива
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }
        return $pass;
    }

}