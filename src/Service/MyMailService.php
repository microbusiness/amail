<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.07.2016
 * Time: 18:02
 */

namespace App\Service;

use SmtpValidatorEmail\ValidatorEmail;
use CommonBundle\Entity\InternalResult as Res;

class MyMailService
{
    private $mailer;
    private $templating;
    private $senderEmail;
    private $kernel;
    private $mailValidator;

    public function __construct($kernel,$mailer,$templating,$validator,$senderEmail)
    {
        $this->mailer=$mailer;
        $this->templating=$templating;
        $this->senderEmail=$senderEmail;
        $this->kernel=$kernel;
        $this->mailValidator=$validator;
    }

    public function sendEmail($subject,$mainMail,$bodyData,$template,$mailList = array())
    {
        $message = new \Swift_Message($subject);
        $message->setFrom($this->senderEmail);
        $message->setTo($mainMail);
        if (count($mailList)!=0)
        {
            $message->setBcc($mailList);
        }
        $bodyData['realimage']=[];
        if (array_key_exists('image',$bodyData))
        {
            if (is_array($bodyData['image']))
            {
                foreach ($bodyData['image'] as $key=>$image)
                {
                    $bodyData['realimage'][$key]=$message->embed(\Swift_Image::fromPath($image));
                }
            }
        }

        $body=$this->templating->render('Emails/'.$template.'.html.twig',array('data' => $bodyData));
        $message->setBody($body,'text/html');
        $this->mailer->send($message);
        return true;
    }

    public function sendTestEmail()
    {
        try
        {
            $message = \Swift_Message::newInstance();
            $message->setSubject('megatesttest');
            $message->setFrom($this->senderEmail,'TESTMAIL');
            $message->setTo('mbusiness.iv@gmail.com');
            $message->setBody('Test','text/html');
            $this->mailer->send($message);
        }
        catch (\Swift_TransportException $e)
        {
            $newTime=new \DateTime();
            if (!file_exists($this->kernel->getRootDir().'/data/logs'))
            {
                mkdir($this->kernel->getRootDir().'/data/logs');
            }
            file_put_contents($this->kernel->getRootDir().'/data/logs/maillog_'.$newTime->format('YmdHis').rand(1,1000).'.log',$e->getMessage().' - '.$e->getFile().' - '.$e->getLine());
        }

        return true;
    }
}