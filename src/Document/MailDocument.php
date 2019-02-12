<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.01.2019
 * Time: 17:03
 */

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;

class MailDocument
{
    private $template;
    private $subject;
    private $senderEmail;
    private $email;
    private $mailCopyList;
    private $mailList;
    private $bodyData;
    private $imageList;
    /**
     * @var \App\Document\MailTransport
     */
    private $mailTransport;

    public function __construct(MailTransport $mailTransport)
    {
        $this->mailTransport=$mailTransport;
        $this->mailList=new ArrayCollection();
        $this->imageList=new ArrayCollection();
        $this->mailCopyList=new ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMailCopyList(): \Doctrine\Common\Collections\ArrayCollection
    {
        return $this->mailCopyList;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $mailCopyList
     */
    public function setMailCopyList(\Doctrine\Common\Collections\ArrayCollection $mailCopyList): void
    {
        $this->mailCopyList = $mailCopyList;
    }

    /**
     * @param \App\Document\MailTransport $mailTransport
     */
    public function setMailTransport(\App\Document\MailTransport $mailTransport): void
    {
        $this->mailTransport = $mailTransport;
    }

    /**
     * @return \App\Document\MailTransport
     */
    public function getMailTransport(): \App\Document\MailTransport
    {
        return $this->mailTransport;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * @param mixed $senderEmail
     */
    public function setSenderEmail($senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $mainMail
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMailList(): \Doctrine\Common\Collections\ArrayCollection
    {
        return $this->mailList;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $mailList
     */
    public function setMailList(\Doctrine\Common\Collections\ArrayCollection $mailList): void
    {
        $this->mailList = $mailList;
    }

    /**
     * @return mixed
     */
    public function getBodyData()
    {
        return $this->bodyData;
    }

    /**
     * @param mixed $bodyData
     */
    public function setBodyData($bodyData): void
    {
        $this->bodyData = $bodyData;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImageList(): \Doctrine\Common\Collections\ArrayCollection
    {
        return $this->imageList;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $imageList
     */
    public function setImageList(\Doctrine\Common\Collections\ArrayCollection $imageList): void
    {
        $this->imageList = $imageList;
    }

    //-----------------------
    public function getPropertyList()
    {
        return array_keys(get_object_vars($this));
    }
}