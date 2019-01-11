<?php
namespace App\Security;

use Doctrine\ORM\EntityNotFoundException;
use RemoteBundle\Entity\Site;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Exception;

class ApiKeyExternalServiceProvider implements UserProviderInterface
{
    private $em;

    public function __construct($em)
    {
        $this->em=$em;
    }

    public function getUsernameForApiKey($apiKey)
    {
        $username=false;
        $siteId=false;
        try
        {
            $sql = 'select id as id from external_service where apikey=:apikey ';
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->bindValue('apikey', $apiKey);
            $stmt->execute();
            while ($row = $stmt->fetch())
            {
                $siteId=$row['id'];
            }
        }
        catch (\Exception $e)
        {
            $siteId=false;
        }


        $entity=$this->em->getRepository('UserBundle:ExternalService')->find($siteId);
        if ($entity)
        {
            $username = $entity->getUsername();
        }

        return $username;
    }

    public function loadUserByUsername($apiKey)
    {
        $siteId=false;
        try
        {
            $sql = 'select id as id from external_service where apikey=:apikey ';
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->bindValue('apikey', $apiKey);
            $stmt->execute();
            while ($row = $stmt->fetch())
            {
                $siteId=$row['id'];
            }
        }
        catch (\Exception $e)
        {
            $siteId=false;
        }
        return $entity=$this->em->getRepository('UserBundle:ExternalService')->find($siteId);
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}